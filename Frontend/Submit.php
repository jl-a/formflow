<?php

namespace FormFlow\Frontend;

use FormFlow\App\HookEventsInterface;
use FormFlow\App\Forms;
use FormFlow\App\Util;

class Submit implements HookEventsInterface {

    public function hook_events() {
        add_action( 'wp_ajax_formflow_submit_form', [ $this, 'submit' ] );
        add_action( 'wp_ajax_nopriv_formflow_submit_form', [ $this, 'submit' ] );
    }

    public function submit() {
        $form_id = Util::decode_html_form_data( $_POST[ 'form_id' ] ?? '' );
        $fields = Util::decode_html_form_data( $_POST[ 'fields' ] ?? '' );

        $form = Forms::get_single( $form_id );
        if ( ! $form || ! $form->fields ) {
            return;
        }

        $mail_fields = [];
        foreach ( $fields as $field ) {
            $field_details = $form->get_field( $field->id );
            $mail_fields[] = [
                'label' => $field_details->title,
                'value' => htmlspecialchars( $field->value ),
            ];
        }

        var_dump( $mail_fields );
    }

}
