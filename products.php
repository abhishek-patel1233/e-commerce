<?php
include("db.php");

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
background:#007bff;
color:white;
border:none;
padding:8px 15px;
border-radius:5px;
cursor:pointer;
}

button:hover{
background:#0056b3;
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

<form method="POST" action="add_to_cart.php">

<input type="hidden" name="product" value="<?php echo $row['name']; ?>">

<input type="hidden" name="price" value="<?php echo $row['price']; ?>">
<!-- 
<input type="number" name="qty" value="1" min="1" style="width:50px;"> -->



<button type="submit">Add To Cart</button>

</form>

</div>

<?php
}

}else{

echo "<h2 style='text-align:center'>No Product Found</h2>";

}
?>

</div>

</body>
</html>