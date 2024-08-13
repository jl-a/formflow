<?php

namespace FormFlow\Data;

use FormFlow\Core\Integrations;

/**
 * Integration class for each instance of a registered Inregration. Registered integrations are
 * shown on the FormFLow Integrations page.
 */
final class Integration {
    /** @var string A unique ID for the integration. */
    public $id;

    /** @var string The intregration's title, as shown in the FormFlow UI */
    public $title;

    /** @var string A URL to the integration's logo */
    public $image;

    /** @var string The URL of the integration's original website. */
    public $website;

    /** @var string Who developed the integration. */
    public $developer;

    /** @var callable A function that shows the settings page. */
    public $settings;

    /** @var callable A function that runs when the integration is loaded. */
    public $on_load;

    // this will be automatically set on registration, so don't ever set this on instantiation
    public $active = false;

    /**
     * @throws \Exception
     * @param string $id
     * @param string $title
     * @param string $image
     * @param string $website
     * @param ?callable $settings
     * @param ?callable $on_load
     * @return void
     */
    final public function __construct(
        string $id, // required parameter
        string $title = '',
        string $image = '',
        string $website = '',
        ?callable $settings = null,
        ?callable $on_load = null
    ) {
        if (!$id) {
            throw new \Exception('Cannot register Form Flow Integration: invalid ID', 1);
        }

        $this->id = $id;
        $this->title = $title;
        $this->image = $image;
        $this->website = $website;
        $this->settings = $settings;
        $this->on_load = $on_load;

        Integrations::register_integration($this);
    }

}
