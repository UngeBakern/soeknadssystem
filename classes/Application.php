<?php
/**
 * Application Class - Enkel søknadsklasse
 */
class Application 
{
    /**
     * Hent alle søknader
     */
    public static function getAll() 
    {
        global $applications;
        return $applications;
    }
    
    /**
     * Finn søknader for bruker
     */
    public static function getByUserId($user_id) 
    {
        global $applications;
        
        return array_filter($applications, function($app) use ($user_id) {
            return $app['user_id'] === $user_id;
        });
    }
}
?>