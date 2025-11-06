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
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, role, password_hash, birthdate, address, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
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

        // returner den nye brukerens ID
            return $pdo->lastInsertId();

        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lagre tilbakestillingstoken for glemt passord 
     */
    public static function saveResetToken($user_id, $token, $expires) 
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
                UPDATE users 
                SET reset_token = ?,
                    reset_token_expires = ? 
                WHERE id = ?
            "); 
            return $stmt->execute([$token, $expires, $user_id]);
        } catch (PDOException $e) {
            error_log("User::saveResetToken() " . $e->getMessage());
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


    /** 
     * Last opp dokument for bruker 
     */
    public static function uploadDocument($file, $user_id, $document_type = 'other')
    {

        // Validerer filtypen
        $validation = self::validateDocument($file);
        if ($validation !== true) {
            return ['success' => false, 'message' => $validation]; 
        }



        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/soeknadssystem/uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generer unikt filnavn
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = $user_id . '_' . time() . '_' . uniqid() . '.' . strtolower($file_extension);
        $file_path = 'uploads/' . $new_filename;



        // Flytt filen til opplastingsmappen
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
        
        // Lagre i databasen 
        $document_id = self::saveDocumentToDatabase([
            'user_id' => $user_id,
            'filename' => $new_filename,
            'original_name' => $file['name'],
            'file_type' => $file_extension,
            'file_size' => $file['size'],
            'document_type' => $document_type, 
            'file_path' => $target_path
        ]);

        if ($document_id) {
            return [
            'success' => true, 
            'message' => 'Dokumentet er lastet opp!',
            'document_id' => $document_id
        ];
        } else {

        //Sletter filen hvis databaselagringen feiler

            unlink($target_path);
            return ['success' => false, 'message' => 'Kunne ikke lagre dokument.'];
            }
        }
            return ['success' => false, 'message' => 'Kunne ikke laste opp filen.'];
    }
        
    /** 
     * Valider dokument 
     */
    private static function validateDocument($file)
    {
        $allowed_extensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $max_file_size = 5242880; // 5MB

        // Sjekk om fil er lastet opp 
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return 'Ingen fil ble lastet opp eller det oppstod en feil under opplastingen.';
        }

        // Sjekk filstørrelse 
        if ($file['size'] > $max_file_size) {
            return 'Filen er for stor. Maksimal tillatt størrelse er 5MB.';
        }

        // Sjekk filtype 
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_extensions)) {
            return 'Ugyldig filtype. Tillatte typer er: PDF, DOC, DOCX, JPG, JPEG, PNG.';
        }

        // Sikkerhet - MIME-type validering 
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowed_mimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png'
        ];

        if (!in_array($mime_type, $allowed_mimes)) {
            return 'Ugyldig filtype.';
        }

        return true;

    }


    /**
     * Lagre dokumentinfo i database
     */
    private static function saveDocumentToDatabase($data) 
    {
        $pdo = Database::connect();
        
        try {
            $stmt = $pdo->prepare("
                INSERT INTO documents 
                (user_id, filename, original_name, file_type, file_size, document_type, file_path, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $data['user_id'],
                $data['filename'],
                $data['original_name'],
                $data['file_type'],
                $data['file_size'],
                $data['document_type'],
                $data['file_path']
            ]);
            
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("User::saveDocumentToDatabase() error: " . $e->getMessage());
            return false;
        }
    }



    /**
     * Hent alle dokumenter for en bruker
     */
    public static function getDocuments($user_id) 
    {
        $pdo = Database::connect();
        
        try {
            $stmt = $pdo->prepare("
                SELECT * FROM documents 
                WHERE user_id = ? 
                ORDER BY created_at DESC
            ");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("User::getDocuments() error: " . $e->getMessage());
            return [];
        }
    }


    
    /**
     * Slett dokument
     */
    public static function deleteDocument($document_id, $user_id) 
    {
        $pdo = Database::connect();
        
        try {
            // Hent dokumentinfo
            $stmt = $pdo->prepare("SELECT * FROM documents WHERE id = ? AND user_id = ?");
            $stmt->execute([$document_id, $user_id]);
            $document = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$document) {
                return ['success' => false, 'message' => 'Dokument ikke funnet.'];
            }
            
            // Slett fil fra server
            $file_path = $document['file_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            
            // Slett fra database
            $stmt = $pdo->prepare("DELETE FROM documents WHERE id = ? AND user_id = ?");
            $stmt->execute([$document_id, $user_id]);
            
            return ['success' => true, 'message' => 'Dokument slettet.'];
        } catch (PDOException $e) {
            error_log("User::deleteDocument() error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Kunne ikke slette dokument.'];
        }
    }

    /** 
     * Formater filstørrelse
     */
    public static function formatFileSize($bytes)
    { 
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }


    
}
?>