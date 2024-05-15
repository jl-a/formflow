<?php

namespace FormFlow\Admin;

use FormFlow\Core\HookEventsInterface;

/**
 * Registers the admin menu with related functions in WordPress.
 */
class Menu implements HookEventsInterface {
    /**
     * Registers the admin menu in WordPress.
     *
     * @return void
     */
    public function hook_events(): void {
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_head', [$this, 'hide_from_submenu']);
    }

    /**
     * Registers the admin pages for the sidebar menu
     *
     * @return void
     */
    public function admin_menu(): void {
        add_menu_page(
            __('Form Flow', 'formflow'),
            __('Forms', 'formflow'),
            'edit_posts',
            'formflow-forms',
            ['FormFlow\Admin\PageForms', 'render'],  // render function in Admin/Page.php
            $this->get_icon_svg(),
            6
        );

        add_submenu_page(
            'formflow-forms',
            __('Form Flow', 'formflow'),
            __('All Forms', 'formflow'),
            'edit_posts',
            'formflow-forms',
            ['FormFlow\Admin\PageForms', 'render'],
        );

        add_submenu_page(
            'formflow-forms',
            __('New Form', 'formflow'),
            __('New Form', 'formflow'),
            'edit_posts',
            'formflow-new',
            ['FormFlow\Admin\PageEdit', 'render'],
        );

        add_submenu_page(
            'formflow-forms',
            __('Edit Form', 'formflow'),
            __('Edit Form', 'formflow'),
            'edit_posts',
            'formflow-edit',
            ['FormFlow\Admin\PageEdit', 'render'],
        );

        add_submenu_page(
            'formflow-forms',
            __('Form Flow Integrations', 'formflow'),
            __('Integrations', 'formflow'),
            'edit_posts',
            'formflow-integrations',
            ['FormFlow\Admin\PageIntegrations', 'render'],
        );

        add_submenu_page(
            'formflow-forms',
            __('Form Flow Settings', 'formflow'),
            __('Settings', 'formflow'),
            'edit_posts',
            'formflow-settings',
            ['FormFlow\Admin\PageSettings', 'render'],
        );
    }

    /**
     * Removes the edit page from the submenu, while keeping the parent item highlighted
     *
     * @return void
     */
    public function hide_from_submenu(): void {
        remove_submenu_page('formflow-forms', 'formflow-edit');
    }

    /**
     * Returns a base64 URL for the svg for use in the menu.
     *
     * @param bool $base64 Whether or not to return base64'd output.
     * @return string SVG icon.
     */
    public function get_icon_svg(bool $base64 = true): string {
        $svg = file_get_contents(FORMFLOW_PLUGIN_PATH . '/assets/images/menu-icon.svg');

        if ($base64) {
            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        }

        return $svg;
    }
}
