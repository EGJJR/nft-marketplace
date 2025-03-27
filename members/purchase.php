
<?php
require_once __DIR__ . '/../db/db_config.php';

$error = '';
$success = '';
$nft = null;

// Get NFT ID from URL
$nftId = isset($_GET['nft_id']) ? intval($_GET['nft_id']) : 0;

// Fetch NFT details
$stmt = $conn->prepare("
    SELECT n.*, u.username as seller_name 
    FROM nfts n 
    JOIN users u ON n.user_id = u.user_id 
    WHERE n.nft_id = ?
");
$stmt->execute([$nftId]);
$nft = $stmt->fetch();

if (!$nft) {
    header('Location: index.php');
    exit();
}

// Check if user is trying to buy their own NFT
if ($nft['user_id'] === $_SESSION['user_id']) {
    $error = "You cannot purchase your own NFT";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    try {
        // Begin transaction
        $conn->beginTransaction();
        
        // Insert transaction record
        $stmt = $conn->prepare("
            INSERT INTO transactions (buyer_id, nft_id, price) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $nftId, $nft['price']]);
        
        // Update NFT ownership
        $stmt = $conn->prepare("
            UPDATE nfts 
            SET user_id = ? 
            WHERE nft_id = ?
        ");
        $stmt->execute([$_SESSION['user_id'], $nftId]);
        
        // Commit transaction
        $conn->commit();
        $success = "NFT purchased successfully!";
        
        // Refresh NFT data
        $stmt = $conn->prepare("
            SELECT n.*, u.username as seller_name 
            FROM nfts n 
            JOIN users u ON n.user_id = u.user_id 
            WHERE n.nft_id = ?
        ");
        $stmt->execute([$nftId]);
        $nft = $stmt->fetch();
        
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollBack();
        $error = "Transaction failed. Please try again.";
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Purchase NFT</h3>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <img src="<?php echo htmlspecialchars($nft['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($nft['title']); ?>" 
                                 class="img-fluid">
                        </div>
                        <div class="col-md-6">
                            <h3><?php echo $nft['title']; ?></h3>
                            <p><?php echo $nft['description']; ?></p>
                            <h5>Price: <?php echo $nft['price']; ?> ETH</h5>
                            
                            <?php if (empty($error) && !$success): ?>
                                <form method="POST" action="">
                                    <div class="d-grid mt-3">
                                        <button type="submit" class="btn btn-primary">Confirm Purchase</button>
                                    </div>
                                </form>
                            <?php endif; ?>
                            
                            <div class="mt-3 d-grid">
                                <a href="index.php" class="btn btn-secondary">Back to Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>