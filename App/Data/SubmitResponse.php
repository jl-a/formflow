<?php

namespace FormFlow\Data;

final class SubmitResponse {

    public $formId = null;

    public $success = false;

    public $validationErrors = [];

    public $successMessage = '';

    public $errorMessage = '';

}
