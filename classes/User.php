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
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Finn bruker basert på ID
     */
    public static function findById($id) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Opprett ny bruker
     */
    public static function create($data) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, role, password_hash, birthdate, address, created_at) VALUES (?,  ?, ?, ?, ?, ?, ?, NOW())
            ");

            $stmt->execute([
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['role'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['birthdate'],
                $data['address']
            ]);

        //  // returner den nye brukerens ID
            return $pdo->lastInsertId();

        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Slett bruker 
     */

    public static function delete($id) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
}
?>