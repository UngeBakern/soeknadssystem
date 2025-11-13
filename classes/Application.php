<?php
/**
 * Application Class - Enkel søknadsklasse
 */
class Application {

    /**
     * Opprett ny søknad 
     */
    public static function create($data) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
            INSERT INTO applications (job_id, applicant_id, cover_letter, cv_path, status, created_at)
            VALUES (?, ?, ?, ?, 'Mottatt', NOW())
            ");
            $stmt->execute([
                $data['job_id'],
                $data['applicant_id'],
                $data['cover_letter'],
                $data['cv_path']
            ]);
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Database error in create: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Sjekk om bruker allerede har søkt på stilling
     */
    public static function hasApplied($job_id, $user_id) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM applications
            WHERE job_id = :job_id AND applicant_id = :user_id
            ");
            $stmt->execute([
                ':job_id' => $job_id,
                ':user_id'=> $user_id
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Database error in hasApplied: " . $e->getMessage());
            return false; 
        }
    }




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