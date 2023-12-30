<?php

namespace FormFlow\Data;

use FormFlow\App\Integrations;
use FormFlow\Data\Setting;

final class Integration {

    public $id;

    public $title;

    public $image;

    public $website;

    public $developer = 'Unknown';

    public $settings;

    public $on_load;

    public $active = false; // this will be automatically set on registration, so don't ever set this on instantiation

    /**
     * Accepts an array of settings, and turns them into an onject of settings. Reads from WordPress options to populate
     * the settings with values
     * @param $settings An array of settings, conforming to
     */
    public final function load_settings( $settings ) {
        if ( ! is_array( $settings ) ) {
            return;
        }

        $integration_settings = get_option( 'formflow_settings_integration_' . $this->id );
        if ( ! is_array( $integration_settings ) ) {
            $integration_settings = [];
        }

        foreach ( $settings as $setting ) {
            if ( ! $setting instanceof Setting ) {
                $setting = new Setting( $setting );
            }

            $id = $setting->id;
            if ( isset( $this->settings->{ $id } ) ) { // no settings with duplicate IDs
                throw new Exception( 'Duplicate setting ID for ' . $this->id . ' integration: ' . $id, 1 );
            }

            $setting->value = isset( $integration_settings[ $id ] ) ?: '';
            $this->settings->{ $id } = $setting;
        }
    }

    /**
     * Stores the values and IDs of the current integration settings to a WordPress option
     */
    public final function save_settings() {
        $settings = [];
        foreach ( $this->settings as $id => $setting ) {
            $settings[ $id ] = $setting->value;
        }
        update_option( 'formflow_settings_integration_' . $this->id, $settings );
    }

    public final function __construct(
        $id, // required parameter
        $title = '',
        $image = '',
        $developer = '',
        $website = '',
        $on_load = null
    ) {
        if ( $id && is_string( $id ) ) {
            $this->id = $id;
        } else {
            throw new Exception( 'Cannot register Form Flow Integration: invalid ID', 1 );
        }
        if ( $title && is_string( $title ) ) {
            $this->title = $title;
        }
        if ( $image && is_string( $image ) ) {
            $this->image = $image;
        }
        if ( $developer && is_string( $developer ) ) {
            $this->developer = $developer;
        }
        if ( $website && is_string( $website ) ) {
            $this->website = $website;
        }
        if ( is_callable( $on_load ) ) {
            $this->on_load = $on_load;
        }

        $this->settings = new \stdClass();

        Integrations::register_integration( $this );
    }

}
