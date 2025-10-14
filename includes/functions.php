<?php
/**
 * Helper functions for the job application system
 */

/**
 * Sanitize input data
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate email format
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate Norwegian phone number
 */
function validate_phone($phone) {
    $cleaned = preg_replace('/[\s\-]/', '', $phone);
    return preg_match('/^[49]\d{7}$/', $cleaned);
}

/**
 * Generate unique ID
 */
function generate_id($prefix = '') {
    return $prefix . uniqid() . '_' . time();
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if user has specific role
 */
function has_role($role) {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === $role;
}

/**
 * Require login - redirect if not logged in
 */
function require_login() {
    if (!is_logged_in()) {
        header('Location: ' . APP_URL . '/auth/login.php');
        exit;
    }
}

/**
 * Require specific role
 */
function require_role($role) {
    require_login();
    if (!has_role($role)) {
        header('Location: ' . APP_URL . '/index.php');
        exit;
    }
}

/**
 * Format date for display
 */
function format_date($date, $format = 'd.m.Y') {
    return date($format, strtotime($date));
}

/**
 * Format date with time
 */
function format_datetime($datetime, $format = 'd.m.Y H:i') {
    return date($format, strtotime($datetime));
}

/**
 * Generate password hash
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Redirect with message
 */
function redirect($url, $message = '', $type = 'info') {
    if (!empty($message)) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    header('Location: ' . $url);
    exit;
}

/**
 * Display flash message
 */
function display_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        
        // Bootstrap alert classes
        $alert_class = 'alert-info';
        switch ($type) {
            case 'success':
                $alert_class = 'alert-success';
                break;
            case 'error':
                $alert_class = 'alert-danger';
                break;
            case 'warning':
                $alert_class = 'alert-warning';
                break;
        }
        
        echo '<div class="alert ' . $alert_class . ' alert-dismissible fade show" role="alert">';
        echo htmlspecialchars($message);
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        echo '</div>';
        
        // Clear message
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
    }
}

/**
 * Validate required fields
 */
function validate_required_fields($fields, $data) {
    $errors = [];
    foreach ($fields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            $errors[] = ucfirst($field) . ' er påkrevd';
        }
    }
    return $errors;
}

/**
 * Get user by ID
 */
function get_user_by_id($user_id) {
    global $users;
    return isset($users[$user_id]) ? $users[$user_id] : null;
}

/**
 * Get user by email
 */
function get_user_by_email($email) {
    global $users;
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null;
}

/**
 * Save user data
 */
function save_users_data() {
    global $users;
    $data = "<?php\n// User data array\n\$users = " . var_export($users, true) . ";\n?>";
    return file_put_contents(__DIR__ . '/../data/users.php', $data);
}

/**
 * Save jobs data
 */
function save_jobs_data() {
    global $jobs;
    $data = "<?php\n// Jobs data array\n\$jobs = " . var_export($jobs, true) . ";\n?>";
    return file_put_contents(__DIR__ . '/../data/jobs.php', $data);
}

/**
 * Save applications data
 */
function save_applications_data() {
    global $applications;
    $data = "<?php\n// Applications data array\n\$applications = " . var_export($applications, true) . ";\n?>";
    return file_put_contents(__DIR__ . '/../data/applications.php', $data);
}

/**
 * Debug function
 */
function debug($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

/**
 * Check file upload
 */
function handle_file_upload($file, $allowed_types = ['pdf', 'doc', 'docx']) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_extension, $allowed_types)) {
        return false;
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return false;
    }
    
    $upload_dir = __DIR__ . '/../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $filename = uniqid() . '.' . $file_extension;
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filename;
    }
    
    return false;
}
?>