
The code has been refactored to follow best practices and PSR standards. Here's the refactored code:

<?php

require_once __DIR__ . '/../db/db_config.php';

$error = '';
$success = '';
$nft = null;

// Get NFT ID from URL
$nftId = isset($_GET['nft_id']) ? intval($_GET['nft_id']) : 0;

// Fetch NFT details
$stmt = $conn->prepare("SELECT * FROM nfts WHERE nft_id = ? AND user_id = ?");
$stmt->execute([$nftId, $_SESSION['user_id']]);
$nft = $stmt->fetch();

if (!$nft) {
    header('Location: index.php?page=dashboard');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);

    // Validate input
    if (empty($title)) {
        $error .= 'Please enter a valid title.';
    }

    if (empty($description)) {
        $error .= 'Please enter a valid description.';
    }

    if (!is_numeric($price) || $price < 0) {
        $error .= 'Please enter a valid price.';
    }

    if (empty($error)) {
        // Update NFT details
        $stmt = $conn->prepare("UPDATE nfts SET title = ?, description = ?, price = ? WHERE nft_id = ?");
        $stmt->execute([$title, $description, $price, $nftId]);

        if ($stmt->rowCount() > 0) {
            $success = 'NFT updated successfully.';
        } else {
            $error .= 'Failed to update NFT details.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit NFT</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h3 class="text-center">Edit NFT</h3>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($nft['title']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($nft['description']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price ($)</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="<?php echo $nft['price']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div>
                            <img src="<?php echo htmlspecialchars($nft['image_url']); ?>" alt="Current NFT Image" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">New Image (Optional)</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <div class="form-text">Maximum file size: 5MB. Allowed formats: JPG, JPEG, PNG, GIF</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update NFT</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>