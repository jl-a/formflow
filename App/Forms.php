<?php

namespace FormFlow\App;

use FormFlow\App\HookEventsInterface;
use FormFlow\App\Database;
use FormFlow\Data\Form;
use FormFlow\Data\Details;

class Forms implements HookEventsInterface {

    public function hook_events() {
        add_filter( 'formflow_get_all', [ $this, 'get_all' ] ); // Convenience filter to get a list of all forms
        add_filter( 'formflow_get_single', [ $this, 'get_single' ] ); // Convenience filter to get a single form by ID
    }

    public static function get_all() {
        $rows = Database::query_all_form_details();
        $forms = [];

        if ( ! $rows || ! is_array( $rows ) ) {
            $rows = [];
        }

        foreach ( $rows as $raw_form ) {
            if ( ! empty( $raw_form->data_value ) ) {
                $forms[] = new Details( unserialize( $raw_form->data_value ) );
            }
        }

        $forms = apply_filters( 'formflow_forms', $forms );
        return $forms;
    }

    public static function get_single( $id ) {
        $rows = Database::query_single_form( $id );
        if ( ! sizeof( $rows ) ) {
            return null;
        }
        foreach ( $rows as $row ) {
            if ( $row->data_key === 'details' ) {
                $details = unserialize( $row->data_value );
            }
            if ( $row->data_key === 'fields' ) {
                $fields = unserialize( $row->data_value );
            }
        }
        if ( ! $details && ! $fields ) {
            return null;
        }
        return new Form( [
            'details' => $details,
            'fields' => $fields,
        ] );
    }

    public static function save( $form ) {
        if ( ! $form instanceof Form ) {
            return [ 'success' => false ];
        }

        $is_new = false;

        if (
            ! is_numeric( $form->details->id )
            || ! Database::form_id_exists( $form->details->id )
        ) {
            $form->details->id = 1 + Database::query_largest_form_id();
            $is_new = true;
        }
        $form_id = $form->details->id;

        apply_filters( 'formfield_before_save', $form );
        do_action( 'formfield_before_save' );

        Database::write_form_item( $form_id, 'details', $form->details );
        Database::write_form_item( $form_id, 'fields', $form->fields );

        do_action( 'formfield_after_save' );

        return [
            'success' => true,
            'redirect' => $is_new ? get_admin_url() . 'admin.php?page=formflow-edit&form_id=' . $form_id : false,
        ];
    }

}
