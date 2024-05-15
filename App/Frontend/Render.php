<?php

namespace FormFlow\Frontend;

use FormFlow\Core\Forms;
use FormFlow\Core\HookEventsInterface;
use FormFlow\Data\Field;

/**
 * Handles all the logic for rendering the form to the frontend
 */
class Render implements HookEventsInterface {
    /**
     * Intitialisation function that adds WordPress actions and shortcodes.
     *
     * @return void
     */
    public function hook_events(): void {
        add_action('formflow_form_render', [$this, 'form'], 1);
        add_shortcode('formflow-form', [$this, 'shortcode_output']);
    }

    /**
     * Called from the shortcode. Output the form HTML to the frontend
     *
     * @param array $atts
     * @return void
     */
    public final function shortcode_output(array $atts): void {
        self::form($atts['id'] ?? null);
    }

    /**
     * Output the form HTML to the frontend.
     *
     * @param int $form_id The ID of the form to be rendered.
     * @return void
     */
    public static function form(int $form_id): void {
        $form = Forms::get_single($form_id);
        if (!$form || !$form->fields) {
            return;
        }

        $root_fields = array_filter(
            $form->fields,
            function ($field) {
                return isset($field->parent) && $field->parent === 'root';
            },
        );
        usort(
            $root_fields,
            function ($a, $b) {
                return isset($a->position) && isset($b->position) ? $a->position - $b->position : 0;
            }
        );
?>
<form
    id="formflow-<?= $form_id ?>"
    class="formflow-form"
    data-form-id="<?= $form_id ?>"
>
<div class="formflow-loading">
    <div class="formflow-loading-wrap">
        <div class="c1"></div>
        <div class="c2"></div>
        <div class="c3"></div>
        <div class="c4"></div>
    </div>
</div>
<div class="formflow-message"></div>
<div class="formflow-wrap">
<div class="formflow-fields">
<?php self::field_list($root_fields); ?>
</div>
<div class="formflow-field-submit">
<input
    type="submit"
    class="formflow-input formflow-submit button"
    value="Submit"
/>
</div>
</div>
</form>
        <?php
    }

    /**
     * Output the HTML for a list of fields.
     *
     * @param array $fields An array of form fields.
     * @return void
     */
    public static function field_list(array $fields): void {
        if (!is_array($fields)) {
            return;
        }
        foreach ($fields as $field) {
        ?>
<div
    id="formflow-field-<?= $field->id ?>"
    class="formflow-field formflow-field-<?= $field->type ?>"
>
<?php self::field($field); ?>
</div>
            <?php
        }
    }

    /**
     * Outputs the HTML for a single field.
     *
     * @param Field $field The field data to render.
     * @return void
     */
    public static function field(Field $field): void {
            ?>
<label class="formflow-label formflow-text">
<span><?= $field->title ?></span>
        <?php

        switch ($field->type) {
            case 'text':
            case 'email':
        ?>
<input
    type="text"
    class="formflow-input formflow-input-<?= $field->type ?>"
    placeholder="<?= $field->title ?>"
    data-field-id="<?= $field->id ?>"
/>
                <?php
                break;

            case 'paragraph':
                ?>
<textarea
    class="formflow-input formflow-paragraph"
    placeholder="<?= $field->title ?>"
    data-field-id="<?= $field->id ?>"
></textarea>
                <?php
                break;
        }
                ?></label><?php
    }
}
