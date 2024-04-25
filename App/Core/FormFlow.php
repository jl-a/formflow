<?php

namespace FormFlow\Core;

use FormFlow\Core\HookEventsInterface;
use FormFlow\Core\Database;

class FormFlow implements HookEventsInterface {

    /** List of all class instances that the plugin creates */
    private $instances = [];

    /**
     * List of all class names that the plugin will attempt to instantiate, for both the frontend
     * and for the admin section.
     */
    private $includes = [
        'Database',
        'Forms',
        'Assets',
        'Integrations'
    ];

    private $frontend_includes = [
        'Render',
        'Submit',
    ];

    /**
     * List of all class names that the plugin will attempt to instantiate for the admin section only
     * */
    private $admin_includes = [
        'Menu',
        'PageEdit',
    ];

    private $integrations = [
        'GoogleRecaptcha',
        'hCaptcha',
        'CloudflareTurnstile',
    ];

    /**
     * Instantiates all defined classes. Once they have all been created (and their individual
     * `__construct()` functions run, if they exist), each instance's `hook_events` function
     * will be run.
     */
    public function hook_events() {
        // Create and instantiate each class
        foreach ( $this->includes as $include ) {
            $class_name = "FormFlow\\Core\\$include";
            $this->instances[] = new $class_name;
        }

        foreach ( $this->frontend_includes as $include ) {
            $class_name = "FormFlow\\Frontend\\$include";
            $this->instances[] = new $class_name;
        }

        if ( is_admin() ) {
            foreach ( $this->admin_includes as $include ) {
                $class_name = "FormFlow\\Admin\\$include";
                $this->instances[] = new $class_name;
            }
        }

        foreach ( $this->integrations as $include ) {
            $class_name = "FormFlow\\Integrations\\$include";
            $this->instances[] = new $class_name;
        }

        // Run each class instance's hook_events function
        foreach ( $this->instances as $instance ) {
            if ( method_exists( $instance, 'hook_events' ) ) {
                $instance->hook_events();
            }
        }

    }

    /**
     * Runs plugin setup on activation
     */
    public static function activation() {
        Database::create_databases();
    }

}
