<?php

namespace FormFlow\Admin;

use FormFlow\App\Forms;

class PageForms {

    public static function render() {
        $forms = Forms::get_all();

        do_action( 'formflow_admin_pre_pageform', $forms );
        ?>

        <div class="wrap">
            <h1
                id="formflow-title"
                class="wp-heading-inline"
            >
                <?= __( 'All Forms', 'formflow' ) ?> - <?= __( 'Form Flow', 'formflow' ) ?>
            </h1>
            <a
                href="<?= get_admin_url() ?>admin.php?page=formflow-new"
                class="page-title-action"
            >
                <?= __( 'Add New', 'formflow' ) ?>
            </a>
        </div>

        <ul id="formflow-form-list">
            <?php foreach ( $forms as $form ) : ?>
                <li>
                    <a href="<?= get_admin_url() ?>admin.php?page=formflow-edit&form_id=<?= $form->id ?>">
                        <?= $form->title ?: 'Untitled Form' ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>

        <?php
        do_action( 'formflow_admin_post_pageform', $forms );
    }

}
