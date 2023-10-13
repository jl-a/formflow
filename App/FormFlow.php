<?php

namespace FormFlow\App;

use FormFlow\App\HookEventsInterface;
use FormFlow\App\Database;

class FormFlow implements HookEventsInterface {

    /** List of all class instances that the plugin creates */
    private $instances = [];

    /**
     * List of all class names that the plugin will attempt to instantiate, for both the frontend
     * and for the admin section.
     */
    private $includes = [
        'App\Database',
        'App\Forms',
        'App\Assets',
    ];

    private $frontend_includes = [
        'Frontend\Render',
        'Frontend\Submit',
    ];

    /**
     * List of all class names that the plugin will attempt to instantiate for the admin section only
     * */
    private $admin_includes = [
        'Admin\Menu',
        'Admin\PageEdit',
    ];

    /**
     * Instantiates all defined classes. Once they have all been created (and their individual
     * `__construct()` functions run, if they exist), each instance's `hook_events` function
     * will be run.
     */
    public function hook_events() {
        // Create and instantiate each class
        foreach ( $this->includes as $include ) {
            $class_name = "FormFlow\\$include";
            $this->instances[] = new $class_name;
        }

        foreach ( $this->frontend_includes as $include ) {
            $class_name = "FormFlow\\$include";
            $this->instances[] = new $class_name;
        }

        if ( is_admin() ) {
            foreach ( $this->admin_includes as $include ) {
                $class_name = "FormFlow\\$include";
                $this->instances[] = new $class_name;
            }
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
