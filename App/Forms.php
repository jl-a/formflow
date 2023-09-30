<?php

namespace FormFlow\App;

use FormFlow\App\HookEventsInterface;
use FormFlow\App\Database;
use FormFlow\Data\Form;

class Forms implements HookEventsInterface {

    public function hook_events() {
        add_filter( 'formflow_get_all', [ $this, 'get_all' ] ); // Convenience filter to get a list of all forms
        add_filter( 'formflow_get_single', [ $this, 'get_single' ] ); // Convenience filter to get a single form by ID

        add_action( 'wp_ajax_formflow_save_form', [ $this, 'save' ] );
    }

    public static function get_all() {
        $forms = Database::query_all_forms();

        if ( ! $forms || ! is_array( $forms ) ) {
            $forms = [];
        }

        $forms = apply_filters( 'formflow_forms', $forms );
        return $forms;
    }

    public static function get_single( $id ) {
        $rows = Database::query_single_form( $id );
        foreach ( $rows as $row ) {
            if ( $row->data_key === 'details' ) {
                $details = unserialize( $row->data_value );
            }
            if ( $row->data_key === 'fields' ) {
                $fields = unserialize( $row->data_value );
            }
        }
        return new Form( [
            'details' => $details,
            'fields' => $fields,
        ] );
    }

    public function save() {
        $form = new Form( [
            'details' => $this->decode( $_POST[ 'details' ] ?? '' ),
            'fields' => $this->decode( $_POST[ 'fields' ] ?? '' ),
        ] );

        if ( ! Database::form_id_exists( $form->details->id ) ) {
            $form->details->id = 1 + Database::query_largest_form_id();
        }
        $form_id = $form->details->id;

        apply_filters( 'formfield_before_save', $form );
        do_action( 'formfield_before_save' );

        Database::write_form_item( $form_id, 'details', $form->details );
        Database::write_form_item( $form_id, 'fields', $form->fields );

        do_action( 'formfield_after_save' );

        wp_die();
    }

    private static function decode( $data ) {
        return json_decode(
            html_entity_decode(
                stripslashes( $data )
            )
        );
    }

}
