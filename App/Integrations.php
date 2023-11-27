<?php

namespace FormFlow\App;

use FormFlow\App\HookEventsInterface;
use FormFlow\App\Database;
use FormFlow\Data\Form;
use FormFlow\Data\Details;
use FormFlow\App\Util;

class Integrations implements HookEventsInterface {

    public function hook_events() {
        add_action( 'wp_ajax_formflow_activate_integration', [ $this, 'ajax_activate_integration' ] );
        add_action( 'wp_ajax_formflow_deactivate_integration', [ $this, 'ajax_deactivate_integration' ] );
    }

    public function ajax_activate_integration() {
        $slug = $_POST[ 'slug' ] ?? '';
        self::activate_integration( $slug );
    }

    public function ajax_deactivate_integration() {
        $slug = $_POST[ 'slug' ] ?? '';
        self::deactivate_integration( $slug );
    }

    /**
     * Get all saved integrations, ensuring that the returned result is just an array
     * of strings.
     */
    public static function get_activated_integrations() {
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
        $integrations = self::get_activated_integrations();
        if ( in_array( $slug, $integrations ) ) {
            return;
        }

        $integrations[] = $slug;
        update_option( 'formflow_activated_integrations', $integrations );
    }

    /**
     * Remove a slug from the list of activated integrations, and update WordPress options
     */
    public static function deactivate_integration( $slug ) {
        if ( ! is_string( $slug ) ) {
            return;
        }
        $integrations = self::get_activated_integrations();
        $index = array_search( $slug, $integrations );

        if ( $index !== false ) {
            unset( $integrations[ $index ] );
            $integrations = array_values( $integrations );
            update_option( 'formflow_activated_integrations', $integrations );
        }
    }

}
