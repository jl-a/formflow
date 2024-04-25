<?php

namespace FormFlow\Admin;

use FormFlow\Core\Integrations;

class PageSettings {

    public static function render() {

        if ( isset( $_GET[ 'tab' ] ) ) {
            $tab_id = $_GET[ 'tab' ];

            $integrations = Integrations::get_all_integrations();

            if (
                in_array( $tab_id, array_keys( $integrations ) )
                && isset( $integrations[ $tab_id ] )
                && isset( $integrations[ $tab_id ]->settings )
                && is_callable( $integrations[ $tab_id ]->settings )
            ) {
                ?><div id="<?= $tab_id ?>-settings" class="formflow-settings wrap"><?php
                    ( $integrations[ $tab_id ]->settings )();
                ?></div><?php
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
