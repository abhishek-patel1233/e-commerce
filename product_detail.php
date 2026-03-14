<?php
include("db.php");

$id = $_GET['id'] ?? 0;

// Fetch product
$sql = "SELECT * FROM products WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if(!$product){
    die("Product not found.");
}

// Fetch all images for this product
$images = [];
$img_result = $conn->query("SELECT image_name FROM product_images WHERE product_id = $id");
while($row = $img_result->fetch_assoc()){
    $images[] = $row['image_name'];
}

// If no additional images, fallback to main product image
if(empty($images) && !empty($product['image'])){
    $images[] = $product['image'];
}

// Fetch similar products
$category = $product['category'];
$similar_result = $conn->query("SELECT * FROM products WHERE category='$category' AND id != $id LIMIT 8");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['name']; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">

    <div class="row g-4">

        <!-- Product Images Carousel -->
        <div class="col-md-5">
            <?php if(count($images) > 0): ?>
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach($images as $index => $img): ?>
                        <div class="carousel-item <?php echo $index==0?'active':''; ?>">
                            <img src="images/<?php echo $img; ?>" class="d-block w-100 rounded" alt="<?php echo $product['name']; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if(count($images) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Product Info -->
        <div class="col-md-7">
            <h2 class="fw-bold"><?php echo $product['name']; ?></h2>
            <h4 class="text-danger my-3">₹<?php echo $product['price']; ?></h4>
            <p><?php echo $product['description']; ?></p>

            <form method="POST" action="cart.php" class="d-inline me-2">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button class="btn btn-warning btn-lg" type="submit" name="add_to_cart">Add to Cart</button>
            </form>

            <form method="POST" action="checkout.php?id=<?php echo $product['id']; ?>" class="d-inline">
                <button class="btn btn-danger btn-lg" type="submit" name="buy_now">Buy Now</button>
            </form>
        </div>

    </div>

    <!-- Similar Products -->
    <div class="mt-5">
        <h3 class="mb-4">Similar Products</h3>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php
            if(mysqli_num_rows($similar_result) > 0){
                while($row = $similar_result->fetch_assoc()){
                    echo '<div class="col">';
                    echo '<div class="card h-100">';
                    echo '<a href="product_detail.php?id='.$row['id'].'">';
                    echo '<img src="images/'.$row['image'].'" class="card-img-top" alt="'.$row['name'].'">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">'.$row['name'].'</h5>';
                    echo '<p class="card-text text-danger fw-bold">₹'.$row['price'].'</p>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center">No similar products found.</p>';
            }
            ?>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>