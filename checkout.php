<?php
session_start();
include("db.php");
include("navbar.php");

/* Login check */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = "";

/* Place order */
if(isset($_POST['place_order'])){

    if(!empty($_SESSION['cart'])){

        foreach($_SESSION['cart'] as $item){

            $product_id = $item['id'];
            $name = $item['name'];
            $price = $item['price'];

            $sql = "INSERT INTO orders (user_id, product_id, product_name, price, status)
                    VALUES ('$user_id','$product_id','$name','$price','Pending')";

            mysqli_query($conn,$sql);
        }

        /* Cart empty after order */
        unset($_SESSION['cart']);

        $success = "Your order has been placed successfully!";
    }
}
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
background:white;
padding:20px;
margin-top:30px;
border-radius:5px;
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

th{
background:#222;
color:white;
}

.total{
font-size:20px;
margin-top:20px;
text-align:right;
}

button{
background:green;
color:white;
padding:12px 20px;
border:none;
font-size:18px;
cursor:pointer;
border-radius:5px;
}

button:hover{
background:darkgreen;
}

.success{
background:#d4edda;
color:#155724;
padding:15px;
margin-bottom:15px;
text-align:center;
border-radius:4px;
}

</style>
</head>

<body>

<div class="container">

<h2>Checkout</h2>

<?php
if($success!=""){
echo "<div class='success'>$success</div>";
}
?>

<table>

<tr>
<th>Product</th>
<th>Price</th>
</tr>

<?php

$total = 0;

if(!empty($_SESSION['cart'])){

foreach($_SESSION['cart'] as $item){

$total += $item['price'];

echo "<tr>";
echo "<td>".$item['name']."</td>";
echo "<td>₹".$item['price']."</td>";
echo "</tr>";

}

}else{

echo "<tr><td colspan='2'  >Cart is empty</td></tr>";

}
?>

</table>

<div class="total">
Total Price: ₹<?php echo $total; ?>
</div>

<form method="POST" style="margin-top:20px;text-align:right;">
<button type="submit" name="place_order">Place Order</button>
</form>

</div>

</body>
</html>