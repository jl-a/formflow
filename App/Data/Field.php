<?php

namespace FormFlow\Data;

/**
 * Field class for each instance of a field in the form.
 */
final class Field {
    /** @var string A unique ID for the field. */
    public $id;

    /** @var string The ID of the parent field. */
    public $parent;

    /** @var string The title of the field. Shown in the admin UI */
    public $title;

    /** @var string The type of the field. eg text, select, and so on */
    public $type;

    /** @var int The position of the field in the form */
    public $position;

    /**
     * @param array $raw_data
     * @param string|int $form_id
     */
    final public function __construct($raw_data, $form_id = null) {
        $data = (array) $raw_data;

        $this->id = !empty($data[ 'id' ]) && is_string($data[ 'id' ])
            ? $data[ 'id' ]
            : wp_generate_uuid4();

        $this->parent = !empty($data[ 'parent' ]) && is_string($data[ 'parent' ])
            ? $data[ 'parent' ]
            : 'root';

        $this->title = !empty($data[ 'title' ]) && is_string($data[ 'title' ])
            ? $data[ 'title' ]
            : '';

        $this->type = !empty($data[ 'type' ]) && is_string($data[ 'type' ])
            ? $data[ 'type' ]
            : 'text';

        $this->position = !empty($data[ 'position' ]) && is_int($data[ 'position' ])
            ? $data[ 'position' ]
            : 0;


        apply_filters('formflow_field_setup', $this);
        apply_filters('formflow_field_' . $this->id . '_setup', $this);

        if (is_string($form_id) || is_numeric($form_id)) {
            apply_filters('formflow_form_' . $form_id . '_field_setup', $this);
            apply_filters('formflow_form_' . $form_id . '_field_' . $this->id . '_setup', $this);
        }
    }

}
