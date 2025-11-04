<?php
/**
 * Enkle autentiseringsfunksjoner
 */

function is_logged_in() {
    return Auth::isLoggedIn();
}

function has_role($role) {
    return Auth::hasRole($role);
}

function get_user_by_email($email) {
    return User::findByEmail($email);
}

function verify_password($password, $hash) {
    // Sikker verifisering av passord med hash
    return password_verify($password, $hash);
}

function redirect($url, $message = '', $type = 'info') {
    if (!empty($message)) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    header("Location: $url");
    exit();
}
?>