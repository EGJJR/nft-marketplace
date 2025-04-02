
<?php
declare(strict_types=1);

use Symfony\Component\HttpServer\Server;

$host = 'localhost';
$port = 8000;
$root = __DIR__;

echo "Starting NFT Marketplace server...\n";
echo "Server running at http://$host:$port\n";
echo "Press Ctrl+C to stop the server.\n\n";

(new Server())->listen($host, $port, $root);