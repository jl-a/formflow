<?php

namespace FormFlow\Data;

final class Field {

    public $id;

    public $parent;

    public $title;

    public $type;

    public $position;

    public final function __construct( $raw_data, $form_id = null ) {
        $data = (array) $raw_data;

        $this->id       = ! empty( $data[ 'id' ] )       && is_string( $data[ 'id' ] )     ? $data[ 'id' ]       : wp_generate_uuid4();
        $this->parent   = ! empty( $data[ 'parent' ] )   && is_string( $data[ 'parent' ] ) ? $data[ 'parent' ]   : 'root';
        $this->title    = ! empty( $data[ 'title' ] )    && is_string( $data[ 'title' ] )  ? $data[ 'title' ]    : '';
        $this->type     = ! empty( $data[ 'type' ] )     && is_string( $data[ 'type' ] )   ? $data[ 'type' ]     : 'text';
        $this->position = ! empty( $data[ 'position' ] ) && is_int( $data[ 'position' ] )  ? $data[ 'position' ] : 0;

        apply_filters( 'formflow_field_setup', $this );
        if ( is_string( $form_id ) || is_numeric( $form_id ) ) {
            apply_filters( 'formflow_' . $form_id . '_field_setup', $this );
        }
    }

}
