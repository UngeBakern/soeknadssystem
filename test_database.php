<?php
/**
 * Test database connection
 */

// Last inn autoload (som inkluderer config.php og alle klasser)
require_once 'includes/autoload.php';

echo "<h1>Database Connection Test</h1>";

try {
    // Opprett Database-instans
    $database = new Database();
    echo "<p>✅ Database class loaded successfully</p>";
    
    // Test connection
    $pdo = $database->connect();
    
    if ($pdo) {
        echo "<p style='color: green;'>✅ Database connection successful!</p>";
        
        // Test basic query
        $stmt = $pdo->query("SELECT DATABASE() as current_db");
        $result = $stmt->fetch();
        
        echo "<p><strong>Connected to database:</strong> " . $result['current_db'] . "</p>";
        
        // Test if jobs table exists
        $stmt = $pdo->query("SHOW TABLES LIKE 'jobs'");
        $tableExists = $stmt->rowCount() > 0;
        
        if ($tableExists) {
            echo "<p style='color: green;'>✅ 'jobs' table exists</p>";
            
            // Count rows in jobs table
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM jobs");
            $result = $stmt->fetch();
            echo "<p><strong>Number of jobs in database:</strong> " . $result['count'] . "</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ 'jobs' table does not exist yet</p>";
            echo "<p>You need to create the jobs table in phpMyAdmin first.</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Database connection failed!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>Current Configuration:</h2>";
echo "<p><strong>Host:</strong> " . DB_HOST . "</p>";
echo "<p><strong>Database:</strong> " . DB_NAME . "</p>";
echo "<p><strong>User:</strong> " . DB_USER . "</p>";
echo "<p><strong>Password:</strong> " . (empty(DB_PASS) ? "(empty)" : "(set)") . "</p>";
?>
