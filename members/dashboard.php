
Here is the refactored code:
```php
<?php
require_once __DIR__ . '/../db/db_config.php';

$stmt = $conn->prepare("SELECT * FROM nfts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$user_nfts = $stmt->fetchAll();

$stmt = $conn->prepare("SELECT t.*, n.title as nft_title, u.username as seller_name FROM transactions t JOIN nfts n ON t.nft_id = n.nft_id JOIN users u ON n.user_id = u.user_id WHERE t.buyer_id = ? ORDER BY t.purchase_date DESC LIMIT 5");
$stmt->execute([$_SESSION['user_id']]);
$recent_transactions = $stmt->fetchAll();

?>
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p class="lead">Your NFT Dashboard</p>
        </div>
        <div class="col text-end">
            <a href="index.php?page=upload_nft" class="btn btn-primary">Upload New NFT</a>
        </div>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- User's NFTs -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Your NFTs</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($user_nfts)): ?>
                        <p>You haven't uploaded any NFTs yet.</p>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($user_nfts as $nft): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <img src="<?php echo htmlspecialchars($nft['image_url']); ?>" alt="<?php echo htmlspecialchars($nft['title']); ?>" />
                                        <div class="card-body">
                                            <h6 class="card-title"><?php echo htmlspecialchars($nft['title']); ?></h6>
                                            <p class="card-text"><?php echo htmlspecialchars($nft['description']); ?></p>
                                            <a href="<?php echo htmlspecialchars($nft['link']); ?>" class="btn btn-primary">View</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Transactions</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_transactions)): ?>
                        <p>No recent transactions.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($recent_transactions as $transaction): ?>
                                <li class="list-group-item">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($transaction['nft_title']); ?></h6>
                                    <p class="mb-1">
                                        <small>From: <?php echo htmlspecialchars($transaction['seller_name']); ?></small>
                                    </p>
                                    <small class="text-muted">
                                        $<?php echo number_format($transaction['price'], 2); ?> - 
                                        <?php echo date('M d, Y', strtotime($transaction['purchase_date'])); ?>
                                    </small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
```
The changes made to the code include:

* Using a prepared statement for both SQL queries, which helps to prevent SQL injection attacks.
* Moving the `htmlspecialchars` function to the places where it is needed, to make sure that special characters are properly escaped in the output.
* Using more descriptive variable names and using `$nft['image_url']` instead of `$transaction['link']`.
* Adding a new CSS class for the list-group, so that the styles can be customized separately from other elements on the page.