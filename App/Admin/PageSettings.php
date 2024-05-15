<?php

namespace FormFlow\Admin;

use FormFlow\Core\Integrations;

/**
 * Contains a function for rendering the settings page.
 */
class PageSettings {
    /**
     * Renders the settings page, with elements containing sub-pages of integration settings for those
     * integrations that have been enabled.
     *
     * @return void
     */
    public static function render(): void {
        if (isset($_GET['tab'])) {
            $tab_id = $_GET['tab'];

            $integrations = Integrations::get_all_integrations();

            if (
                in_array($tab_id, array_keys($integrations)) &&
                isset($integrations[$tab_id]) &&
                isset($integrations[$tab_id]->settings) &&
                is_callable($integrations[$tab_id]->settings)
            ) {
                ?>
                <div id="<?= $tab_id ?>-settings" class="formflow-settings wrap">
                
                    <?= ($integrations[$tab_id]->settings)(); ?>

                </div>
                <?php
            } else {
                ?>
                <div class="formflow-settings wrap">
                    <p>Settings tab not available</p>
                </div>
                <?php
            }

            wp_die();
        }
    }
}
