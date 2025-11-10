<?php
require_once '../includes/autoload.php';

// Destroy session and redirect
session_destroy();
redirect('../index.php', 'Du er nå logget ut.', 'success');
?>
