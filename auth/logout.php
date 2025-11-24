<?php
require_once '../includes/autoload.php';

// Sjekk at bruker er logget inn
if (!is_logged_in()) {
    redirect('../auth/login.php', 'Du er ikke logget inn.', 'info');
}

// Kun tillat POST-requests 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    csrf_check('logout.php');

}

csrf_check('../dashboard/' . $_SESSION['role'] . '.php');

// Destroy session and redirect
Auth::logout();

redirect('../index.php', 'Du er nå logget ut. Mi sees', 'success');
?>
