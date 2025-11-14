<?php
/**
 * Auth Class - Enkel autentiseringsklasse
 */
class Auth {
    /**
     * Sjekk om bruker er innlogget
     */
    public static function isLoggedIn() 
    {
        return !empty($_SESSION['user_id']);
    }
    
    /**
     * Hent brukerrolle
     */
    public static function getRole() {
        return $_SESSION['role'] ?? null;
    }


    /**
     * Sjekk brukerrolle
     */
    public static function hasRole($role) 
    {
        return self::getRole() === $role;
    }
    
    /**
     * Logg ut bruker
     */
    public static function logout() 
    {
        session_unset();
        session_destroy();
    }
}
?>