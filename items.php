<?php

include("db.php");
include("chat.php");

// Search
$search = "";
if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn,$_GET['search']);
}

$sql = "SELECT * FROM products WHERE name LIKE '%$search%'";
$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>

<title>Products</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
    font-family: 'Segoe UI', Arial, sans-serif;
    margin:0;
    background:#f1f3f6;
}

/* Grid */
.products{
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(230px,1fr));
    gap:20px;
    padding:20px;
}

/* Card */
.product{
    background:white;
    padding:15px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
    transition:0.3s;
    position:relative;
    overflow:hidden;
}

/* Hover */
.product:hover{
    transform:scale(1.05);
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

/* Image */
.product img{
    width:100%;
    height:180px;
    object-fit:cover;
    border-radius:8px;
}

/* Title */
.product h3{
    font-size:16px;
    margin:10px 0 5px;
    color:#333;
}

/* Price */
.price{
    margin-top:5px;
}

/* Row */
.cart-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-top:10px;
}

/* Button */
.product button{
    padding:8px 12px;
    background:#ff9f00;
    border:none;
    color:white;
    cursor:pointer;
    border-radius:6px;
    font-size:14px;
    transition:0.3s;
}

.product button:hover{
    background:#fb641b;
}

/* Wishlist */
.wishlist{
    font-size:20px;
    transition:0.3s;
}

.black{ color:#555; }
.red{ color:#e74c3c; }

.wishlist:hover{
    transform:scale(1.3);
}

/* Badge */
.badge{
    position:absolute;
    top:10px;
    left:10px;
    background:#e74c3c;
    color:white;
    padding:4px 8px;
    font-size:12px;
    border-radius:5px;
}

</style>

</head>
<body>

<div class="products">

<?php
if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){

// Wishlist Color
$color="black";
if(isset($_SESSION['user_id'])){
    $user_id=$_SESSION['user_id'];
    $product_id=$row['id'];

    $check="SELECT * FROM wishlist WHERE user_id='$user_id' AND product_id='$product_id'";
    $res=mysqli_query($conn,$check);

    if(mysqli_num_rows($res)>0){
        $color="red";
    }
}

// 🔥 Discount Logic
$original_price = $row['price'];
$discount_price = $original_price - ($original_price * 15 / 100);
?>

<div class="product">

<span class="badge">15% OFF</span>

<a href="product_detail.php?id=<?php echo $row['id']; ?>" style="text-decoration:none; color:black;">

<img src="images/<?php echo $row['image']; ?>">

<h3><?php echo $row['name']; ?></h3>

<div class="price">
    <span style="text-decoration:line-through; color:gray; font-size:14px;">
        ₹<?php echo $original_price; ?>
    </span>
    <br>
    <span style="color:#2ecc71; font-size:18px; font-weight:bold;">
        ₹<?php echo round($discount_price); ?>
    </span>
    <span style="color:red; font-size:13px;">(15% OFF)</span>
</div>

</a>

<div class="cart-row">

<form method="POST" action="cart.php">
<input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
<button type="submit" name="add_to_cart">
<i class="fas fa-cart-plus"></i> Add
</button>
</form>

<a class="wishlist <?php echo $color; ?>" href="add_to_wishlist.php?product_id=<?php echo $row['id']; ?>">
<i class="fas fa-heart"></i>
</a>

</div>

</div>

<?php
}
}else{
echo "<p style='text-align:center;'>No products found</p>";
}
?>

</div>

</body>
</html>