<?php

namespace FormFlow\Core;

use FormFlow\Core\HookEventsInterface;

class Database implements HookEventsInterface {

    public static $formflow_general;
    public static $formflow_entries;

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
            data_value      longtext                                        NOT NULL,

            PRIMARY KEY  (id)
        ) $charset_collate;";

        // Database for storing entries for each form
        $sql_entries = "CREATE TABLE IF NOT EXISTS $formflow_entries (
            id              mediumint(9)                                    NOT NULL AUTO_INCREMENT,
            created         datetime        DEFAULT '0000-00-00 00:00:00'   NOT NULL,
            form_id         mediumint(9)                                    NOT NULL,
            data_value      longtext                                        NOT NULL,

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
    public static function query_all_form_details() {
        global $wpdb;

        $sql = $wpdb->prepare(
            "SELECT * FROM %i WHERE data_key='details'",
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
            "SELECT * FROM %i WHERE form_id=%d",
            self::$formflow_general,
            $id
        );

        return $wpdb->get_results( $sql );
    }

    public static function form_id_exists( $id ) {
        if ( ! is_numeric( $id ) || $id < 0 ) {
            return false;
        }
        global $wpdb;

        $sql = $wpdb->prepare(
            "SELECT COUNT(*) FROM %i WHERE form_id=%d LIMIT 1",
            self::$formflow_general,
            $id
        );

        return (bool) $wpdb->get_var( $sql ); // will be 0 or a max of 1, so cast to false or true
    }

    public static function query_largest_form_id() {
        global $wpdb;

        $sql = $wpdb->prepare(
            "SELECT MAX(form_id) FROM %i",
            self::$formflow_general,
        );

        return $wpdb->get_var( $sql ) ?: 0;
    }

    public static function write_form_item( $form_id, $data_key, $data_value ) {
        global $wpdb;

        $sql = $wpdb->prepare(
            "SELECT id FROM %i WHERE form_id=%d AND data_key=%s LIMIT 1",
            self::$formflow_general,
            $form_id,
            $data_key,
        );
        $row_id = $wpdb->get_results( $sql );
        $id = isset( $row_id[ 0 ] ) && ! empty( $row_id[ 0 ]->id ) ? $row_id[ 0 ]->id : null;

        if ( is_numeric( $id ) ) {
            $sql = $wpdb->prepare(
                "UPDATE %i SET form_id=%d, modified=CURRENT_TIMESTAMP, data_key=%s, data_value=%s WHERE id=%d",
                self::$formflow_general,
                $form_id,
                $data_key,
                serialize( $data_value ),
                $id,
            );
        } else {
            $sql = $wpdb->prepare(
                "INSERT INTO %i (form_id, created, modified, data_key, data_value) VALUES (%d, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, %s, %s)",
                self::$formflow_general,
                $form_id,
                $data_key,
                serialize( $data_value ),
            );
        }

        $wpdb->query( $sql );
    }

}
