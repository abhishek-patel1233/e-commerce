<?php
include("db.php");

$search = "";

if(isset($_GET['search'])){
$search = mysqli_real_escape_string($conn,$_GET['search']);
}

if($search==""){
$sql = "SELECT * FROM products";
}else{
$sql = "SELECT * FROM products 
WHERE name LIKE '%$search%' 
OR category LIKE '%$search%'";
}

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>

<head>

<title>Products</title>

<style>

body{
font-family:Arial;
background:#f2f2f2;
}

.products{
display:flex;
flex-wrap:wrap;
justify-content:center;
padding:30px;
}

.product{
background:white;
width:220px;
margin:15px;
padding:15px;
text-align:center;
border-radius:10px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

.product img{
width:150px;
height:150px;
object-fit:cover;
}

button{
background:#28a745;
color:white;
border:none;
padding:8px 15px;
border-radius:5px;
cursor:pointer;
}

button:hover{
background:#1e7e34;
}

</style>

</head>

<body>

<?php include("navbar.php"); ?>

<div class="products">

<?php

if(mysqli_num_rows($result) > 0){

while($row = mysqli_fetch_assoc($result)){
?>

<div class="product">

<img src="images/<?php echo $row['image']; ?>">

<h3><?php echo $row['name']; ?></h3>

<p>Price: ₹<?php echo $row['price']; ?></p>

<form method="POST" action="cart.php">

<input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">

<button type="submit" name="add_to_cart">Add to Cart</button>

</form>

</div>

<?php
}

}else{

echo "<h2>No Product Found</h2>";

}

?>

</div>

</body>
</html>