<?php
require_once __DIR__ . '/../db/db_config.php';

// Fetch all NFTs with their creator's username
$stmt = $conn->query("
    SELECT n.*, u.username as creator_name 
    FROM nfts n 
    JOIN users u ON n.user_id = u.user_id 
    ORDER BY n.created_at DESC
");
$nfts = $stmt->fetchAll();
?>

<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Welcome to NFT Marketplace</h1>
            <p class="lead">Discover and collect unique digital assets</p>
        </div>
    </div>

    <div class="row">
        <?php foreach ($nfts as $nft): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?php echo htmlspecialchars($nft['image_url']); ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($nft['title']); ?>"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($nft['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars(substr($nft['description'], 0, 100)) . '...'; ?></p>
                        <p class="card-text">
                            <small class="text-muted">
                                Created by: <?php echo htmlspecialchars($nft['creator_name']); ?>
                            </small>
                        </p>
                        <p class="card-text">
                            <strong>Price: $<?php echo number_format($nft['price'], 2); ?></strong>
                        </p>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="index.php?page=purchase&nft_id=<?php echo $nft['nft_id']; ?>" 
                               class="btn btn-primary">Purchase</a>
                        <?php else: ?>
                            <a href="index.php?page=login" class="btn btn-primary">Login to Purchase</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div> 