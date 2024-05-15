<?php

namespace FormFlow\Core;

use FormFlow\Core\HookEventsInterface;

/**
 * Load style and script assets, and provide endpoint to the frontend
 */
class Assets implements HookEventsInterface {
    /*
     * Run functions to load styles and scripts on class load
     *
     * @return void
     */
    public function hook_events(): void {
        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
        add_action('wp_enqueue_scripts', [$this, 'frontend_assets']);
        add_action('enqueue_block_editor_assets', [$this, 'frontend_assets']);
    }

    /**
     * Load style and script assets for WordPress admin. Ensures that FormFLow assets
     * are only loaded on FormFlow admin pages.
     *
     * @param string $hook_suffix
     * @return void
     */
    public function admin_assets(string $hook_suffix): void {
        $hook_parts = explode('_', $hook_suffix);
        if (
            sizeof($hook_parts) >= 3 &&
            substr($hook_parts[2], 0, 8) === 'formflow'  // only load assets on Form Flow admin pages
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

            wp_localize_script(
                'formflow-admin-script',
                'formflow',
                [
                    'ajax_url' => admin_url('admin-ajax.php'),
                ],
            );
        }
    }

    /**
     * Load style and script assets for WordPress frontend.
     *
     * @return void
     */
    public function frontend_assets(): void {
        wp_enqueue_script(
            'formflow-frontend-script',
            FORMFLOW_PLUGIN_URI . 'assets/build/frontend.js',
            [],
            FORMFLOW_VERSION
        );

        wp_enqueue_style(
            'formflow-frontend-style',
            FORMFLOW_PLUGIN_URI . 'assets/build/frontend.css',
            [],
            FORMFLOW_VERSION,
            false
        );

        wp_localize_script(
            'formflow-frontend-script',
            'formflow',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
            ],
        );
    }
}
