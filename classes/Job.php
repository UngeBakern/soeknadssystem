<?php
/**
 * Job Class - Enkel stillingsklasse
 */
class Job 
{
    /**
     * Hent alle stillinger i databasen basert på om de er aktive 
     * 
     */
    public static function getAll() 
    {   
        $pdo = Database::connect();

        try {
            $stmt = $pdo->query("SELECT jobs.*, users.name as employer_name 
            FROM jobs 
            LEFT JOIN users ON jobs.employer_id = users.id
            WHERE jobs.is_archived = 0 
            ORDER BY jobs.created_at DESC
            ");
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
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
            SELECT 
                jobs.*, users.name as employer_name
            FROM jobs 
            LEFT JOIN users ON jobs.employer_id = users.id 
            WHERE jobs.id = ?
            LIMIT 1
            ");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }
}
