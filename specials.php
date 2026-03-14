<?php
session_start();
include("db.php");

$sql = "SELECT * FROM products WHERE is_special=1";
$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>

<title>Special Deals</title>


<style>

body{
font-family:Arial;
background:#f4f4f4;
margin:0;
}

.container{
width:90%;
margin:auto;
margin-top:40px;
}

.products{
display:flex;
flex-wrap:wrap;
}

.product{
background:white;
width:220px;
margin:15px;
padding:15px;
text-align:center;
border-radius:8px;
box-shadow:0 0 8px rgba(0,0,0,0.1);
position:relative;
}

.product img{
width:100%;
height:150px;
object-fit:cover;
}

.price-old{
text-decoration:line-through;
color:gray;
}

.price-new{
color:red;
font-size:18px;
font-weight:bold;
}

.badge{
position:absolute;
top:10px;
left:10px;
background:red;
color:white;
padding:5px 10px;
font-size:12px;
border-radius:4px;
}

.product button{
background:green;
color:white;
border:none;
padding:8px 12px;
border-radius:5px;
cursor:pointer;
}

</style>

</head>

<body>

<?php include("navbar.php"); ?>

<div class="container">

<h2>🔥 Special Deals</h2>
<h2 style="text-align:center;color:#e60023;">
🔥 Flat 15% OFF On All Products
</h2>

<div class="products">

<?php

if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){
?>

<div class="product">

<div class="badge">SALE</div>

<img src="images/<?php echo $row['image']; ?>">

<h3><?php echo $row['name']; ?></h3>

<p>

<span class="price-old">₹<?php echo $row['price']; ?></span>
<br>

<span class="price-new">₹<?php echo $row['offer_price']; ?></span>

</p>

<form method="POST" action="cart.php">

<input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">

<button type="submit" name="add_to_cart">Add to Cart</button>

</form>

</div>

<?php
}

}else{

echo "<p>No special products available</p>";

}

?>

</div>

</div>

</body>
</html>