
<?php
session_start();
require_once('db/db_config.php');

$publicPages = ['home', 'login', 'register'];
$privatePages = ['dashboard', 'upload_nft', 'manage_nfts', 'transactions', 'purchase', 'edit_nft', 'delete_nft'];

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$isLoggedIn = isset($_SESSION['user_id']);

if (!in_array($page, $publicPages) && !$isLoggedIn) {
    header('Location: index.php?page=login');
    exit();
}

include 'includes/header.php';

if (in_array($page, $publicPages)) {
    if ($page === 'login' || $page === 'register') {
        include "auth/$page.php";
    } else {
        include "public/$page.php";
    }
} elseif (in_array($page, $privatePages)) {
    include "members/$page.php";
} else {
    echo "<div class='container mt-5'><h2>Page Not Found</h2></div>";
}

include 'includes/footer.php';
?>