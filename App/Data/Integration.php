<?php

namespace FormFlow\Data;

use FormFlow\Core\Integrations;

final class Integration {

    public $id;

    public $title;

    public $image;

    public $website;

    public $developer;

    public $settings;

    public $on_load;

    public $active = false; // this will be automatically set on registration, so don't ever set this on instantiation

    public final function __construct(
        $id, // required parameter
        $title = '',
        $image = '',
        $website = '',
        $settings = null,
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
        if ( $website && is_string( $website ) ) {
            $this->website = $website;
        }
        if ( is_callable( $settings ) ) {
            $this->settings = $settings;
        }
        if ( is_callable( $on_load ) ) {
            $this->on_load = $on_load;
        }

        Integrations::register_integration( $this );
    }

}
