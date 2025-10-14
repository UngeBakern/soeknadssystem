<?php
/**
 * Auth Class - Enkel autentiseringsklasse
 */
class Auth 
{
    /**
     * Sjekk om bruker er innlogget
     */
    public static function isLoggedIn() 
    {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Sjekk brukerrolle
     */
    public static function hasRole($role) 
    {
        return isset($_SESSION['user_type']) && $_SESSION['user_type'] === $role;
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