
<?php
// Database configuration for InfinityFree
$dsn = sprintf(
    'mysql:host=%s;dbname=%s',
    DB_HOST,
    DB_NAME
);

try {
    $conn = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}
?>