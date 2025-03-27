```
<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'nft_marketplace');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
```