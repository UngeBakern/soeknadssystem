<?php
/**
 * Job Class - Enkel stillingsklasse
 */
class Job extends Database
{
    /**
     * Hent alle stillinger i databasen basert på om de er aktive 
     * 
     */
    public function getAll() 
    {   
        try {
            $pdo = $this->connect();
            $stmt = $pdo->query("SELECT * FROM jobs WHERE active = 1 ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
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
