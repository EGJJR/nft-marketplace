<?php
session_start();
require_once 'db/db_config.php';

// Define allowed pages for both public and private sections
$public_pages = ['home', 'login', 'register'];
$private_pages = ['dashboard', 'upload_nft', 'manage_nfts', 'transactions', 'purchase', 'edit_nft', 'delete_nft'];

// Get the requested page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);

// Handle page access logic
if (in_array($page, $private_pages) && !$is_logged_in) {
    header('Location: index.php?page=login');
    exit();
}

// Include header
include 'includes/header.php';

// Load the appropriate page
if (in_array($page, $public_pages)) {
    if ($page === 'login' || $page === 'register') {
        include "auth/$page.php";
    } else {
        include "public/$page.php";
    }
} elseif (in_array($page, $private_pages)) {
    include "members/$page.php";
} else {
    echo "<div class='container mt-5'><h2>Page Not Found</h2></div>";
}

// Include footer
include 'includes/footer.php';
?> 