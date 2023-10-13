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
        $data = Util::decode_html_form_data( $_POST[ 'data' ] ?? '' );

        var_dump( $data );
    }

}
