<?php

namespace FormFlow\Data;

final class Setting {

    public $id;

    public $value = '';

    public $title = '';

    public $type = 'text';

    public $options = [];

    public $conditional = [];

    public final function __construct( $data ) {
        if ( is_string( $data ) ) {
            $this->id = $data;
        } else if (
            is_array( $data )
            && isset( $data[ 'id' ] )
            && is_string( $data[ 'id' ] )
        ) {
            $this->id = $data[ 'id' ];

            if ( isset( $data[ 'title' ] ) && is_string( $data[ 'title' ] ) ) {
                $this->title = $data[ 'title' ];
            }
            if ( isset( $data[ 'type' ] ) && is_string( $data[ 'type' ] ) ) {
                $this->type = $data[ 'type' ];
            }
            if ( isset( $data[ 'options' ] ) && is_array( $data[ 'options' ] ) ) {
                $this->options = $data[ 'options' ];
            }
            if ( isset( $data[ 'conditional' ] ) && is_array( $data[ 'conditional' ] ) ) {
                $this->conditional = $data[ 'conditional' ];
            }
        } else {
            throw new Exception( 'Cannot register Form Flow Setting: invalid ID', 1 );
        }
    }

}
