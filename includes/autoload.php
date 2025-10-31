<?php
/**
 * Autoload - Last inn klasser automatisk
 */

// Last inn config først (for constants og session)
require_once __DIR__ . '/config.php';

// Enkle includes for alle klasser
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Job.php';
require_once __DIR__ . '/../classes/Application.php';
require_once __DIR__ . '/../classes/Validator.php';
?>