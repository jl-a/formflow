<?php

namespace FormFlow\App;

use FormFlow\App\HookEventsInterface;

class Database implements HookEventsInterface {

    private static $formflow_general;
    private static $formflow_entries;

    public function hook_events() {
        global $wpdb;
        self::$formflow_general = $wpdb->prefix . 'formflow_general';
        self::$formflow_entries = $wpdb->prefix . 'formflow_entries';
    }

    /**
     * Creates two databases, for general Form Flow settings and for Form Flow
     * entries.
     */
    public static function create_databases() {
        global $wpdb;

        // Prepare string literals
        $charset_collate = $wpdb->get_charset_collate();
        $formflow_general = self::$formflow_general;
        $formflow_entries = self::$formflow_entries;

        // Database for storing all form settings data, and other settings such as
        // integrations
        $sql_general = "CREATE TABLE IF NOT EXISTS $formflow_general (
            id              mediumint(9)                                    NOT NULL AUTO_INCREMENT,
            created         datetime        DEFAULT '0000-00-00 00:00:00'   NOT NULL,
            modified        datetime        DEFAULT '0000-00-00 00:00:00'   NOT NULL,
            form_id         mediumint(9)                                    NOT NULL,
            data_key        varchar(255)                                    NOT NULL,
            data_value      text                                            NOT NULL,

            PRIMARY KEY  (id)
        ) $charset_collate;";

        // Database for storing entries for each form
        $sql_entries = "CREATE TABLE IF NOT EXISTS $formflow_entries (
            id              mediumint(9)                                    NOT NULL AUTO_INCREMENT,
            created         datetime        DEFAULT '0000-00-00 00:00:00'   NOT NULL,
            form_id         mediumint(9)                                    NOT NULL,
            data_value      text                                            NOT NULL,

            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_general );
        dbDelta( $sql_entries );
    }

    /**
     * Queries the database and returns details for all forms
     *
     * @return {array}
     */
    public static function query_all_forms() {
        global $wpdb;

        $sql = $wpdb->prepare(
            "SELECT * FROM %s WHERE data_key='details'",
            self::$formflow_general
        );

        return $wpdb->get_results( $sql );
    }

    /**
     * Queries the database for details, settings, and fields of a single form
     *
     * @param {number} $id
     *
     * @return {unknown}
     */
    public static function query_single_form( $id ) {
        global $wpdb;

        $sql = $wpdb->prepare(
            "SELECT * FROM %s WHERE form_id=%d",
            self::$formflow_general,
            $id
        );

        return $wpdb->get_results( $sql );
    }

}
