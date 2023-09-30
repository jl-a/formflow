<?php

namespace FormFlow\Data;

final class Form {

    public $details = [];

    public $fields = [];

    /**
     * Casts serialisable properties to an associative array and returns as a JSON string.
     */
    public function json() {
        return json_encode( $this->array() );
    }

    /**
     * Casts serialisable properties to an associative array. This does deep casting on objects
     * within child arrays.
     */
    public function array() {
        return [
            'details' => (array) $this->details,
            'fields' => array_map(
                function( $field ) { return (array) $field; },
                $this->fields,
            )
        ];
    }

    public function __construct( $raw_data ) {
        $data = (array) $raw_data;

        $this->details = new Details( $data[ 'details' ] ?? [] );

        if ( ! empty( $data[ 'fields' ] ) && is_array( $data[ 'fields' ] ) ) {
            foreach ( $data[ 'fields' ] as $field ) {
                $this->fields[] = new Field( $field );
                apply_filters( 'formflow_setup_field', end( $this->fields ) );
            }
        }

        // Verify that each field's ID is unique, and generate replacements if not
        $field_ids = array_map(
            function( $field ) { return $field->id; },
            $this->fields,
        );
        $duplicate_ids = [];
        foreach( array_count_values( $field_ids ) as $val => $c ) { // finds duplicates, see https://stackoverflow.com/a/3450223/1427563
            if ( $c > 1 ) {
                $duplicate_ids[] = $val;
            }
        }
        foreach ( $duplicate_ids as $duplicate_id ) {
            do { $new_id = wp_generate_uuid4(); }
            while ( in_array( $new_id, $field_ids ) ); // New ID is guaranteed to be unique within the form
            $field_ids[] = $new_id; // add to array of field IDs so we can check later duplicates against it too

            foreach ( $this->fields as $field ) {
                if ( $field->id === $duplicate_id ) { // replace first occurrence
                    $field->id = $new_id;
                    break;
                }
            }
        }

        apply_filters( 'formflow_setup_form', $this );
        if ( $this->details->id === 'new' ) {
            apply_filters( 'formflow_setup_new_form', $this );
        }
    }

}
