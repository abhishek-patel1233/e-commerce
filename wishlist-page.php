<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
header("Location: login.php");
exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT products.* , wishlist.id AS wid 
        FROM wishlist 
        JOIN products ON wishlist.product_id = products.id 
        WHERE wishlist.user_id='$user_id'";

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>My Wishlist</title>

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
}

.product img{
width:100%;
height:150px;
object-fit:cover;
}

.product button{
padding:8px 12px;
background:green;
border:none;
color:white;
cursor:pointer;
border-radius:5px;
}

.remove{
display:block;
margin-top:10px;
color:red;
text-decoration:none;
}

</style>

</head>

<body>

<?php include("navbar.php"); ?>

<div class="container">

<h2>My Wishlist</h2>

<div class="products">

<?php

if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){
?>

<div class="product">

<img src="images/<?php echo $row['image']; ?>">

<h3><?php echo $row['name']; ?></h3>

<p>₹<?php echo $row['price']; ?></p>

<form method="POST" action="cart.php">
<input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
<button type="submit" name="add_to_cart">Add to Cart</button>
</form>

<a class="remove" href="remove-wishlist.php?id=<?php echo $row['wid']; ?>">
Remove
</a>

</div>

<?php
}

}else{

echo "<p>No products in wishlist</p>";

}

?>

</div>

</div>

</body>
</html>










