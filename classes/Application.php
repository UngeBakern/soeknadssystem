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
        $pdo = Database::connect();

        try {
            $stmt = $pdo->query("
            SELECT 
                applications.*,
                jobs.title as job_title,
                jobs.location, 
                employer.name as employer_name,
                applicant.name as applicant_name
            FROM applications
            LEFT JOIN jobs ON applications.job_id = jobs.id
            LEFT JOIN users as employer ON jobs.employer_id = employer.id
            LEFT JOIN users as applicant ON applications.user_id = applicant.id
            ORDER BY applications.created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Hent søknader for en spesifikk bruker (søker)
     */
    public static function getByApplicant($user_id) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
            SELECT 
                applications.*,
                jobs.title as job_title,
                jobs.location, 
                employer.name as employer_name
            FROM applications
            LEFT JOIN jobs ON applications.job_id = jobs.id
            LEFT JOIN users as employer ON jobs.employer_id = employer.id
            WHERE applications.user_id = ?
            ORDER BY applications.created_at DESC");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];

        }
    }
}
?>