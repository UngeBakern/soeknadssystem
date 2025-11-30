<?php
/**
 * Application Class - Enkel søknadsklasse
 */
class Application {

    /**
     * Opprett ny søknad 
     * @param array $data
     * @return int|false Returnerer ny ID eller false
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
            error_log("Database error i Application::create: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Slett søknad 
     * @param int $id
     * @return bool
     */
    public static function delete($id)
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("DELETE FROM applications WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Database error i Application::delete: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Sjekk om bruker allerede har søkt på stilling
     * @param int $job_id
     * @param int $user_id
     * @return bool
     */
    public static function hasApplied($job_id, $user_id) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM applications
            WHERE job_id = :job_id AND 
            applicant_id = :user_id
            ");
            $stmt->execute([
                ':job_id' => $job_id,
                ':user_id'=> $user_id
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                // Database returnerte ingenting, behandle som ikke søkt
                return false;
            }

            return $result['count'] > 0;

        } catch (PDOException $e) {
            error_log("Database error i Application::hasApplied: " . $e->getMessage());
            return false; 
        }
    }

    
    /**
     * Hent søknader for en spesifikk bruker (søker)
     * @param int $applicant_id
     * @return array
     */
    public static function getByApplicant($applicant_id) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
            SELECT 
                a.id,
                a.job_id,
                a.applicant_id,
                a.cover_letter,
                a.cv_path,
                a.status,
                a.created_at,

                j.title    AS job_title,
                j.location AS job_location,
                j.company  AS company,
                j.employer_id, 
                u.name AS employer_name

                FROM applications a
                LEFT JOIN jobs j ON a.job_id = j.id
                LEFT JOIN users u ON j.employer_id = u.id
                WHERE a.applicant_id = ?
                ORDER BY a.created_at DESC
            ");
            $stmt->execute([$applicant_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Database error i Application::getByApplicant: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Status 
     * @param int $user_id
     * @param string $status
     * @return array
     */
    public static function getByApplicantAndStatus($user_id, $status) {

        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
                SELECT a.*, 
                    j.title     AS job_title, 
                    j.location  AS location, 
                    u.name      AS employer_name
                FROM applications a 
                JOIN jobs j  ON a.job_id = j.id
                JOIN users u ON j.employer_id = u.id
                WHERE a.applicant_id = ?
                AND a.status = ?
                ORDER BY a.created_at DESC
            ");

            $stmt->execute([$user_id, $status]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Database error i Application::getByApplicantAndStatus: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Tall antall søknader for en spesifikk jobb 
     * @param int $jobId
     * @return int
     */
    public static function countByJob($jobId) {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
            SELECT COUNT(*) AS count
            FROM applications
            WHERE job_id = :job_id
            ");
            $stmt->execute(['job_id' => $jobId]);
            return (int)$stmt->fetchColumn();

        } catch (Exception $e) {
            error_log("Database error i Application::countByJob: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Hent antall søknader for flere jobber 
     * @param array $job_ids
     * @return array
     */
    public static function countByJobs($job_ids) {
        if (empty($job_ids)) {
            return [];
        }
        
        $pdo = Database::connect();

        try {
            $placeholders = str_repeat('?,', count($job_ids) - 1) . '?';

            $stmt = $pdo->prepare("
            SELECT 
                job_id, COUNT(*) AS count
            FROM applications
            WHERE job_id IN ($placeholders)
            GROUP BY job_id
            ");
            $stmt->execute($job_ids);

            $counts = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $counts[$row['job_id']] = (int)$row['count'];
            }

            return $counts;

        } catch (Exception $e) {
            error_log("Database error i Application::countByJobs: " . $e->getMessage());
            return [];
        }
    }


    /**
     * Hent søknad etter ID med full informasjon
     * @param int $id
     * @return array|null
     */
    public static function findById($id) {

        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
                        SELECT 
                        a.id,
                        a.job_id,
                        a.applicant_id,
                        a.cover_letter,
                        a.cv_path,
                        a.status,
                        a.created_at,
                        j.title AS job_title,
                        j.company,
                        j.location, 
                        j.employer_id,
                        u_employer.name as employer_name,
                        u_applicant.name as applicant_name,
                        u_applicant.email as applicant_email,
                        u_applicant.phone as applicant_phone
                    FROM applications a
                    LEFT JOIN jobs j ON a.job_id = j.id
                    LEFT JOIN users u_employer ON j.employer_id = u_employer.id
                    LEFT JOIN users u_applicant ON a.applicant_id = u_applicant.id
                    WHERE a.id = ?
                    ");
                    $stmt->execute([$id]);


                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (!$row) {
                        return null;
                    }
                    return $row;

        } catch (PDOException $e) {
            error_log("Database error i Application::findById: " . $e->getMessage());
            return null;
        }
    }


    /**
     * Hent alle søknader for en spesifikk jobb (for arbeidsgiver)
     * @param int $job_id
     * @return array
     */
    public static function getByJobId($job_id) {

        $pdo = Database::connect();
    
        try {
            $stmt = $pdo->prepare("
                SELECT 
                    a.id,
                    a.job_id,
                    a.applicant_id,
                    a.cover_letter,
                    a.cv_path,
                    a.status,
                    a.created_at,
                    u.name AS applicant_name,
                    u.email AS applicant_email,
                    u.phone AS applicant_phone
                FROM applications a
                LEFT JOIN users u ON a.applicant_id = u.id
                WHERE a.job_id = ?
                ORDER BY a.created_at DESC
            ");
            $stmt->execute([$job_id]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Database error i Application::getByJobId: " . $e->getMessage());
            return [];
        }
}
}


?>
