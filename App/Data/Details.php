<?php

namespace FormFlow\Data;

final class Details {

    public $id;

    public $title;

    public static function get_id( $raw_data ) {
        $data = (array) $raw_data;

        if (
            ! empty( $data[ 'id' ] )
            && ( is_string( $data[ 'id' ] ) || is_numeric( $data[ 'id' ] ) )
        ) {
            $form_id = $data[ 'id' ];
        } else {
            $form_id = 'new';
        }

        return $form_id;
    }

    public final function __construct( $raw_data, $form_id = null ) {
        $data = (array) $raw_data;
        if ( ! is_string( $form_id ) && ! is_numeric( $form_id ) ) {
            $form_id = self::get_id( $data );
        }

        $this->id       = $form_id;
        $this->title    = ! empty( $data[ 'title' ] ) && is_string( $data[ 'title' ] ) ? $data[ 'title' ]  : '';

        apply_filters( 'formflow_details_setup', $this );
        apply_filters( 'formflow_' . $form_id . '_details_setup', $this );
    }

}
