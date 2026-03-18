<?php
session_start();
include("db.php");

$products = [];
$total = 0;

if(isset($_POST['product_id'])){

$product_id = $_POST['product_id'];

$stmt=$conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i",$product_id);
$stmt->execute();

$res=$stmt->get_result();
$product=$res->fetch_assoc();

if($product){
$products[] = $product;
}

}

elseif(!empty($_SESSION['cart'])){

foreach($_SESSION['cart'] as $item){
$products[] = $item;
}

}

include("navbar.php");
?>

<!DOCTYPE html>
<html>
<head>

<title>Checkout</title>

<style>

body{
font-family:Arial;
background:#f4f4f4;
}

.container{
width:70%;
margin:auto;
margin-top:40px;
background:white;
padding:20px;
border-radius:10px;
}

table{
width:100%;
border-collapse:collapse;
}

th,td{
padding:12px;
border-bottom:1px solid #ddd;
text-align:center;
}

img{
width:80px;
}

.btn{
padding:12px 20px;
background:green;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
}

</style>

</head>

<body>

<div class="container">

<h2>Checkout</h2>

<table>

<tr>
<th>Image</th>
<th>Product</th>
<th>Price</th>
</tr>

<?php

if(!empty($products)){

foreach($products as $item){

$op = $item['price'];
$dp = $op - ($op * 15 / 100);

$total += $dp;

echo "<tr>";

echo "<td><img src='images/".$item['image']."'></td>";

echo "<td>".$item['name']."</td>";

echo "<td>
<span style='text-decoration:line-through;color:gray;font-size:13px;'>₹".$op."</span><br>
<span style='color:green;font-weight:bold;'>₹".round($dp)."</span>
<br><span style='color:red;font-size:12px;'>(15% OFF)</span>
</td>";

echo "</tr>";

}

}else{

echo "<tr><td colspan='3'>No product selected</td></tr>";

}

?>

</table>

<h3 style="margin-top:20px;">
Total: ₹<?php echo round($total); ?> (After 15% OFF)
</h3>

<form method="POST" action="address.php">

<input type="hidden" name="total" value="<?php echo round($total); ?>">

<button type="submit" class="btn">
Continue To Address
</button>

</form>

</div>

</body>
</html>