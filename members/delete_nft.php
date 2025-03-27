<?php
require_once __DIR__ . '/../db/db_config.php';

$nft_id = isset($_GET['nft_id']) ? intval($_GET['nft_id']) : 0;

// Verify ownership and get NFT details
$stmt = $conn->prepare("SELECT * FROM nfts WHERE nft_id = ?");
$stmt->execute([$nft_id]);
$nft = $stmt->fetch();

if ($nft) {
    // Check if the current user owns the NFT
    if ($nft['user_id'] === $_SESSION['user_id']) {
        // Delete the image file
        $image_path = __DIR__ . "/../" . $nft['image_url'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        
        // Update transactions to show the NFT as deleted
        $stmt = $conn->prepare("
            UPDATE transactions 
            SET nft_id = NULL 
            WHERE nft_id = ?
        ");
        $stmt->execute([$nft_id]);
        
        // Now delete the NFT
        $stmt = $conn->prepare("DELETE FROM nfts WHERE nft_id = ?");
        $stmt->execute([$nft_id]);
    } else {
        // If user doesn't own the NFT, redirect with error message
        $_SESSION['error'] = "You don't have permission to delete this NFT.";
        header('Location: index.php?page=dashboard');
        exit();
    }
}

// Redirect back to dashboard
header('Location: index.php?page=dashboard');
exit();
?> 