<?php
/**
 * Auth Class - Enkel autentiseringsklasse
 */
class Auth {
    /**
     * Sjekk om bruker er innlogget
     * @return bool
     */
    public static function isLoggedIn() 
    {
        return !empty($_SESSION['user_id']);
    }
    
    /**
     * Hent brukerrolle
     * @return string|null
     */
    public static function getRole() {
        return $_SESSION['role'] ?? null;
    }


    /**
     * Sjekk brukerrolle
     * @param string $role
     * @return bool
     */
    public static function hasRole($role) 
    {
        return self::getRole() === $role;
    }

    /**
     * Logg inn bruker og setter session verdier
     * @param array $user
     * @return void
     */
    public static function login($user) {
        session_regenerate_id(true);

        $_SESSION['user_id']        = $user['id'];
        $_SESSION['user_name']      = $user['name'];
        $_SESSION['user_email']     = $user['email'];
        $_SESSION['role']           = $user['role'];
        $_SESSION['logged_in_at']   = time();
    }

    /**
     * Logg ut bruker
     * @return void
     */
    public static function logout() {

        // Tøm session data
        $_SESSION = [];

        // Regenerer session-ID
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
        
    }

    /**
     * Autentiser bruker med epost og passord
     * @param string $email
     * @param string $password
     * @return array|false Returnerer bruker-array ved suksess, ellers false
     */
    public static function attempt($email, $password) {

        $user = User::findByEmail($email);

        if (!$user) {
            return false; 
        }

        if (!self::verifyPassword($password, $user['password_hash'])) {
            return false; 
        }

        return $user;
    }

    /**
     * Verifiser passord mot hash
     * @param string $password
     * @param string $hash
     * @return bool
     */
    private static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Hash passord
     * @param string $password
     * @return string
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Hent bruker ID
     * @return int|null
     */
    public static function id() {
        return $_SESSION['user_id'] ?? null; 
    }

    /**
     * Hent brukerdata fra session
     * @return array|null
     */
    public static function user() {
        if (!self::isLoggedIn()) {
            return null; 
        }

        return [
            'id'     => $_SESSION['user_id'], 
            'name'   => $_SESSION['user_name'] ?? '',
            'email'  => $_SESSION['user_email'] ?? '',
            'role'   => $_SESSION['role'] ?? '',
        ];
    }



}
?>