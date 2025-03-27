
Refactored code:
```php
<?php
// Fetch user's NFTs and recent transactions
$stmt = $conn->prepare("
    SELECT * FROM nfts 
    WHERE user_id = :user_id 
    ORDER BY created_at DESC
");
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$nfts = $stmt->fetchAll();

$stmt = $conn->prepare("
    SELECT t.*, n.title as nft_title, u.username as seller_name 
    FROM transactions t 
    JOIN nfts n ON t.nft_id = n.nft_id 
    JOIN users u ON n.user_id = u.user_id 
    WHERE t.buyer_id = :user_id 
    ORDER BY t.purchase_date DESC 
    LIMIT 5
");
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$transactions = $stmt->fetchAll();

// Render HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NFT Dashboard</title>
    <!-- Load Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
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
            <div class="col-md-8">
                <!-- NFTs -->
                <h2>Your NFTs</h2>
                <ul class="list-group">
                    <?php foreach ($nfts as $nft): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($nft['title']); ?>
                            <span class="badge bg-primary rounded-pill"><?php echo date('M d, Y', strtotime($nft['created_at'])); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-md-4">
                <!-- Recent Transactions -->
                <h2>Recent Transactions</h2>
                <ul class="list-group">
                    <?php foreach ($transactions as $transaction): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($transaction['nft_title']); ?>
                            <span class="badge bg-primary rounded-pill"><?php echo date('M d, Y', strtotime($transaction['purchase_date'])); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
```
This code uses Bootstrap CSS to style the HTML elements. It also adds a new column for displaying recent transactions and removes unnecessary HTML tags. The code is more readable and maintainable, and it's easy to add or remove columns as needed.