<?php

namespace FormFlow\App;

use FormFlow\App\HookEventsInterface;
use FormFlow\App\Database;

class Forms implements HookEventsInterface {

    public function hook_events() {
        add_filter( 'formflow_get_all', [ $this, 'get_all' ] ); // Convenience filter to get a list of all forms
        add_filter( 'formflow_get_single', [ $this, 'get_single' ] ); // Convenience filter to get a single form by ID
    }

    public static function get_all() {
        $forms = Database::query_all_forms();

        if ( ! $forms || ! is_array( $forms ) ) {
            return [];
        }

        $forms = apply_filters( 'formflow_forms', $forms );
        return $forms;
    }

    public static function get_single( $id ) {
        return null;
    }

}
