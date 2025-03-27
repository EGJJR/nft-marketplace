
<?php
require_once __DIR__ . '/../db/db_config.php';

// Fetch all NFTs
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$orderBy = 'n.created_at DESC';

switch ($sort) {
    case 'price_low':
        $orderBy = 'n.price ASC';
        break;
    case 'price_high':
        $orderBy = 'n.price DESC';
        break;
    case 'oldest':
        $orderBy = 'n.created_at ASC';
        break;
}

$stmt = $conn->query("
    SELECT n.*, u.username as seller_name 
    FROM nfts n 
    JOIN users u ON n.user_id = u.user_id 
    ORDER BY $orderBy
");
$nfts = $stmt->fetchAll();

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search) {
    $stmt = $conn->prepare("
        SELECT n.*, u.username as seller_name 
        FROM nfts n 
        JOIN users u ON n.user_id = u.user_id 
        WHERE n.title LIKE ? OR n.description LIKE ? 
        ORDER BY $orderBy
    ");
    $searchTerm = "%$search%";
    $stmt->execute([$searchTerm, $searchTerm]);
    $nfts = $stmt->fetchAll();
}
?>

<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Welcome to NFT Marketplace</h1>
            <p class="lead">Discover and collect unique digital assets</p>
        </div>
        <div class="col-md-6">
            <form action="index.php" method="GET" class="d-flex">
                <?php if ($search): ?>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <?php endif; ?>
                <label for="sort-by" class="me-2">Sort by:</label>
                <select name="sort-by" id="sort-by" class="form-select">
                    <option value="newest"<?php if ($sort == 'newest'): ?> selected<?php endif; ?>>Newest</option>
                    <option value="oldest"<?php if ($sort == 'oldest'): ?> selected<?php endif; ?>>Oldest</option>
                    <option value="price_low"<?php if ($sort == 'price_low'): ?> selected<?php endif; ?>>Price: Low to High</option>
                    <option value="price_high"<?php if ($sort == 'price_high'): ?> selected<?php endif; ?>>Price: High to Low</option>
                </select>
                <button type="submit" class="btn btn-primary">Sort</button>
            </form>
        </div>
    </div>

    <?php if (empty($nfts)): ?>
        <div class="col">
            <div class="alert alert-info">
                <?php echo $search ? 'No NFTs found matching your search.' : 'No NFTs available yet.'; ?>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($nfts as $nft): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?php echo htmlspecialchars($nft['image_url']); ?>" alt="<?php echo htmlspecialchars($nft['title']); ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($nft['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($nft['description']); ?></p>
                        <p class="card-text">
                            <small class="text-muted">By: <?php echo htmlspecialchars($nft['seller_name']); ?></small>
                        </p>
                        <p class="card-text">
                            <strong>Price: $<?php echo number_format($nft['price'], 2); ?></strong>
                        </p>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="index.php?page=purchase&nft_id=<?php echo $nft['nft_id']; ?>" class="btn btn-primary">Purchase</a>
                        <?php else: ?>
                            <a href="index.php?page=login" class="btn btn-primary">Login to Purchase</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>