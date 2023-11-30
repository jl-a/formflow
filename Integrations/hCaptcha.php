<?php

namespace FormFlow\Integrations;

use FormFlow\App\HookEventsInterface;
use FormFlow\Data\Integration;

class hCaptcha implements HookEventsInterface {

    public function hook_events() {
        $integration = new Integration( 'hcaptcha' );
        $integration->title = 'hCaptcha';
        $integration->image = FORMFLOW_PLUGIN_URI . '/assets/images/hCaptcha.png';
        $integration->website = 'https://www.hcaptcha.com/';
        $integration->developer = 'Form Flow';
        $integration->on_load = [ $this, 'on_load' ];
    }

    public function on_load() {

    }

}
