<?php

namespace FormFlow\Integrations;

use FormFlow\Core\HookEventsInterface;
use FormFlow\Data\Integration;

class CloudflareTurnstile implements HookEventsInterface {

    public function hook_events() {
        $integration = new Integration( 'couldflare-turnstile' );
        $integration->title = 'Cloudflare Turnstile';
        $integration->image = FORMFLOW_PLUGIN_URI . '/assets/images/Cloudflare.png';
        $integration->website = 'https://www.cloudflare.com/products/turnstile/';
        $integration->developer = 'Form Flow';
        $integration->on_load = [ $this, 'on_load' ];
    }

    public function on_load() {

    }

}
