<?php

namespace FormFlow\Data;

/**
 * Details of the FormFlow form, such as its ID and title.
 */
final class Details {
    /** @var string A unique ID for the form. */
    public $id;

    /** @var string The form's title, as shown in the FormFlow UI */
    public $title;

    /**
     * Given the basic data that makes up a form, extracts the form ID and returns it. If no form ID exists,
     * returns an ID of 'new'
     *
     * @param array|object $raw_data
     * @return string
     */
    public static function get_id(array|object $raw_data): string {
        $data = (array) $raw_data;

        return ! empty($data[ 'id' ]) && (is_string($data[ 'id' ]) || is_numeric($data[ 'id' ]))
            ? (string) $data[ 'id' ]
            : 'new';
    }

    /**
     * @param array|object $raw_data
     * @param ?string|int $form_id
     */
    final public function __construct(array|object $raw_data, string|int|null $form_id = null) {
        $data = (array) $raw_data;
        if (!is_string($form_id) && !is_numeric($form_id)) {
            $form_id = self::get_id($data);
        }

        $this->id = $form_id;
        $this->title = !empty($data[ 'title' ]) && is_string($data[ 'title' ]) ? $data[ 'title' ] : '';

        apply_filters('formflow_details_setup', $this);
        apply_filters('formflow_' . $form_id . '_details_setup', $this);
    }

}
