<?php

namespace FormFlow\Core;

class Util {

    public static function decode_html_form_data( $data ) {
        return json_decode(
            html_entity_decode(
                stripslashes( $data )
            )
        );
    }

}
