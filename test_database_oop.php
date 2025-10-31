<?php
/**
 * OOP Database Test - Simple Version
 */

require_once 'includes/autoload.php';

echo "<h1>Database Test - OOP Version</h1>";

try {
    // Create tester instance and run tests
    $tester = new DatabaseTester();
    $results = $tester->runAllTests();
    
    // Display results as simple HTML
    foreach ($results as $result) {
        echo "<p>" . htmlspecialchars($result['message']) . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Critical Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>Usage Examples:</h2>";
echo "<pre>";
echo "// Method 1: Full control\n";
echo "\$tester = new DatabaseTester();\n";
echo "\$results = \$tester->runAllTests();\n\n";
echo "// Method 2: Quick test\n";
echo "DatabaseTester::quickTest();\n";
echo "</pre>";
?>
