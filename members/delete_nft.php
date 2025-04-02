
Here is the refactored code following best practices and PSR standards:

<?php

require_once __DIR__ . '/../db/db_config.php';

$nftId = (isset($_GET['nft_id'])) ? intval($_GET['nft_id']) : 0;

if ($nftId) {
    // Verify ownership and get NFT details
    $stmt = $conn->prepare("SELECT * FROM nfts WHERE nft_id = ?");
    $stmt->execute([$nftId]);
    $nft = $stmt->fetch();
    
    if ($nft) {
        // Check if the current user owns the NFT
        if ($nft['user_id'] === $_SESSION['user_id']) {
            // Delete the image file
            $imagePath = __DIR__ . "/../" . $nft['image_url'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            // Update transactions to show the NFT as deleted
            $stmt = $conn->prepare("UPDATE transactions SET nft_id = NULL WHERE nft_id = ?");
            $stmt->execute([$nftId]);
            
            // Now delete the NFT
            $stmt = $conn->prepare("DELETE FROM nfts WHERE nft_id = ?");
            $stmt->execute([$nftId]);
        } else {
            // If user doesn't own the NFT, redirect with error message
            $_SESSION['error'] = "You don't have permission to delete this NFT.";
            header('Location: index.php?page=dashboard');
            exit();
        }
    }
}

// Redirect back to dashboard
header('Location: index.php?page=dashboard');
exit();
?>