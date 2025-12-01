<?php

/**
 * Filopplastingsklasse - Håndterer dokumentopplastinger og dokumenter for brukere
 */
class Upload
{
    /**
     * Last opp dokument for bruker
     *
     * @param array  $file          Typisk $_FILES['document']
     * @param int    $user_id       Innlogget bruker-ID
     * @param string $document_type F.eks. 'cv', 'søknad', 'other'
     *
     * @return int|false  Dokument-ID ved suksess, false ved feil
     */
    public static function uploadDocument($file, $user_id, $document_type = 'other')
    {
        $pdo = Database::connect();

        // 1) Valider dokument
        if (!self::validateDocument($file)) {
            return false;
        }

        // 2) Rate limiting – maks 10 opplastinger siste 5 minutter
        if (!self::canUploadMoreRecently($pdo, $user_id, 10, 5)) {

            show_error('For mange opplastinger. Prøv igjen senere.');

            return false;
        }

        // 3) Maks antall dokumenter per bruker – f.eks. 5
        if (!self::hasDocumentCapacity($pdo, $user_id, 5)) {

            show_error('Du har nådd maksgrensen på 5 dokumenter. Slett et dokument før du laster opp et nytt.');

            return false;
        }

        // 4) Rens originalt filnavn
        $original_name = basename($file['name']); // fjerner ev. path
        $original_name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $original_name);

        // 5) Kataloger (bruker konstanter fra config.php)
        $upload_dir_fs = defined('UPLOAD_PATH')
            ? UPLOAD_PATH
            : ($_SERVER['DOCUMENT_ROOT'] . '/soeknadssystem/uploads/');

        $upload_dir_fs = rtrim($upload_dir_fs, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $upload_dir_web = defined('UPLOAD_DIR_WEB') ? UPLOAD_DIR_WEB : 'uploads/';
        $upload_dir_web = rtrim($upload_dir_web, '/') . '/';

        // Opprett mappe hvis den ikke finnes
        if (!is_dir($upload_dir_fs)) {
            @mkdir($upload_dir_fs, 0755, true);
            if (!is_dir($upload_dir_fs)) {
                show_error('Kunne ikke opprette opplastingsmappe.');
                return false;
            }
        }

        // 6) Generer unikt filnavn og stier
        $ext          = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_filename = $user_id . '_' . time() . '_' . uniqid() . '.' . $ext;

        $target_fs_path  = $upload_dir_fs . $new_filename;
        $target_web_path = $upload_dir_web . $new_filename;

        // 7) Flytt filen til opplastingsmappen
        // (liten debug-logg som hjelper hvis noe går galt)
        if (!is_uploaded_file($file['tmp_name'])) {
            error_log('Upload-feil: tmp_name er ikke en opplastet fil: ' . ($file['tmp_name'] ?? 'mangler'));
            show_error('Kunne ikke laste opp filen.');
            return false;
        }

        if (!move_uploaded_file($file['tmp_name'], $target_fs_path)) {
            error_log('Upload-feil: move_uploaded_file feilet. tmp=' . $file['tmp_name'] . ' target=' . $target_fs_path);
            show_error('Kunne ikke laste opp filen.');
            return false;
        }

        // 8) Lagre dokumentinfo i database
        $document_id = self::saveDocumentToDatabase([
            'user_id'           => $user_id,
            'filename'          => $new_filename,
            'original_filename' => $original_name,
            'file_type'         => $ext,
            'file_size'         => $file['size'],
            'document_type'     => $document_type,
            'file_path'         => $target_web_path
        ]);

        if (!$document_id) {
            @unlink($target_fs_path);
            show_error('Kunne ikke lagre dokument i database.');
            return false;
        }

        // Suksess = dokument-ID
        return $document_id;
    }

    /**
     * Valider dokument
     *
     * @param array $file
     * @return bool  true hvis ok, false hvis feil (show_error)
     */
    private static function validateDocument($file)
    {
        $allowed_ext = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];

        // Sjekk om fil er lastet opp
        if (!isset($file) || !isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            show_error('Ingen fil ble lastet opp eller det oppstod en feil under opplastingen.');
            return false;
        }

        // Sjekk for dobbel-extension (virus.php.pdf osv.)
        $filename_without_ext = pathinfo($file['name'], PATHINFO_FILENAME);
        if (preg_match('/\.(php|phtml|php[0-9]?|exe|sh|bat|cmd)$/i', $filename_without_ext)) {
            show_error('Ugyldig filnavn.');
            return false;
        }

        // Sjekk filstørrelse (bruk MAX_FILE_SIZE hvis definert)
        $max_file_size = defined('MAX_FILE_SIZE') ? MAX_FILE_SIZE : (5 * 1024 * 1024);

        if ($file['size'] > $max_file_size) {
            $max_mb = round($max_file_size / (1024 * 1024));
            show_error('Filen er for stor. Maksimal tillatt størrelse er ' . $max_mb . 'MB.');
            return false;
        }

        // Sjekk filtype (extension)
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_ext)) {
            show_error('Ugyldig filtype. Tillatte typer er: PDF, DOC, DOCX, JPG, JPEG, PNG.');
            return false;
        }

        // MIME-type validering
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowed_mime = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png'
        ];

        if (!in_array($mime, $allowed_mime)) {
            show_error('Ugyldig filtype.');
            return false;
        }

        return true;
    }

    private static function canUploadMoreRecently($pdo, $user_id, $max_uploads = 10, $minutes_window = 5)
    {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) AS count 
            FROM documents 
            WHERE user_id = ?
              AND created_at > DATE_SUB(NOW(), INTERVAL ? MINUTE)
        ");
        $stmt->execute([$user_id, $minutes_window]);
        $row = $stmt->fetch();

        $recent_uploads = isset($row['count']) ? (int) $row['count'] : 0;

        return $recent_uploads < $max_uploads;
    }

    private static function hasDocumentCapacity($pdo, $user_id, $max_docs = 5)
    {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) AS total_count
            FROM documents
            WHERE user_id = ?
        ");
        $stmt->execute([$user_id]);
        $row        = $stmt->fetch();
        $total_docs = isset($row['total_count']) ? (int) $row['total_count'] : 0;

        return $total_docs < $max_docs;
    }

    private static function saveDocumentToDatabase($data)
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
                INSERT INTO documents 
                    (user_id, filename, original_filename, file_type, file_size, document_type, file_path, created_at)
                VALUES 
                    (?, ?, ?, ?, ?, ?, ?, NOW())
            ");

            $stmt->execute([
                $data['user_id'],
                $data['filename'],
                $data['original_filename'],
                $data['file_type'],
                $data['file_size'],
                $data['document_type'],
                $data['file_path']
            ]);

            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log('Database error i Upload::saveDocumentToDatabase: ' . $e->getMessage());
            return false;
        }
    }

    public static function getDocuments($user_id)
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
                SELECT * 
                FROM documents 
                WHERE user_id = ? 
                ORDER BY created_at DESC
            ");
            $stmt->execute([$user_id]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Database error i Upload::getDocuments: ' . $e->getMessage());
            return [];
        }
    }

    public static function deleteDocument($document_id, $user_id)
    {
        $pdo = Database::connect();

        try {
            // Hent dokumentinfo
            $stmt = $pdo->prepare("
                SELECT * 
                FROM documents 
                WHERE id = ? AND user_id = ?
            ");
            $stmt->execute([$document_id, $user_id]);
            $doc = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$doc) {
                show_error('Dokument ikke funnet.');
                return false;
            }

            $allowed_ext = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
            $file_ext    = strtolower(pathinfo($doc['file_path'], PATHINFO_EXTENSION));

            if (!in_array($file_ext, $allowed_ext)) {
                show_error('Ugyldig filtype.');
                return false;
            }

            // Bygg full filsystem-sti
            $base_path = defined('BASE_PATH')
                ? BASE_PATH
                : ($_SERVER['DOCUMENT_ROOT'] . '/soeknadssystem');

            $file_path = ltrim($doc['file_path'], '/'); // forventer "uploads/..."
            $fs_path   = $base_path . '/' . $file_path;

            $upload_dir_real = realpath(defined('UPLOAD_PATH') ? UPLOAD_PATH : ($base_path . '/uploads/'));
            $real_fs_path    = realpath($fs_path);

            // Ekstra sikkerhet: filen må ligge under uploads/
            if ($real_fs_path === false || $upload_dir_real === false || strpos($real_fs_path, $upload_dir_real) !== 0) {
                show_error('Ugyldig filsti.');
                return false;
            }

            // Slett fil fra server
            if (is_file($real_fs_path)) {
                @unlink($real_fs_path);
            }

            // Slett fra database
            $stmt = $pdo->prepare("DELETE FROM documents WHERE id = ? AND user_id = ?");
            $stmt->execute([$document_id, $user_id]);

            return true;
        } catch (PDOException $e) {
            error_log('Database error i Upload::deleteDocument: ' . $e->getMessage());
            show_error('Kunne ikke slette dokument.');
            return false;
        }
    }

    public static function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i     = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public static function attachToApplication($document_id, $application_id, $user_id)
    {
        $pdo = Database::connect();

        try {
            // Sjekk at dokumentet tilhører brukeren
            $stmt = $pdo->prepare("
                SELECT id
                FROM documents 
                WHERE id = ? AND user_id = ?
            ");
            $stmt->execute([$document_id, $user_id]);

            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                show_error('Du har ikke tilgang til dette dokumentet.');
                return false;
            }

            // Opprett kobling i application_documents
            $stmt = $pdo->prepare("
                INSERT INTO application_documents (application_id, document_id)
                VALUES (?, ?)
            ");

            return $stmt->execute([$application_id, $document_id]);
        } catch (PDOException $e) {
            error_log('Database error i Upload::attachToApplication: ' . $e->getMessage());
            show_error('Kunne ikke knytte dokument til søknad.');
            return false;
        }
    }

    public static function getDocumentsByApplication($application_id)
    {
        $pdo = Database::connect();

        try {
            $stmt = $pdo->prepare("
                SELECT d.*
                FROM application_documents ad
                JOIN documents d ON ad.document_id = d.id 
                WHERE ad.application_id = ? 
                ORDER BY d.created_at DESC
            ");
            $stmt->execute([$application_id]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Database error i Upload::getDocumentsByApplication: ' . $e->getMessage());
            return [];
        }
    }
}