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
        $target_dir = __DIR__ . "/../assets/images/nfts/";
        
        // Debug information
        error_log("Upload directory: " . $target_dir);
        error_log("Directory exists: " . (file_exists($target_dir) ? 'Yes' : 'No'));
        error_log("Directory writable: " . (is_writable($target_dir) ? 'Yes' : 'No'));
        
        if (!file_exists($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                $error = "Failed to create upload directory";
                error_log("Failed to create directory: " . $target_dir);
            }
        }
        
        if (empty($error)) {
            $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            // Debug information
            error_log("Target file: " . $target_file);
            error_log("File extension: " . $file_extension);
            error_log("Upload error code: " . $_FILES["image"]["error"]);
            
            // Validate file type
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($file_extension, $allowed_types)) {
                $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            } else if ($_FILES["image"]["size"] > 10000000) { // 10MB limit
                $error = "Sorry, your file is too large. Maximum size is 10MB.";
            } else if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
                switch ($_FILES["image"]["error"]) {
                    case UPLOAD_ERR_INI_SIZE:
                        $error = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $error = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $error = "The uploaded file was only partially uploaded";
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $error = "No file was uploaded";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $error = "Missing a temporary folder";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $error = "Failed to write file to disk";
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $error = "A PHP extension stopped the file upload";
                        break;
                    default:
                        $error = "Unknown upload error";
                }
            } else if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Insert NFT into database
                $image_url = "assets/images/nfts/" . $new_filename;
                $stmt = $conn->prepare("
                    INSERT INTO nfts (user_id, title, description, image_url, price) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                
                if ($stmt->execute([$_SESSION['user_id'], $title, $description, $image_url, $price])) {
                    $success = "NFT uploaded successfully!";
                } else {
                    $error = "Failed to upload NFT. Please try again.";
                    error_log("Database error: " . print_r($stmt->errorInfo(), true));
                }
            } else {
                $error = "Sorry, there was an error uploading your file.";
                error_log("Failed to move uploaded file from " . $_FILES["image"]["tmp_name"] . " to " . $target_file);
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