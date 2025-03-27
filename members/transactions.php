<?php
require_once __DIR__ . '/../db/db_config.php';

// Fetch user's transactions with NFT and seller details
$stmt = $conn->prepare("
    SELECT t.*, 
           COALESCE(n.title, 'Deleted NFT') as nft_title, 
           COALESCE(n.image_url, 'assets/images/deleted-nft.jpg') as image_url, 
           u.username as seller_name 
    FROM transactions t 
    LEFT JOIN nfts n ON t.nft_id = n.nft_id 
    JOIN users u ON t.buyer_id = u.user_id 
    WHERE t.buyer_id = ? 
    ORDER BY t.purchase_date DESC
");
$stmt->execute([$_SESSION['user_id']]);
$transactions = $stmt->fetchAll();
?>

<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Transaction History</h1>
            <p class="lead">View your NFT purchase history</p>
        </div>
    </div>

    <?php if (empty($transactions)): ?>
        <div class="alert alert-info">
            You haven't made any purchases yet. 
            <a href="index.php" class="alert-link">Browse the marketplace</a> to find NFTs you like!
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($transactions as $transaction): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="<?php echo htmlspecialchars($transaction['image_url']); ?>" 
                                     class="img-fluid rounded-start" 
                                     alt="<?php echo htmlspecialchars($transaction['nft_title']); ?>"
                                     style="height: 100%; object-fit: cover;">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($transaction['nft_title']); ?></h5>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            From: <?php echo htmlspecialchars($transaction['seller_name']); ?>
                                        </small>
                                    </p>
                                    <p class="card-text">
                                        <strong>Price: $<?php echo number_format($transaction['price'], 2); ?></strong>
                                    </p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            Purchased: <?php echo date('M d, Y', strtotime($transaction['purchase_date'])); ?>
                                        </small>
                                    </p>
                                    <?php if ($transaction['nft_id'] === null): ?>
                                        <span class="badge bg-warning">NFT Deleted</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div> 