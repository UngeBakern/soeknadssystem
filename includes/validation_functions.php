<?php
/**
 * Enkle valideringsfunksjoner
 */

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validate_required($value) {
    return !empty(trim($value));
}
?>