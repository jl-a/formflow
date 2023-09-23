<?php

namespace FormFlow\App;

use FormFlow\App\HookEventsInterface;

class Assets implements HookEventsInterface {

    public function hook_events() {
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_assets' ] );
    }

    public function admin_assets( $hook_suffix ) {
        $hook_parts = explode( '_', $hook_suffix );
        if (
            sizeof( $hook_parts ) >= 3
            && substr( $hook_parts[ 2 ], 0, 8 ) === 'formflow' // only load assets on Form Flow admin pages
        ) {
            wp_enqueue_script(
                'formflow-admin-script',
                FORMFLOW_PLUGIN_URI . 'assets/build/admin.js',
                [],
                FORMFLOW_VERSION
            );

            wp_enqueue_style(
                'formflow-admin-style',
                FORMFLOW_PLUGIN_URI . 'assets/build/admin.css',
                [],
                FORMFLOW_VERSION,
                false
            );
        }
    }

}
