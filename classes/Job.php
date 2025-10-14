<?php
/**
 * Job Class - Enkel stillingsklasse
 */
class Job 
{
    /**
     * Hent alle stillinger
     */
    public static function getAll() 
    {
        global $jobs;
        return $jobs;
    }
    
    /**
     * Finn stilling basert på ID
     */
    public static function findById($id) 
    {
        global $jobs;
        
        foreach ($jobs as $job) {
            if ($job['id'] === $id) {
                return $job;
            }
        }
        
        return null;
    }
}
?>