```
Here is the refactored code, following best practices and PSR standards:

<?php
require_once __DIR__ . '/../db/db_config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    
    // Validate input
    if (empty($title) || empty($description) || $price <= 0) {
        $error = "All fields are required and price must be greater than 0";
    } else {
        // Handle image upload
        $targetDir = __DIR__ . '/../assets/images/nfts';
        
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $imageFilePath = $targetDir . '/' . basename($_FILES['image']['name']);
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imageFilePath)) {
            // Database connection and query execution
            try {
                $dbh = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');
                $stmt = $dbh->prepare("INSERT INTO nfts (title, description, image_url, price) VALUES (:title, :description, :image_url, :price)");
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':image_url', $imageFilePath);
                $stmt->bindParam(':price', $price);
                
                if ($stmt->execute()) {
                    $success = "NFT uploaded successfully!";
                } else {
                    $error = "Failed to upload NFT. Please try again.";
                }
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            } finally {
                $dbh = null;
            }
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Upload New NFT</h3>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="price" class="form-label">Price ($)</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">NFT Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                            <div class="form-text">Maximum file size: 10MB. Allowed formats: JPG, JPEG, PNG, GIF</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Upload NFT</button>
                            <a href="index.php?page=dashboard" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>