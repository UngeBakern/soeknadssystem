<?php
/**
 * User Class - Enkel brukerklasse
 */
class User {
    /**
     * Finn bruker basert på e-post
     * @param string $email
     * @return array|null
     */
    public static function findByEmail($email) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);

            $row = $stmt->fetch();
            if (!$row) {
                return null;
            }
            return $row;

        } catch (PDOException $e) {
            error_log("Database error i User::findByEmail: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Finn bruker basert på ID
     * @param int $id
     * @return array|null
     */
    public static function findById($id) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);

            $row = $stmt->fetch();
            if (!$row) {
                return null;
            }
            return $row;
            
        } catch (PDOException $e) {

            error_log("Database error i User::findById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Opprett ny bruker
     * @param array $data
     * @return int|false Returnerer ny ID eller false
     */
    public static function create($data) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, role, password_hash, birthdate, address, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

            $stmt->execute([
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['role'],
                Auth::hashPassword($data['password']),
                $data['birthdate'],
                $data['address']
            ]);

            // Returner den nye brukerens ID
            return $pdo->lastInsertId();

        } catch (PDOException $e) {
            error_log("Database error i User::create: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Oppdater brukerinfo
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data)
    {
        $pdo = Database::connect();

        try {

            $stmt = $pdo->prepare("
            UPDATE users
            SET 
                name  = :name, 
                email = :email, 
                phone = :phone, 
                birthdate = :birthdate, 
                address = :address
            WHERE id = :id
            ");

            $result = $stmt->execute([
                'name'      => $data['name']        ?? '',
                'email'     => $data['email']       ?? '',
                'phone'     => $data['phone']       ?? '',
                'birthdate' => $data['birthdate']   ?: null,
                'address'   => $data['address']     ?: null,
                'id'        => $id
            ]);

            return $result;

        } catch (PDOException $e) {
            error_log("Database error i User::update: " . $e->getMessage());
            return false; 
        }
    }

}
?>