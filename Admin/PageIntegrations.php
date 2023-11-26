<?php

namespace FormFlow\Admin;

use FormFlow\App\HookEventsInterface;
use FormFlow\App\Forms;

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
                'active' => true,
            ],
            [
                'slug' => 'hcaptcha',
                'title' => 'hCaptcha',
                'image' => FORMFLOW_PLUGIN_URI . '/assets/images/hCaptcha.png',
                'description' => 'A security service designed to differentiate between human users and bots through various challenges, exceeding privacy standards like GDPR, CCPA, and HIPAA.',
                'website' => 'https://www.hcaptcha.com/',
                'active' => false,
            ],
            [
                'slug' => 'couldflare-turnstile',
                'title' => 'Cloudflare Turnstile',
                'image' => FORMFLOW_PLUGIN_URI . '/assets/images/Cloudflare.png',
                'description' => 'A security tool that uses an alternative system to traditional CAPTCHAs to secure form submissions from bots and and confirm visitors are real.',
                'website' => 'https://www.cloudflare.com/products/turnstile/',
                'active' => false,
            ]
        ];

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
                    <p><?= $integration[ 'description' ] ?></p>

                    <?php if ( $integration[ 'website' ] ) : ?>
                        <p><a href="<?= $integration[ 'website' ] ?>" target="_blank">Website</a></p>
                    <?php endif ?>

                    <p class="integration-buttons">
                        <?php if ( $integration[ 'active' ] ) : ?>
                            <a href="#" class="button button-secondary">Settings</a>
                            <a href="#" class="deactivate">Deactivate</a>
                        <?php else : ?>
                            <a href="#" class="button button-secondary">Activate</a>
                        <?php endif ?>
                    </p>

                </li>
            <?php endforeach ?>
        </ul>

        <?php

    }

}
