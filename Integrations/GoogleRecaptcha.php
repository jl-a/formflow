<?php

namespace FormFlow\Integrations;

use FormFlow\App\HookEventsInterface;
use FormFlow\Data\Integration;

class GoogleRecaptcha implements HookEventsInterface {

    public function hook_events() {
        $integration = new Integration( 'google-recaptcha' );
        $integration->title = 'Google reCAPTCHA';
        $integration->image = FORMFLOW_PLUGIN_URI . '/assets/images/reCAPTCHA.png';
        $integration->website = 'https://www.google.com/recaptcha/about/';
        $integration->developer = 'Form Flow';
        $integration->on_load = [ $this, 'on_load' ];
        $integration->settings = [
            'type' => [
                'type' => 'select',
                'title' => 'Type',
                'options' => [
                    'v2' => 'reCAPTCHA v2',
                    'v3' => 'reCAPTCHA v3',
                ],
            ],
            'widget' => [
                'type' => 'select',
                'title' => 'Widget',
                'options' => [
                    'checkbox' => 'Checkbox',
                    'invisible' => 'Invisible',
                ],
                'conditional' => [ [
                    'field' => 'type',
                    'conditon' => '==',
                    'value' => 'v2'
                ] ],
            ],
            'api_key' => [
                'type' => 'text',
                'title' => 'API key',
            ],
        ];
    }

    public function on_load() {
        add_action( 'formflow_validate_data', [ $this, 'validate' ], 0, 2 ); // execute this action first, and expect 2 arguments
    }

    public function validate( $form, $fields ) {
        var_dump( $fields );
        die();
    }

}
