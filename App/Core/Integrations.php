<?php

namespace FormFlow\Core;

use FormFlow\Core\HookEventsInterface;
use FormFlow\Data\Integration;

class Integrations implements HookEventsInterface {

    /** List of all registered integrations. This only gets populated when the register_integartion function is called */
    private static $integrations = [];

    /** List of all active integrations. Gets loaded from the database in hook_events */
    private static $activated_integrations = [];

    public function hook_events() {
        self::$activated_integrations = self::find_activated_integrations();

        add_action( 'wp_ajax_formflow_activate_integration', [ $this, 'ajax_activate_integration' ] );
        add_action( 'wp_ajax_formflow_deactivate_integration', [ $this, 'ajax_deactivate_integration' ] );

        add_action( 'wp_loaded', [ $this, 'on_load' ], 99 );
    }

    /** Function run from Ajax request, to activate an integration */
    public function ajax_activate_integration() {
        $slug = $_POST[ 'slug' ] ?? '';
        self::activate_integration( $slug );
    }

    /** Function run from Ajax request, to deactivate an integration */
    public function ajax_deactivate_integration() {
        $slug = $_POST[ 'slug' ] ?? '';
        self::deactivate_integration( $slug );
    }

    /**
     * Runs at the end of the init hook. Goes through every activated integration and calls their
     * own, individual on_load functions.
     */
    public function on_load() {
        foreach ( self::$activated_integrations as $integration_id ) {
            if (
                isset( self::$integrations[ $integration_id ] )
                && isset( self::$integrations[ $integration_id ]->on_load )
                && is_callable( self::$integrations[ $integration_id ]->on_load )
            ) {
                ( self::$integrations[ $integration_id ]->on_load )(); // attempt to call the on_load function
            }
        }
    }

    /** Registers integrations, if they are of class Integration */
    public static function register_integration( $integration ) {
        if ( $integration instanceof Integration ) {
            if ( in_array( $integration->id, self::$activated_integrations ) ) {
                $integration->active = true;
            } else {
                $integration->active = false;
            }
            self::$integrations[ $integration->id ] = $integration;
        }
    }

    public static function get_all_integrations() {
        $integrations = apply_filters( 'formflow_integrations', self::$integrations );
        return $integrations;
    }

    public static function get_activated_integrations() {
        $activated_integrations = apply_filters( 'formflow_activated_integrations', self::$activated_integrations );
        return $activated_integrations;
    }

    /**
     * Get all saved integrations from the datavase, ensuring that the returned result is
     * just an array of strings.
     */
    public static function find_activated_integrations() {
        $integrations = get_option( 'formflow_activated_integrations' );
        if ( ! is_array( $integrations ) ) {
            $integrations = [];
        }
        $integrations = array_filter( $integrations, function( $item ) {
            return is_string( $item );
        } );
        return array_values( $integrations );
    }

    /**
     * Add a unique slug to the list of activated integrations, and store it in options
     */
    public static function activate_integration( $slug ) {
        if ( ! is_string( $slug ) || ! $slug ) {
            return;
        }
        if ( in_array( $slug, self::$activated_integrations ) ) {
            return;
        }

        self::$activated_integrations[] = $slug;
        update_option( 'formflow_activated_integrations', self::$activated_integrations );
    }

    /**
     * Remove a slug from the list of activated integrations, and update WordPress options
     */
    public static function deactivate_integration( $slug ) {
        if ( ! is_string( $slug ) ) {
            return;
        }

        $index = array_search( $slug, self::$activated_integrations );

        if ( $index !== false ) {
            unset( self::$activated_integrations[ $index ] );
            self::$activated_integrations = array_values( self::$activated_integrations ); // re-evaluate indexes
            update_option( 'formflow_activated_integrations', self::$activated_integrations );
        }
    }

}
