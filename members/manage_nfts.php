<?php
require_once __DIR__ . '/../db/db_config.php';

// Fetch user's NFTs
$stmt = $conn->prepare("
    SELECT * FROM nfts 
    WHERE user_id = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$nfts = $stmt->fetchAll();
?>

<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Manage Your NFTs</h1>
            <p class="lead">View and manage your NFT collection</p>
        </div>
        <div class="col text-end">
            <a href="index.php?page=upload_nft" class="btn btn-primary">Upload New NFT</a>
        </div>
    </div>

    <?php if (empty($nfts)): ?>
        <div class="alert alert-info">
            You haven't uploaded any NFTs yet. 
            <a href="index.php?page=upload_nft" class="alert-link">Upload your first NFT</a>!
        </div>
    <?php else: ?>
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
                                <strong>Price: $<?php echo number_format($nft['price'], 2); ?></strong>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">
                                    Created: <?php echo date('M d, Y', strtotime($nft['created_at'])); ?>
                                </small>
                            </p>
                            <div class="btn-group w-100">
                                <a href="index.php?page=edit_nft&nft_id=<?php echo $nft['nft_id']; ?>" 
                                   class="btn btn-outline-primary">Edit</a>
                                <a href="index.php?page=delete_nft&nft_id=<?php echo $nft['nft_id']; ?>" 
                                   class="btn btn-outline-danger"
                                   onclick="return confirm('Are you sure you want to delete this NFT?')">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div> 