<?php

namespace FormFlow\Admin;

use FormFlow\App\HookEventsInterface;
use FormFlow\App\Forms;
use FormFlow\App\Integrations;

class PageIntegrations implements HookEventsInterface {

    public function hook_events() {}

    public static function render() {

        $integrations = [];
        $integrations = apply_filters( 'formflow_integrations', $integrations );

        $integrations = [
            [
                'slug' => 'google-recaptcha',
                'title' => 'Google reCAPTCHA',
                'image' => FORMFLOW_PLUGIN_URI . '/assets/images/reCAPTCHA.png',
                'description' => 'A widely used security measure that employs various tests to distinguish between human users and automated bots on websites, preventing spam submissions.',
                'website' => 'https://www.google.com/recaptcha/about/',
                'developer' => 'Form Flow',
            ],
            [
                'slug' => 'hcaptcha',
                'title' => 'hCaptcha',
                'image' => FORMFLOW_PLUGIN_URI . '/assets/images/hCaptcha.png',
                'description' => 'A security service designed to differentiate between human users and bots through various challenges, exceeding privacy standards like GDPR, CCPA, and HIPAA.',
                'website' => 'https://www.hcaptcha.com/',
                'developer' => 'Form Flow',
            ],
            [
                'slug' => 'couldflare-turnstile',
                'title' => 'Cloudflare Turnstile',
                'image' => FORMFLOW_PLUGIN_URI . '/assets/images/Cloudflare.png',
                'description' => 'A security tool that uses an alternative system to traditional CAPTCHAs to secure form submissions from bots and and confirm visitors are real.',
                'website' => 'https://www.cloudflare.com/products/turnstile/',
                'developer' => 'Form Flow',
            ]
        ];

        $activated_integrations = Integrations::get_activated_integrations();

        for ( $index = 0; $index < sizeof( $integrations ); $index++ ) {
            if ( in_array( $integrations[ $index ][ 'slug' ], $activated_integrations ) ) {
                $integrations[ $index ][ 'active' ] = true;
            } else {
                $integrations[ $index ][ 'active' ] = false;
            }
        }

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
            <?php foreach ( $integrations as $integration ) : ?>
                <li class="<?= $integration[ 'active' ] ? 'active' : '' ?>">

                    <?php if ( $integration[ 'image' ] ) : ?>
                        <img src="<?= $integration[ 'image' ] ?>" />
                    <?php endif ?>

                    <h2><?= $integration[ 'title' ] ?></h2>
                    <p class="developed-by">Integration developed by <?= $integration[ 'developer' ] ?: 'Unknown' ?></p>
                    <p><?= $integration[ 'description' ] ?></p>

                    <?php if ( $integration[ 'website' ] ) : ?>
                        <p><a href="<?= $integration[ 'website' ] ?>" target="_blank">Website</a></p>
                    <?php endif ?>

                    <p class="integration-buttons">
                        <?php if ( $integration[ 'active' ] ) : ?>
                            <a href="#" class="button button-secondary">Settings</a>
                            <a href="#" class="deactivate" data-slug="<?= $integration[ 'slug' ] ?>">Deactivate</a>
                        <?php else : ?>
                            <a href="#" class="button button-secondary activate" data-slug="<?= $integration[ 'slug' ] ?>">Activate</a>
                        <?php endif ?>
                        <span class="spinner"></span>
                    </p>

                </li>
            <?php endforeach ?>
        </ul>

        <?php

    }

}
