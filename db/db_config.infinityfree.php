
<?php
// Database configuration for InfinityFree
define('DB_HOST', 'your_host.infinityfree.com'); // Usually something like sql.infinityfree.com
define('DB_NAME', 'your_database_name');         // Your database name
define('DB_USER', 'your_database_user');         // Your database username
define('DB_PASS', 'your_database_password');     // Your database password

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
try {
    $conn = new PDO($dsn, DB_USER, DB_PASS, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>