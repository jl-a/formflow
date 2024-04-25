<?php

namespace FormFlow\Frontend;

use FormFlow\Core\HookEventsInterface;
use FormFlow\Core\Forms;

class Render implements HookEventsInterface {

    public function hook_events() {
        add_action( 'formflow_form_render', [ $this, 'form' ], 1 );

        add_shortcode( 'formflow-form', [ $this, 'shortcode_output' ] );
    }

    public final function shortcode_output( $atts ) {
        self::form( $atts[ 'id' ] ?? null );
    }

    public static function form( $form_id ) {
        $form = Forms::get_single( $form_id );
        if ( ! $form || ! $form->fields ) {
            return;
        }

        $root_fields = array_filter(
            $form->fields,
            function( $field ) { return isset( $field->parent ) && $field->parent === 'root'; },
        );
        usort(
            $root_fields,
            function( $a, $b ) { return isset( $a->position ) && isset( $b->position ) ? $a->position - $b->position : 0; }
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
<?php self::field_list( $root_fields ); ?>
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

    public static function field_list( $fields ) {
        if ( ! is_array( $fields ) ) {
            return;
        }
        foreach ( $fields as $field ) {
            ?>
<div
    id="formflow-field-<?= $field->id ?>"
    class="formflow-field formflow-field-<?= $field->type ?>"
>
<?php self::field( $field ); ?>
</div>
            <?php
        }
    }

    public static function field( $field ) {
        ?>
<label class="formflow-label formflow-text">
<span><?= $field->title ?></span>
        <?php

        switch ( $field->type ) {

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
