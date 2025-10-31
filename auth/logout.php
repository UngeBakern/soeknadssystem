<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Destroy session and redirect
session_destroy();
redirect('../index.php', 'Du er nå logget ut.', 'success');
?>
