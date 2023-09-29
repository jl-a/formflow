<?php

namespace FormFlow\Data;

final class Field {

    public $id;

    public $parent;

    public $title;

    public $type;

    public $position;

    public final function __construct( $raw_data ) {
        $data = (array) $raw_data;

        $this->id       = ! empty( $data[ 'id' ] )       && is_string( $data[ 'id' ] )     ? $data[ 'id' ]       : wp_generate_uuid4();
        $this->parent   = ! empty( $data[ 'parent' ] )   && is_string( $data[ 'parent' ] ) ? $data[ 'parent' ]   : 'root';
        $this->title    = ! empty( $data[ 'title' ] )    && is_string( $data[ 'title' ] )  ? $data[ 'title' ]    : '';
        $this->type     = ! empty( $data[ 'type' ] )     && is_string( $data[ 'type' ] )   ? $data[ 'type' ]     : 'text';
        $this->position = ! empty( $data[ 'position' ] ) && is_int( $data[ 'position' ] )  ? $data[ 'position' ] : 0;
    }

}
