<?php
/**
 * Validator Class - Enkel valideringsklasse
 */
class Validator 
{
    /**
     * Valider e-post format
     */
    public static function validateEmail($email) 
    {
        require_once __DIR__ . '/../validation_functions.php';
        return validate_email($email);
    }
    
    /**
     * Valider påkrevd felt
     */
    public static function required($value) 
    {
        return !empty(trim($value));
    }
}
?>