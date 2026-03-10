<?php
include("db.php");

$id = $_GET['id'];

$sql = "SELECT * FROM products WHERE id='$id'";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $row['name']; ?></title>

<style>

.product-detail{
width:900px;
margin:auto;
display:flex;
gap:40px;
padding:30px;
}

.product-detail img{
width:350px;
}

.product-info h2{
margin:0;
}

.price{
font-size:22px;
color:red;
}

button{
padding:10px 20px;
background:#ff6600;
border:none;
color:white;
cursor:pointer;
}

</style>

</head>

<body>

<div class="product-detail">

<div>
<img src="images/<?php echo $row['image']; ?>">
</div>

<div class="product-info">

<h2><?php echo $row['name']; ?></h2>

<p class="price">₹<?php echo $row['price']; ?></p>

<!-- <p><?php echo $row['description']; ?></p> -->

<button>Add to Cart</button>
<a href="checkout.php" style="background:green;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;font-size:18px;">
Buy Now
</a>

</div>

</div>

</body>
</html>