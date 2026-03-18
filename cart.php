<?php

session_start();
include("db.php");

$user_id = $_SESSION['user_id'] ?? 0;

if(!isset($_SESSION['cart'])){
$_SESSION['cart']=[];
}

/* ADD TO CART */

if(isset($_POST['add_to_cart'])){

$product_id = intval($_POST['product_id']);

$sql = mysqli_query($conn,"SELECT * FROM products WHERE id='$product_id'");
$row = mysqli_fetch_assoc($sql);

$found=false;

foreach($_SESSION['cart'] as $key=>$item){

if($item['id']==$product_id){

$_SESSION['cart'][$key]['quantity']++;
$found=true;
break;

}
}

if(!$found){

$_SESSION['cart'][]=[
"id"=>$row['id'],
"name"=>$row['name'],
"price"=>$row['price'],
"image"=>$row['image'],
"quantity"=>1
];

}

/* DATABASE SAVE */

if($user_id){

$check=mysqli_query($conn,"SELECT * FROM cart WHERE user_id='$user_id' AND product_id='$product_id'");

if(mysqli_num_rows($check)>0){

mysqli_query($conn,"UPDATE cart SET quantity=quantity+1 WHERE user_id='$user_id' AND product_id='$product_id'");

}else{

mysqli_query($conn,"INSERT INTO cart(user_id,product_id,name,price,image,quantity)
VALUES('$user_id','$product_id','".$row['name']."','".$row['price']."','".$row['image']."',1)");

}

}

header("Location: cart.php");
exit();
}


/* REMOVE ITEM */

if(isset($_GET['remove'])){

$index=$_GET['remove'];
$product_id=$_SESSION['cart'][$index]['id'];

if($user_id){
mysqli_query($conn,"DELETE FROM cart WHERE user_id='$user_id' AND product_id='$product_id'");
}

unset($_SESSION['cart'][$index]);
$_SESSION['cart']=array_values($_SESSION['cart']);

header("Location: cart.php");
exit();
}


/* INCREASE */

if(isset($_GET['increase'])){

$index=$_GET['increase'];
$_SESSION['cart'][$index]['quantity']++;

$product_id=$_SESSION['cart'][$index]['id'];

if($user_id){
mysqli_query($conn,"UPDATE cart SET quantity=quantity+1 WHERE user_id='$user_id' AND product_id='$product_id'");
}

header("Location: cart.php");
exit();
}


/* DECREASE */

if(isset($_GET['decrease'])){

$index=$_GET['decrease'];
$product_id=$_SESSION['cart'][$index]['id'];

if($_SESSION['cart'][$index]['quantity']>1){

$_SESSION['cart'][$index]['quantity']--;

if($user_id){
mysqli_query($conn,"UPDATE cart SET quantity=quantity-1 WHERE user_id='$user_id' AND product_id='$product_id'");
}

}else{

unset($_SESSION['cart'][$index]);
$_SESSION['cart']=array_values($_SESSION['cart']);

if($user_id){
mysqli_query($conn,"DELETE FROM cart WHERE user_id='$user_id' AND product_id='$product_id'");
}

}

header("Location: cart.php");
exit();
}


/* TOTAL */

$cart_count=0;
$total=0;

foreach($_SESSION['cart'] as $item){

$cart_count += $item['quantity'];

$discount_price = $item['price'] - ($item['price'] * 15 / 100);
$total += $discount_price * $item['quantity'];

}

include("navbar.php");
?>

<!DOCTYPE html>
<html>

<head>
<title>Cart</title>

<style>

body{
font-family:Arial;
background:#f4f4f4;
}

table{
width:80%;
margin:auto;
background:white;
border-collapse:collapse;
}

th,td{
padding:15px;
border-bottom:1px solid #ddd;
text-align:center;
}

img{
width:80px;
}

.btn{
padding:6px 10px;
background:#ddd;
text-decoration:none;
}

.remove{
background:red;
color:white;
}

.checkout{
background:green;
color:white;
padding:10px 20px;
text-decoration:none;
}

</style>

</head>

<body>

<h2 style="text-align:center">
Cart (<?php echo $cart_count; ?> items)
</h2>

<table>

<tr>
<th>Image</th>
<th>Name</th>
<th>Price</th>
<th>Quantity</th>
<th>Remove</th>
</tr>

<?php

if(!empty($_SESSION['cart'])){

foreach($_SESSION['cart'] as $index=>$item){

$op = $item['price'];
$dp = $op - ($op * 15 / 100);

echo "<tr>";

echo "<td><img src='images/".$item['image']."'></td>";

echo "<td>".$item['name']."</td>";

echo "<td>
<span style='text-decoration:line-through;color:gray;font-size:13px;'>₹".$op."</span><br>
<span style='color:green;font-weight:bold;'>₹".round($dp)."</span>
<br><span style='color:red;font-size:12px;'>(15% OFF)</span>
</td>";

echo "<td>

<a class='btn' href='cart.php?decrease=".$index."'>-</a>

".$item['quantity']."

<a class='btn' href='cart.php?increase=".$index."'>+</a>

</td>";

echo "<td>

<a class='remove btn' href='cart.php?remove=".$index."'>
Remove
</a>

</td>";

echo "</tr>";

}

}else{

echo "<tr><td colspan='5'>Cart Empty</td></tr>";

}

?>

</table>

<div style="text-align:center;margin-top:20px;">

<h3>Total ₹<?php echo round($total); ?> (After 15% OFF)</h3>

<a class="checkout" href="checkout.php">
Proceed To Checkout
</a>

</div>

</body>
</html>