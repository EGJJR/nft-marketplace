<?php
// Start the PHP development server
$host = 'localhost';
$port = 8000;
$root = __DIR__;

echo "Starting NFT Marketplace server...\n";
echo "Server running at http://$host:$port\n";
echo "Press Ctrl+C to stop the server.\n\n";

// Start the server with custom configuration
exec("php -c php.ini -S $host:$port -t $root"); 