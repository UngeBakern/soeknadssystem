<?php
/**
 * Validator Class - Enkel valideringsklasse
 */
class Validator {
    /**
     * Valider e-post format
     */
    public static function validateEmail($email) 
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
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