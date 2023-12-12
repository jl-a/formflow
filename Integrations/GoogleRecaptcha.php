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
        $integration->settings = [ $this, 'settings' ];
        $integration->on_load = [ $this, 'on_load' ];
    }

    public function on_load() {

    }

    public function settings() {
        echo 'settings';
    }

}
