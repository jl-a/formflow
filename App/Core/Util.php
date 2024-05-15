<?php

namespace FormFlow\Core;

/**
 * Utility functions as static methods
 */
class Util {
    /**
     * Decode HTML form data from the frontend into a useful PHP object
     *
     * @param string $data
     * @return void
     */
    public static function decode_html_form_data(string $data): void {
        return json_decode(
            html_entity_decode(
                stripslashes($data)
            )
        );
    }
}
