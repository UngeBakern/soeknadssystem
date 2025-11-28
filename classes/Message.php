<?php
/**
 * Message Class - Håndterer meldinger mellom employers og applicants
 */
class Message {
    
    /**
     * Send en melding til søker
     */
    public static function send($application_id, $sender_id, $receiver_id, $message) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
                INSERT INTO messages (application_id, sender_id, receiver_id, message, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $application_id,
                $sender_id,
                $receiver_id,
                $message
            ]);
            
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Message::send() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Hent alle meldinger for en søknad
     */
    public static function getByApplication($application_id) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
                SELECT m.*, 
                       sender.name as sender_name,
                       sender.role as sender_role,
                       receiver.name as receiver_name
                FROM messages m
                JOIN users sender ON m.sender_id = sender.id
                JOIN users receiver ON m.receiver_id = receiver.id
                WHERE m.application_id = ?
                ORDER BY m.created_at ASC
            ");
            
            $stmt->execute([$application_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Message::getByApplication() error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Hent antall uleste meldinger for en bruker
     */
    public static function getUnreadCount($user_id) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as count
                FROM messages
                WHERE receiver_id = ? AND is_read = 0
            ");
            
            $stmt->execute([$user_id]);
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            error_log("Message::getUnreadCount() error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Marker meldinger som lest for en søknad
     */
    public static function markAsRead($application_id, $user_id) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
                UPDATE messages 
                SET is_read = 1 
                WHERE application_id = ? AND receiver_id = ?
            ");
            
            return $stmt->execute([$application_id, $user_id]);
        } catch (PDOException $e) {
            error_log("Message::markAsRead() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Hent uleste meldinger for en bruker
     */
    public static function getUnreadMessages($user_id) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
                SELECT m.*, 
                       a.id as application_id,
                       j.title as job_title,
                       sender.name as sender_name
                FROM messages m
                JOIN applications a ON m.application_id = a.id
                JOIN jobs j ON a.job_id = j.id
                JOIN users sender ON m.sender_id = sender.id
                WHERE m.receiver_id = ? AND m.is_read = 0
                ORDER BY m.created_at DESC
            ");
            
            $stmt->execute([$user_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Message::getUnreadMessages() error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Sjekk om en bruker har tilgang til en søknads meldinger
     */
    public static function hasAccess($application_id, $user_id) 
    {
        $pdo = Database::connect();

        try {
            // Sjekk om bruker er søker eller arbeidsgiver for søknaden
            $stmt = $pdo->prepare("
                SELECT a.applicant_id, j.employer_id
                FROM applications a
                JOIN jobs j ON a.job_id = j.id
                WHERE a.id = ?
            ");
            
            $stmt->execute([$application_id]);
            $result = $stmt->fetch();
            
            if (!$result) {
                return false;
            }
            
            return ($result['applicant_id'] == $user_id || $result['employer_id'] == $user_id);
        } catch (PDOException $e) {
            error_log("Message::hasAccess() error: " . $e->getMessage());
            return false;
        }
    }
}
?>
