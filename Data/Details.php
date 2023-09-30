<?php

namespace FormFlow\Data;

final class Details {

    public $id;

    public $title;

    public final function __construct( $raw_data ) {
        $data = (array) $raw_data;

        $this->id       = ! empty( $data[ 'id' ] ) && is_string( $data[ 'id' ] ) || is_numeric( $data[ 'id' ] ) ? $data[ 'id' ]     : 'new';
        $this->title    = ! empty( $data[ 'title' ] ) && is_string( $data[ 'title' ] )                          ? $data[ 'title' ]  : '';
    }

}
