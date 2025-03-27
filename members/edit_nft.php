<?php
require_once __DIR__ . '/../db/db_config.php';

$error = '';
$success = '';
$nft = null;

// Get NFT ID from URL
$nft_id = isset($_GET['nft_id']) ? intval($_GET['nft_id']) : 0;

// Fetch NFT details
$stmt = $conn->prepare("SELECT * FROM nfts WHERE nft_id = ? AND user_id = ?");
$stmt->execute([$nft_id, $_SESSION['user_id']]);
$nft = $stmt->fetch();

if (!$nft) {
    header('Location: index.php?page=dashboard');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    
    // Validate input
    if (empty($title) || empty($description) || $price <= 0) {
        $error = "All fields are required and price must be greater than 0";
    } else {
        // Handle image upload if new image is provided
        $image_url = $nft['image_url'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../assets/images/nfts/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            // Validate file type
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($file_extension, $allowed_types)) {
                $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            } else if ($_FILES["image"]["size"] > 5000000) { // 5MB limit
                $error = "Sorry, your file is too large. Maximum size is 5MB.";
            } else if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Delete old image
                $old_image_path = "../" . $nft['image_url'];
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
                
                $image_url = "assets/images/nfts/" . $new_filename;
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
        
        if (empty($error)) {
            // Update NFT in database
            $stmt = $conn->prepare("
                UPDATE nfts 
                SET title = ?, description = ?, image_url = ?, price = ? 
                WHERE nft_id = ? AND user_id = ?
            ");
            
            if ($stmt->execute([$title, $description, $image_url, $price, $nft_id, $_SESSION['user_id']])) {
                $success = "NFT updated successfully!";
                // Refresh NFT data
                $stmt = $conn->prepare("SELECT * FROM nfts WHERE nft_id = ? AND user_id = ?");
                $stmt->execute([$nft_id, $_SESSION['user_id']]);
                $nft = $stmt->fetch();
            } else {
                $error = "Failed to update NFT. Please try again.";
            }
        }
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Edit NFT</h3>
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
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?php echo htmlspecialchars($nft['title']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" 
                                      rows="4" required><?php echo htmlspecialchars($nft['description']); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="price" class="form-label">Price ($)</label>
                            <input type="number" class="form-control" id="price" name="price" 
                                   step="0.01" min="0" value="<?php echo $nft['price']; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div>
                                <img src="<?php echo htmlspecialchars($nft['image_url']); ?>" 
                                     alt="Current NFT Image" 
                                     class="img-thumbnail" 
                                     style="max-height: 200px;">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">New Image (Optional)</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="form-text">Maximum file size: 5MB. Allowed formats: JPG, JPEG, PNG, GIF</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Update NFT</button>
                            <a href="index.php?page=dashboard" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 