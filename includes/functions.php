<?php
/**
 * Hovedfunksjoner for søknadssystemet
 */

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