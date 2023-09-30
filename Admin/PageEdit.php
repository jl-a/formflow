<?php

namespace FormFlow\Admin;

use FormFlow\App\HookEventsInterface;
use FormFlow\App\Forms;

class PageEdit implements HookEventsInterface {

    public function hook_events() {}

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

        // If the form is invalid then redirect to the list of forms
        if (
            ! is_array( $form )
            || ( get_current_screen()->id === 'forms_page_formflow-edit' && $form_id === 'new' )
        ) {
            echo '<script>window.location="' . get_admin_url() . 'admin.php?page=formflow-forms";</script>';
            return;
        }

        do_action( 'formflow_admin_pre_pageedit', $form_id, $form );
        ?>

        <div class="wrap">
            <h1
                id="formflow-title"
                class="wp-heading-inline"
            >
                <?= __( ( $form_id === 'new' ? 'New' : 'Edit' ) . ' Form', 'formflow' ) ?> - <?= __( 'Form Flow', 'formflow' ) ?>
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
        do_action( 'formflow_admin_post_pageedit', $form_id, $form );
    }

}
