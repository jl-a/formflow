<?php

namespace FormFlow\Core;

use FormFlow\Core\Database;
use FormFlow\Core\HookEventsInterface;
use FormFlow\Data\Details;
use FormFlow\Data\Form;

/**
 * Class containing static functions to fetch individual or all forms from the database, and save
 * given forms to the database.
 */
class Forms implements HookEventsInterface {
    /**
     * Hook events.
     * @return void
     */
    public function hook_events(): void {
        add_filter('formflow_get_all', [$this, 'get_all']);  // Convenience filter to get a list of all forms
        add_filter('formflow_get_single', [$this, 'get_single']);  // Convenience filter to get a single form by ID
    }

    /**
     * Reads the database and gets an array of all forms, containing details of each form. The
     * individual fields of each form can then be fetched with the <code>get_single</code>
     * function.
     *
     * @return array
     */
    public static function get_all(): array {
        $rows = Database::query_all_form_details();
        $forms = [];

        if (!$rows || !is_array($rows)) {
            $rows = [];
        }

        foreach ($rows as $raw_form) {
            if (!empty($raw_form->data_value)) {
                $forms[] = new Details(unserialize($raw_form->data_value));
            }
        }

        $forms = apply_filters('formflow_forms', $forms);
        return $forms;
    }

    /**
     * Gets all the details and fields for a single form from the dtabse, using a given ID. Returns
     * an object containing details and fields of the form.
     *
     * @param int $id    ID of the form to get
     * @return Form
     */
    public static function get_single(int $id): Form {
        $rows = Database::query_single_form($id);
        if (!sizeof($rows)) {
            return null;
        }
        foreach ($rows as $row) {
            if ($row->data_key === 'details') {
                $details = unserialize($row->data_value);
            }
            if ($row->data_key === 'fields') {
                $fields = unserialize($row->data_value);
            }
        }
        if (!$details && !$fields) {
            return null;
        }
        return new Form([
            'details' => $details,
            'fields' => $fields,
        ]);
    }

    /**
     * Saves all details and fields of a given form to the databse.
     *
     * @param Form $form     The form data to save.
     * @return array An associative array, with a <code>success</code> property, and a <code>redirect</code> property to indicate where the page should go  after saving.
     */
    public static function save(Form $form): array {
        if (!$form instanceof Form) {
            return ['success' => false];
        }

        $is_new = false;

        if (
            !is_numeric($form->details->id) ||
            !Database::form_id_exists($form->details->id)
        ) {
            $form->details->id = 1 + Database::query_largest_form_id();
            $is_new = true;
        }
        $form_id = $form->details->id;

        apply_filters('formfield_before_save', $form);
        do_action('formfield_before_save');

        Database::write_form_item($form_id, 'details', $form->details);
        Database::write_form_item($form_id, 'fields', $form->fields);

        do_action('formfield_after_save');

        return [
            'success' => true,
            'redirect' => $is_new ? get_admin_url() . 'admin.php?page=formflow-edit&form_id=' . $form_id : false,
        ];
    }
}
