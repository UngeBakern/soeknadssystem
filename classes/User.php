<?php
/**
 * User Class - Enkel brukerklasse
 */
class User 
{
    /**
     * Finn bruker basert på e-post
     */
    public static function findByEmail($email) 
    {
        global $users;
        
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        
        return null;
    }
    
    /**
     * Finn bruker basert på ID
     */
    public static function findById($id) 
    {
        global $users;
        
        foreach ($users as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }
        
        return null;
    }
}
?>