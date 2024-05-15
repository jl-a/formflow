<?php

namespace FormFlow\Admin;

use FormFlow\Core\Forms;

/**
 * Contains a function for rendering the form list page.
 */
class PageForms {
    /**
     * Renders the form list page with a list of all forms in the database.
     *
     * @return void
     */
    public static function render(): void {
        $forms = Forms::get_all();

        do_action('formflow_admin_pre_pageform', $forms);
        ?>

        <div class="wrap">
            <h1
                id="formflow-title"
                class="wp-heading-inline"
            >
                <?= __('All Forms', 'formflow') ?> - <?= __('Form Flow', 'formflow') ?>
            </h1>
            <a
                href="<?= get_admin_url() ?>admin.php?page=formflow-new"
                class="page-title-action"
            >
                <?= __('Add New', 'formflow') ?>
            </a>
        </div>

        <ul id="formflow-form-list">
            <?php foreach ($forms as $form): ?>
                <li>
                    <a href="<?= get_admin_url() ?>admin.php?page=formflow-edit&form_id=<?= $form->id ?>">
                        <?= $form->title ?: 'Untitled Form' ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>

        <?php
        do_action('formflow_admin_post_pageform', $forms);
    }
}
