
<?php
session_start();
include("db.php");

/* LOGIN CHECK */

if(!isset($_SESSION['user_id'])){
header("Location: login.php");
exit();
}

$user_id = $_SESSION['user_id'];


/* Cancel Order */

if(isset($_GET['cancel_id'])){

$order_id = $_GET['cancel_id'];

mysqli_query($conn,"UPDATE orders 
SET status='Cancelled' 
WHERE id='$order_id' AND user_id='$user_id'");

header("Location: orders.php");
exit();

}


/* Delete Order */

if(isset($_GET['delete_id'])){

$id = $_GET['delete_id'];

mysqli_query($conn,"DELETE FROM order_items 
WHERE id='$id' AND order_id IN
(SELECT id FROM orders WHERE user_id='$user_id')");

header("Location: orders.php");
exit();

}

include("navbar.php");


/* FETCH USER ORDERS */

$sql = "SELECT order_items.*, 
orders.status,
orders.payment_status

FROM order_items

JOIN orders 
ON order_items.order_id = orders.id

WHERE orders.user_id='$user_id'

ORDER BY order_items.id DESC";

$result = mysqli_query($conn,$sql);

?>

<!DOCTYPE html>
<html>
<head>

<title>My Orders</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f2f3f7;
font-family:Arial;
}

.orders-container{
max-width:1100px;
margin:40px auto;
}

.orders-card{
background:white;
border-radius:10px;
padding:25px;
box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

.orders-title{
font-weight:600;
margin-bottom:20px;
}

table th{
background:#f8f9fa;
text-align:center;
}

table td{
text-align:center;
vertical-align:middle;
}

.product-name{
font-weight:600;
color:#333;
}

.badge-cancelled{background:#dc3545;}
.badge-delivered{background:#198754;}
.badge-shipped{background:#6f42c1;}
.badge-processing{background:#ffc107;color:black;}

.payment-completed{color:green;font-weight:bold;}
.payment-pending{color:orange;font-weight:bold;}
.payment-failed{color:red;font-weight:bold;}

tr:hover{
background:#fafafa;
}

</style>

</head>

<body>

<div class="orders-container">

<div class="orders-card">

<h3 class="orders-title">📦 My Orders</h3>

<div class="table-responsive">

<table class="table table-bordered">

<thead>

<tr>

<th>Product</th>
<th>Qty</th>
<th>Price</th>
<th>Total</th>
<th>Status</th>
<th>Payment</th>
<th>View</th>
<th>Invoice</th>
<th>Cancel</th>
<th>Delete</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td class="product-name">
<?php echo $row['product_name']; ?>
</td>

<td><?php echo $row['qty']; ?></td>

<td>₹<?php echo $row['price']; ?></td>

<td>₹<?php echo $row['price'] * $row['qty']; ?></td>


<td>

<?php

$status = $row['status'];

if($status=="Pending"){
echo "<span class='badge bg-warning text-dark'>Pending</span>";
}

elseif($status=="Processing"){
echo "<span class='badge badge-processing'>Processing</span>";
}

elseif($status=="Shipped"){
echo "<span class='badge badge-shipped'>Shipped</span>";
}

elseif($status=="Delivered"){
echo "<span class='badge badge-delivered'>Delivered</span>";
}

elseif($status=="Cancelled"){
echo "<span class='badge badge-cancelled'>Cancelled</span>";
}

?>

</td>


<td>

<?php

$pay = $row['payment_status'];

if($pay=="Completed"){
echo "<span class='payment-completed'>Completed</span>";
}

elseif($pay=="Pending"){
echo "<span class='payment-pending'>Pending</span>";
}

elseif($pay=="Failed"){
echo "<span class='payment-failed'>Failed</span>";
}

?>

</td>


<td>

<?php if(!empty($row['product_id'])){ ?>

<a href="product_detail.php?id=<?php echo $row['product_id']; ?>" 
class="btn btn-success btn-sm">

View

</a>

<?php } ?>

</td>


<td>

<a href="order_success.php?id=<?php echo $row['order_id']; ?>" 
class="btn btn-primary btn-sm">

Invoice

</a>

</td>


<td>

<?php if($row['status']!="Cancelled" && $row['status']!="Delivered"){ ?>

<a href="orders.php?cancel_id=<?php echo $row['order_id']; ?>" 
class="btn btn-danger btn-sm"
onclick="return confirm('Cancel this order?')">

Cancel

</a>

<?php }else{ ?>

<span class="text-muted">Not Allowed</span>

<?php } ?>

</td>


<td>

<a href="orders.php?delete_id=<?php echo $row['id']; ?>" 
class="btn btn-dark btn-sm"
onclick="return confirm('Delete order?')">

Delete

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</body>
</html>
```
