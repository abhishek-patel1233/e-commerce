<?php

include("db.php");

// Search
$search = "";
if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn,$search = $_GET['search']);
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
font-family:Arial;
margin:0;
background:#f4f4f4;
color: black;
}

.products{
display:flex;
flex-wrap:wrap;
justify-content:center;
padding:20px;
}

.product{
background:white;
width:200px;
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

.product button{
padding:8px 12px;
background:green;
border:none;
color:white;
cursor:pointer;
border-radius:5px;
}

/* wishlist icon */

.wishlist{
position:absolute;
top:10px;
right:10px;
font-size:20px;
cursor:pointer;
}

.black{
color:black;
}

.red{
color:red;
}

</style>

</head>
<body>

<div class="products">

<?php
if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){

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
?>
<a href="product_detail.php?id=<?php echo $row['id']; ?>">
<div class="product">


<img src="images/<?php echo $row['image']; ?>">

<h3 ><?php echo $row['name']; ?></h3>

<p>₹<?php echo $row['price']; ?></p>

<form method="POST" action="cart.php">
<input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
<button type="submit" name="add_to_cart">Add to Cart</button>

<a class="wishlist <?php echo $color; ?>" href="add_to_wishlist.php?product_id=<?php echo $row['id']; ?>">
<i class="fas fa-heart"></i>
</a> 


</form>

</div>
</a>
<?php
}
}else{
echo "<p>No products found</p>";
}
?>

</div>

</body>
</html>