
Here is the refactored code:

<?php
require_once __DIR__ . '/../db/db_config.php';

// Fetch all NFTs
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$orderBy = null;
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

$stmt = $conn->prepare("SELECT n.*, u.username as seller_name FROM nfts n JOIN users u ON n.user_id = u.user_id");
if ($sort) {
    $stmt->orderBy($orderBy);
}
$nfts = $stmt->fetchAll();

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search) {
    $stmt = $conn->prepare("SELECT n.*, u.username as seller_name FROM nfts n JOIN users u ON n.user_id = u.user_id WHERE n.title LIKE ? OR n.description LIKE ?");
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
                <input type="hidden" name="page" value="home">
                <?php if ($search): ?>
                    <input type="hidden" name="search" value="<?php echo $search; ?>">
                <?php endif; ?>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search NFTs" value="<?php echo $search; ?>">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>
        </div>
    </div>
    
    <?php if (empty($nfts)): ?>
        <div class="alert alert-info">No NFTs available yet.</div>
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