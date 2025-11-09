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

function auth_check($allowed_roles = []) {
    if (!is_logged_in()) {
        redirect('login.php', 'Du må være innlogget for å se denne siden.', 'danger');
    }

    if (!empty($allowed_roles)) {
        $user_role = $_SESSION['role'] ?? '';
        if(!in_array($user_role, $allowed_roles)) {
            redirect('index.php', 'Du har ikke tilgang til denne siden.', 'danger');  
        }
    }
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