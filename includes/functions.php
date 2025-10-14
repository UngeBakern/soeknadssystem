<?php
/**
 * Hovedfunksjoner for søknadssystemet
 */

// Last inn klasser og hjelpefunksjoner
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/auth_functions.php';
require_once __DIR__ . '/validation_functions.php';

// Last inn datafilene
require_once __DIR__ . '/../data/users.php';
require_once __DIR__ . '/../data/jobs.php';
require_once __DIR__ . '/../data/applications.php';

/**
 * Vis suksessmelding
 */
function show_success($message) {
    return '<div class="alert alert-success">' . htmlspecialchars($message) . '</div>';
}

/**
 * Vis feilmelding
 */
function show_error($message) {
    return '<div class="alert alert-danger">' . htmlspecialchars($message) . '</div>';
}

/**
 * Formater dato til norsk format
 */
function format_date($date) {
    return date('d.m.Y H:i', strtotime($date));
}
?>