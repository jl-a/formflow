<?php

namespace FormFlow\Admin;

use FormFlow\App\HookEventsInterface;
use FormFlow\App\Forms;
use FormFlow\Data\Form;

class PageEdit implements HookEventsInterface {

    public function hook_events() {
        add_action( 'wp_ajax_formflow_save_form', [ $this, 'save' ] );
    }

    public function save() {
        $form = new Form( [
            'details' => $this->decode( $_POST[ 'details' ] ?? '' ),
            'fields' => $this->decode( $_POST[ 'fields' ] ?? '' ),
        ] );

        $result = Forms::save( $form );

        header( 'Content-Type: application/json; charset=utf-8' );
        echo( json_encode( $result ) );
        wp_die();
    }

    public static function render() {

        // Get the form ID. It is either a positive integer, or 'new' if creating a new form,
        // or null if invalid
        if ( $_GET[ 'page' ] === 'formflow-edit' && isset( $_GET[ 'form_id' ] ) ) {
            $form_id = (int) $_GET[ 'form_id' ];
        } else if ( $_GET[ 'page' ] === 'formflow-new' ) {
            $form_id = 'new';
        } else {
            $form_id = null;
        }

        // Get the form data. It will be an array if the form exists, or and empty array if
        // creating a new form, or null if the form doesn't exist
        if ( ! $form_id ) {
            $form = null;
        } else if ( $form_id === 'new' ) {
            $form = [];
        } else {
            $form = Forms::get_single( $form_id );
            $form = $form instanceof \FormFlow\Data\Form ? $form->array() : null;
        }

        // Set the display title of the form
        if ( $form_id === 'new' ) {
            $form_title = __( 'New Form', 'formflow' );
        } else {
            $form_title = ! empty( $form[ 'details' ][ 'title' ] ) ? $form[ 'details' ][ 'title' ] : 'Untitled Form';
        }

        // If the form is invalid then redirect to the list of forms
        if (
            ! is_array( $form )
            || ( get_current_screen()->id === 'forms_page_formflow-edit' && $form_id === 'new' )
        ) {
            echo '<script>window.location="' . get_admin_url() . 'admin.php?page=formflow-forms";</script>';
            return;
        }

        do_action( 'formflow_admin_pre_pageedit', $form_id );
        ?>

        <div class="wrap">
            <h1
                id="formflow-title"
                class="wp-heading-inline"
            >
                <?= $form_title ?> - <?= __( 'Form Flow', 'formflow' ) ?>
            </h1>

            <div id="formflow-wrap">
                <div
                    id="formflow-edit"
                    data-form_id="<?= $form_id ?>"
                    data-form="<?= base64_encode( json_encode( $form ) ) ?>"
                ></div>
            </div>
        </div>

        <?php
        do_action( 'formflow_admin_post_pageedit', $form_id );
    }

    private static function decode( $data ) {
        return json_decode(
            html_entity_decode(
                stripslashes( $data )
            )
        );
    }

}
