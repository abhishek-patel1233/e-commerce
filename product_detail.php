<?php
session_start();
include("db.php");
include("navbar.php");

$id = $_GET['id'] ?? 0;

/* PRODUCT */
$stmt=$conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$product=$stmt->get_result()->fetch_assoc();

if(!$product){
die("Product not found");
}

/* DISCOUNT */
$original_price = $product['price'];
$discount_price = $original_price - ($original_price * 15 / 100);

/* PRODUCT IMAGES */
$images=[];

if(!empty($product['image'])){
$images[]=$product['image'];
}

$stmt=$conn->prepare("SELECT image_name FROM product_images WHERE product_id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$res=$stmt->get_result();

while($row=$res->fetch_assoc()){
$images[]=$row['image_name'];
}

/* SIMILAR PRODUCTS */
$stmt=$conn->prepare("SELECT * FROM products WHERE category=? AND id!=? LIMIT 8");
$stmt->bind_param("si",$product['category'],$id);
$stmt->execute();
$similar_result=$stmt->get_result();

/* REVIEW SUBMIT */
if(isset($_POST['submit_review'])){
    if(!isset($_SESSION['user_id'])){
        echo "<script>alert('Login required');</script>";
    } else {
        $pid = $id;
        $uid = $_SESSION['user_id'];
        $rating = $_POST['rating'];
        $review = $_POST['review'];

        if($rating < 1 || $rating > 5){
            die("Invalid rating");
        }

        $stmt = $conn->prepare("INSERT INTO reviews (product_id,user_id,rating,review) VALUES (?,?,?,?)");
        $stmt->bind_param("iiis",$pid,$uid,$rating,$review);
        $stmt->execute();

        echo "<script>alert('Review Added');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $product['name']; ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{background:#f3f3f3;font-family:Arial;}

.gallery{display:flex;gap:15px;}
.thumbs{display:flex;flex-direction:column;gap:10px;}
.thumbs img{width:70px;height:70px;object-fit:cover;border:1px solid #ddd;cursor:pointer;border-radius:5px;background:white;}
.thumbs img:hover{border:2px solid #ff9900;}

.main-img{display:flex;justify-content:center;align-items:center;background:white;border-radius:8px;width:420px;height:420px;}
.main-img img{max-width:100%;max-height:100%;object-fit:contain;}

.product-box{background:white;padding:25px;border-radius:8px;box-shadow:0 3px 12px rgba(0,0,0,0.08);}

.price{font-size:28px;color:#B12704;font-weight:bold;}

.product-card{border:none;box-shadow:0 3px 10px rgba(0,0,0,0.08);transition:0.3s;}
.product-card:hover{transform:translateY(-5px);box-shadow:0 8px 20px rgba(0,0,0,0.15);}

.star{color:gold;}
.review-box{border-bottom:1px solid #ddd;padding:10px 0;}

</style>
</head>

<body>

<div class="container my-5">
    
<div class="row">

<!-- IMAGES -->
<div class="col-md-6">
<div class="gallery">

<div class="thumbs">
<?php foreach($images as $img): ?>
<img src="images/<?php echo $img; ?>" onclick="changeImage(this)">
<?php endforeach; ?>
</div>

<div class="main-img">
<img id="mainImage" src="images/<?php echo $images[0]; ?>">
</div>

</div>
</div>

<!-- PRODUCT INFO -->
<div class="col-md-6">
<div class="product-box">

<h3><?php echo $product['name']; ?></h3>

<!-- 🔥 PRICE WITH DISCOUNT -->
<div class="price my-3">
<span style="text-decoration:line-through; color:gray; font-size:16px;">
₹<?php echo $original_price; ?>
</span><br>

₹<?php echo round($discount_price); ?>
<span style="color:red; font-size:14px;">(15% OFF)</span>
</div>

<p><?php echo $product['description']; ?></p>

<form method="POST" action="cart.php">
<input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
<button class="btn btn-warning w-100">Add to Cart</button>
</form>

<br>

<form method="POST" action="checkout.php">
<input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
<button class="btn btn-danger w-100">Buy Now</button>
</form>

</div>
</div>

</div>
</div>

<!-- RELATED PRODUCTS -->
<div class="container mt-5">
<h4>Related Products</h4>

<div class="row">
<?php while($row=$similar_result->fetch_assoc()){ 
$op=$row['price'];
$dp=$op - ($op*15/100);
?>

<div class="col-md-3">
<div class="card product-card p-2">

<a href="product_detail.php?id=<?php echo $row['id']; ?>" style="text-decoration:none;color:black;">
<img src="images/<?php echo $row['image']; ?>" class="card-img-top">

<h6><?php echo $row['name']; ?></h6>

<p>
<span style="text-decoration:line-through;color:gray;font-size:13px;">₹<?php echo $op; ?></span><br>
<span style="color:red;font-weight:bold;">₹<?php echo round($dp); ?></span>
<span style="color:red;font-size:12px;">(15% OFF)</span>
</p>

</a>

</div>
</div>

<?php } ?>
</div>
</div>

<!-- REVIEWS -->
<div class="container mt-5 bg-white p-4">

<h3>Rating & Reviews</h3>

<?php
$res = $conn->query("SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id=$id");
$row = $res->fetch_assoc();
$avg = round($row['avg_rating'],1);
?>

<p><b><?php echo $avg ? $avg : "0"; ?> ⭐</b></p>

<h4>Write Review</h4>

<form method="POST">
<select name="rating" required>
<option value="">Select</option>
<option value="5">⭐⭐⭐⭐⭐</option>
<option value="4">⭐⭐⭐⭐</option>
<option value="3">⭐⭐⭐</option>
<option value="2">⭐⭐</option>
<option value="1">⭐</option>
</select>

<br><br>

<textarea name="review" required style="width:100%;height:80px;"></textarea>

<br><br>

<button name="submit_review">Submit</button>
</form>

<hr>

<h4>Customer Reviews</h4>

<?php
$result = $conn->query("SELECT * FROM reviews WHERE product_id=$id ORDER BY id DESC");

while($r = $result->fetch_assoc()){
?>

<div class="review-box">

 <?php
for($i=1;$i<=5;$i++){
if($i <= $r['rating']){
    echo "<span style='color:gold;font-size:18px;'>★</span>";
}else{
    echo "<span style='color:#ccc;font-size:18px;'>☆</span>";
}
}
?> 


<p><?php echo $r['review']; ?></p>

<small><?php echo $r['created_at']; ?></small>

</div>

<?php } ?>

</div>

<script>
function changeImage(el){
document.getElementById("mainImage").src = el.src;
}
</script>

</body>
</html>