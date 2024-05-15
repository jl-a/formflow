<?php

namespace FormFlow\Frontend;

use FormFlow\Core\Forms;
use FormFlow\Core\HookEventsInterface;
use FormFlow\Core\Util;
use FormFlow\Data\SubmitResponse;

/**
 * Handles all the logic for submitting the form to the backend, and running defined
 * sumbission actions for the form.
 */
class Submit implements HookEventsInterface {
    /**
     * Response to send to the frontend, including a success status flag.
     */
    private $response = null;

    /**
     * Intitialisation function that adds WordPress actions and shortcodes.
     *
     * @return void
     */
    public function hook_events(): void {
        add_action('wp_ajax_formflow_submit_form', [$this, 'submit']);
        add_action('wp_ajax_nopriv_formflow_submit_form', [$this, 'submit']);
    }

    /**
     * Handles the submission of the form, including mailing the form as a default action.
     *
     * @return void
     */
    public function submit(): void {
        do_action('formflow_before_submit_data');

        $form_id = Util::decode_html_form_data($_POST['form_id'] ?? '');
        $fields = Util::decode_html_form_data($_POST['fields'] ?? '');

        $fields = apply_filters('formflow_submit_data', $fields, $form_id);

        $this->response = new SubmitResponse();
        $this->response->formId = $form_id;

        $form = Forms::get_single($form_id);
        if (!$form || !$form->fields) {
            $this->response->errorMessage = 'Invalid form';
            $this->send_response();
            return; // not really needed because the send_response function will exit
        }

        do_action('formflow_validate_data', $form, $fields);

        $mail_fields = [];
        foreach ($fields as $field) {
            $field_details = $form->get_field($field->id);
            $mail_fields[] = [
                'label' => $field_details->title ?: '<i>Untitled field</i>',
                'value' => nl2br(htmlspecialchars($field->value)) ?: '<i>Nothing entered</i>',
            ];
        }
        $mail_fields = apply_filters('formflow_submit_mail_fields', $mail_fields);

        $site_name = get_bloginfo('name');
        $form_title = $form->details->title ?? 'Untitled form';
        $mail_output = '';
        ob_start();
?>
    <h2 style="font-family:sans-serif">New entry for <?= $form_title ?></h2>
    <p style="font-family:sans-serif">You have a new entry from your form, <strong><?= $form_title ?></strong>, on your website, <?= $site_name ?>.</p>
    <table style="margin:auto">
        <tbody>
            <?php foreach ($mail_fields as $mail_field): ?>
                <tr>
                    <td style="padding:10px;vertical-align:top;font-family:sans-serif"><strong><?= $mail_field['label'] ?></strong></td>
                    <td style="padding:10px;vertical-align:top;font-family:sans-serif"><?= $mail_field['value'] ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php
        $mail_output = ob_get_clean();
        $mail_output = apply_filters('formflow_submit_mail_output', $mail_output);

        $admin_email = get_option('admin_email');
        $headers = ['Content-Type: text/html; charset=UTF-8'];

        wp_mail(
            $admin_email,
            $form_title . ': New form submission from ' . $site_name,
            $mail_output,
            $headers,
        );

        $this->response->success = true;
        $this->send_response();
    }

    /**
     * Sends a JSON response to the frontend.
     *
     * @return void
     */
    private function send_response(): void {
        header('Content-Type: application/json; charset=utf-8');
        echo (json_encode($this->response));
        wp_die();
    }
}
