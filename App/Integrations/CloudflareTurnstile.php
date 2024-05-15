<?php

namespace FormFlow\Integrations;

use FormFlow\Core\HookEventsInterface;
use FormFlow\Data\Integration;

class CloudflareTurnstile implements HookEventsInterface {
    /**
     * Intitialisation function that can be safely called to load functionalty.
     *
     * @return void
     */
    public function hook_events(): void {
        $integration = new Integration('couldflare-turnstile');
        $integration->title = 'Cloudflare Turnstile';
        $integration->image = FORMFLOW_PLUGIN_URI . '/assets/images/Cloudflare.png';
        $integration->website = 'https://www.cloudflare.com/products/turnstile/';
        $integration->developer = 'Form Flow';
        $integration->on_load = [$this, 'on_load'];
    }

    /**
     * Registers the class's validation function on FormFlow validate hook.
     *
     * @return void
     */
    public function on_load(): void {}
}
