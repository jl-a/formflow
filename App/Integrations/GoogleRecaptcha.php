<?php

namespace FormFlow\Integrations;

use FormFlow\Core\HookEventsInterface;
use FormFlow\Data\Form;
use FormFlow\Data\Integration;

class GoogleRecaptcha implements HookEventsInterface {
    /**
     * Intitialisation function that can be safely called to load functionalty.
     *
     * @return void
     */
    public function hook_events(): void {
        $integration = new Integration('google-recaptcha');
        $integration->title = 'Google reCAPTCHA';
        $integration->image = FORMFLOW_PLUGIN_URI . '/assets/images/reCAPTCHA.png';
        $integration->website = 'https://www.google.com/recaptcha/about/';
        $integration->developer = 'Form Flow';
        $integration->on_load = [$this, 'on_load'];
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
                'conditional' => [[
                    'field' => 'type',
                    'conditon' => '==',
                    'value' => 'v2'
                ]],
            ],
            'api_key' => [
                'type' => 'text',
                'title' => 'API key',
            ],
        ];
    }

    /**
     * Registers the class's validation function on FormFlow validate hook.
     *
     * @return void
     */
    public function on_load(): void {
        add_action('formflow_validate_data', [$this, 'validate'], 0, 2);  // execute this action first, and expect 2 arguments
    }

    /**
     * Validate the submission
     *
     * @param Form $form The form data for content that is beubg submitted
     * @param array $fields The field content that is being submitted
     * @return void
     */
    public function validate(Form $form, array $fields): void {
        var_dump($fields);
        die();
    }
}
