<?php

namespace FormFlow\Admin;

use FormFlow\App\Integrations;

class PageSettings {

    public static function render() {

        $integrations = Integrations::get_activated_integrations();
        $integration_settings = [];

        foreach ( $integrations as $id => $integration ) {
            $integration_settings[ $id ] = (array) $integration->settings;
        }

        $settings = [
            'settings' => [],
            'integrations' => $integration_settings,
        ];

        ?>
            <div class="wrap">
                <h1
                    id="formflow-title"
                    class="wp-heading-inline"
                >
                    <?= __( 'Form Flow Settings', 'formflow' ) ?>
                </h1>

                <div id="formflow-wrap">
                    <div
                        id="formflow-settings"
                        data-settings="<?= base64_encode( json_encode( $settings ) ) ?>"
                    ></div>
                </div>
            </div>
        <?php

    }

}
