<?php

namespace FormFlow\Admin;

use FormFlow\App\Forms;
use FormFlow\App\Integrations;

class PageIntegrations {

    public static function render() {
        $all_integrations = Integrations::get_all_integrations();

        ?>

        <div class="wrap">
            <h1
                id="formflow-title"
                class="wp-heading-inline"
            >
                <?= __( 'Integrations', 'formflow' ) ?> - <?= __( 'Form Flow', 'formflow' ) ?>
            </h1>
            <div class="notice">
                <p>Integrations are deactivated by default, to reduce unnecessary options when editing forms. To use an integration, activate it and then update its settings if needed.</p>
            </div>
        </div>

        <ul id="formflow-integration-list">
            <?php foreach ( $all_integrations as $integration ) : ?>
                <li class="<?= $integration->active ? 'active' : '' ?>">

                    <?php if ( $integration->image ) : ?>
                        <img src="<?= $integration->image ?>" />
                    <?php endif ?>

                    <h2><?= $integration->title ?></h2>
                    <p class="developed-by">Integration developed by <?= $integration->developer ?: 'Unknown' ?></p>

                    <?php if ( $integration->website ) : ?>
                        <p><a href="<?= $integration->website ?>" target="_blank">Website</a></p>
                    <?php endif ?>

                    <p class="integration-buttons">
                        <?php if ( $integration->active ) : ?>
                            <?php if ( is_object( $integration->settings ) ) : ?>
                                <a href="<?= get_admin_url() ?>admin.php?page=formflow-settings&tab=<?= $integration->id ?>" class="button button-secondary">Settings</a>
                            <?php endif ?>
                            <a href="#" class="deactivate" data-slug="<?= $integration->id ?>">Deactivate</a>
                        <?php else : ?>
                            <a href="#" class="button button-secondary activate" data-slug="<?= $integration->id ?>">Activate</a>
                        <?php endif ?>
                        <span class="spinner"></span>
                    </p>

                </li>
            <?php endforeach ?>
        </ul>

        <?php

    }

}
