<?php

namespace FormFlow\Integrations;

use FormFlow\Core\HookEventsInterface;
use FormFlow\Data\Integration;

class hCaptcha implements HookEventsInterface {
    /**
     * Intitialisation function that can be safely called to load functionalty.
     *
     * @return void
     */
    public function hook_events(): void {
        $integration = new Integration('hcaptcha');
        $integration->title = 'hCaptcha';
        $integration->image = FORMFLOW_PLUGIN_URI . '/assets/images/hCaptcha.png';
        $integration->website = 'https://www.hcaptcha.com/';
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
