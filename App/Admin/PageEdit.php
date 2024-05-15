<?php

namespace FormFlow\Admin;

use FormFlow\Core\Forms;
use FormFlow\Core\HookEventsInterface;
use FormFlow\Core\Util;
use FormFlow\Data\Form;

/**
 * Contains functions for rendering the form editor page, and saving data to the database.
 */
class PageEdit implements HookEventsInterface {
    /**
     * Initialisations to add hooks.
     *
     * @return void
     */
    public function hook_events(): void {
        add_action('wp_ajax_formflow_save_form', [$this, 'save']);
    }

    /**
     * Runs on an Ajax call. Takes in form data sent from the WordPress editor, and sends it on to
     * be stored in the database.
     *
     * @return void
     */
    public function save(): void {
        $form = new Form([
            'details' => Util::decode_html_form_data($_POST['details'] ?? ''),
            'fields' => Util::decode_html_form_data($_POST['fields'] ?? ''),
        ]);

        $result = Forms::save($form);

        header('Content-Type: application/json; charset=utf-8');
        echo (json_encode($result));
        wp_die();
    }

    /**
     * Reads the form ID from the WordPress admin page URL and renders out the form editor page,
     * including data for React to parse. This function is called from the Admin\Menu class.
     *
     * @return void
     */
    public static function render(): void {
        // Get the form ID. It is either a positive integer, or 'new' if creating a new form,
        // or null if invalid
        if ($_GET['page'] === 'formflow-edit' && isset($_GET['form_id'])) {
            $form_id = (int) $_GET['form_id'];
        } else if ($_GET['page'] === 'formflow-new') {
            $form_id = 'new';
        } else {
            $form_id = null;
        }

        // Get the form data. It will be an array if the form exists, or and empty array if
        // creating a new form, or null if the form doesn't exist
        if (!$form_id) {
            $form = null;
        } else if ($form_id === 'new') {
            $form = [];
        } else {
            $form = Forms::get_single($form_id);
            $form = $form instanceof \FormFlow\Data\Form ? $form->array() : null;
        }

        // Set the display title of the form
        if ($form_id === 'new') {
            $form_title = __('New Form', 'formflow');
        } else {
            $form_title = empty($form['details']['title'])
                ? __('Edit Untitled Form', 'formflow')
                : __('Edit', 'formflow') . ' ' . $form['details']['title'];
        }

        // If the form is invalid then redirect to the list of forms
        if (
            !is_array($form) ||
            (get_current_screen()->id === 'forms_page_formflow-edit' && $form_id === 'new')
        ) {
            echo '<script>window.location="' . get_admin_url() . 'admin.php?page=formflow-forms";</script>';
            return;
        }

        do_action('formflow_admin_pre_pageedit', $form_id);
        ?>

        <div class="wrap">
            <h1
                id="formflow-title"
                class="wp-heading-inline"
            >
                <?= $form_title ?> - <?= __('Form Flow', 'formflow') ?>
            </h1>

            <div id="formflow-wrap">
                <div
                    id="formflow-edit"
                    data-form_id="<?= $form_id ?>"
                    data-form="<?= base64_encode(json_encode($form)) ?>"
                ></div>
            </div>
        </div>

        <?php
        do_action('formflow_admin_post_pageedit', $form_id);
    }
}
