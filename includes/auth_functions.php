<?php
/**
 * Enkle autentiseringsfunksjoner
 */

// Sjekk om bruker er innlogget
function is_logged_in() {
    return Auth::isLoggedIn();
}

// Sjekk om bruker har spesifikk rolle
function has_role($role) {
    return Auth::hasRole($role);
}

/**
 * Sjekker om bruker er innlogget og eventuelt har en av de tillatte rollene i $allowed_roles
 * Hvis ikke, redirect til login side eller riktig dashboard.
 */
function auth_check($allowed_roles = []) {
    if (!is_logged_in()) {
        redirect('../auth/login.php', 'Du må være innlogget for å se denne siden.', 'danger');
    }

    if (empty($allowed_roles)) {
        return;
    }
    
    foreach ($allowed_roles as $role) {

        if (has_role($role)) {
            return;
        }
    }

    // Send til riktig dashboard hvis bruker ikke har tilgang
    $dashboard = has_role('employer') 
    ? '../dashboard/employer.php' 
    : '../dashboard/applicant.php';
    redirect($dashboard, 'Du har ikke tilgang til denne siden.', 'danger'); 
}

// Hent brukerdata basert på e-post
function get_user_by_email($email) {
    return User::findByEmail($email);
}

// Verifiser passord mot hash
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// Redirect funksjon med valgfri flash melding
function redirect($url, $message = '', $type = 'success') {
    if ($message !== '') {
        set_flash($message, $type);
    }

    header("Location: $url");
    exit();
}
?>