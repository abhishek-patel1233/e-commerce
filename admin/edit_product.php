<?php
session_start();
include("../db.php");
include("admin_navbar.php");

// Check Admin Session
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

if(!isset($_GET['id'])){
    echo "No product ID provided.";
    exit();
}

$product_id = $_GET['id'];

// Fetch existing product data
$sql = "SELECT * FROM products WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if(!$product){
    echo "Product not found.";
    exit();
}

// Handle form submission
if(isset($_POST['update_product'])){
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $category = trim($_POST['category']);
    $brand = trim($_POST['brand']);
    $description = trim($_POST['description']);

    $image_name = $product['image']; // default old image

    // Check if a new image is uploaded
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        // Delete old image
        if($product['image'] && file_exists("../images/".$product['image'])){
            unlink("../images/".$product['image']);
        }

        $image_name = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../images/$image_name");
    }

    // Update DB
    $sql = "UPDATE products SET name=?, price=?, image=?, category=?, brand=?, description=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssssi", $name, $price, $image_name, $category, $brand, $description, $product_id);

    if($stmt->execute()){
        $stmt->close();
        header("Location: products.php");
        exit();
    } else {
        echo "Error updating product: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Edit Product</h2>

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $product['name']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" name="price" step="0.01" class="form-control" value="<?php echo $product['price']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" value="<?php echo $product['category']; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <input type="text" name="brand" class="form-control" value="<?php echo $product['brand']; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4"><?php echo $product['description']; ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Product Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <?php if($product['image']): ?>
                                <img src="../images/<?php echo $product['image']; ?>" class="img-thumbnail mt-2" width="150">
                            <?php endif; ?>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="update_product" class="btn btn-primary btn-lg">Update Product</button>
                        </div>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="products.php" class="btn btn-secondary">Back to Products List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>  