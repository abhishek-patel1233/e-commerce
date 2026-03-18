<?php
include("db.php");

// Get search value
$search = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn,$_GET['search']);
}

// Query
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
margin:0;
}

/* SEARCH BOX */
.search-box{
text-align:center;
margin-top:20px;
}

.search-box input{
padding:10px;
width:250px;
border-radius:5px;
border:1px solid #ccc;
}

.search-box button{
padding:10px 15px;
border:none;
background:#28a745;
color:white;
border-radius:5px;
cursor:pointer;
}

.search-box button:hover{
background:#1e7e34;
}

/* PRODUCTS */
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
transition:0.3s;
}

.product:hover{
transform:scale(1.05);
}

/* IMAGE */
.product img{
width:150px;
height:150px;
object-fit:cover;
}

/* LINK */
.product a{
text-decoration:none;
color:black;
}

/* BUTTON */
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

<!-- 🔍 SEARCH -->


<!-- PRODUCTS -->
<div class="products">

<?php
if(mysqli_num_rows($result) > 0){

while($row = mysqli_fetch_assoc($result)){
?>

<div class="product">

<!-- CLICKABLE PRODUCT -->
<a href="product_detail.php?id=<?php echo $row['id']; ?>">

<img src="images/<?php echo $row['image']; ?>">

<h3><?php echo $row['name']; ?></h3>

</a>

<p>Price: ₹<?php echo $row['price']; ?></p>

<form method="POST" action="cart.php">
<input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
<button type="submit" name="add_to_cart">Add to Cart</button>
</form>

</div>

<?php
}

}else{
echo "<h2 style='text-align:center;'>No Product Found</h2>";
}
?>

</div>

</body>
</html>