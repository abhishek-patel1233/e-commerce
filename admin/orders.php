<?php
session_start();
include("db.php");
include("admin_navbar.php");
/* ======================
Update Order Status
====================== */

if(isset($_POST['update_status'])){

$order_id = $_POST['order_id'];
$status = $_POST['status'];
$payment_status = $_POST['payment_status'];

mysqli_query($conn,"UPDATE orders 
SET status='$status', payment_status='$payment_status' 
WHERE id='$order_id'");

}

/* ======================
Fetch Orders
====================== */

$sql = "

SELECT

o.id AS order_id,
o.user_id,
o.address_id,
o.status,
o.payment_status,
o.order_date,
o.payment_ss,

u.fname,
u.lname,
u.email,
u.phone,

p.id AS product_id,
p.name AS product_name,

oi.qty,
oi.price,
(oi.qty*oi.price) AS total_price,

a.address,
a.city,
a.pincode

FROM orders o

JOIN users u ON o.user_id = u.id
JOIN order_items oi ON oi.order_id = o.id
JOIN products p ON p.id = oi.product_id
JOIN address a ON o.address_id = a.id

ORDER BY o.order_date DESC

";

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>

<head>

<title>Admin Orders</title>

<style>

body{
font-family:Arial;
background:#f5f5f5;
margin:20px;

}

h2{
text-align:center;
}

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

th,td{
padding:10px;
border-bottom:1px solid #ddd;
text-align:center;
}

th{
background:#4CAF50;
color:white;
}

tr:hover{
background:#f2f2f2;
}

img{
border-radius:6px;
}

select{
padding:5px;
}

button{
background:#4CAF50;
color:white;
border:none;
padding:6px 10px;
cursor:pointer;
border-radius:4px;
}

button:hover{
background:#45a049;
}

.pending{color:orange;font-weight:bold;}
.completed{color:green;font-weight:bold;}
.failed{color:red;font-weight:bold;}

</style>

</head>

<body>

<h2>Admin Order Management</h2>

<table>

<tr>

<th>Order ID</th>
<th>User ID</th>
<th>Address ID</th>

<th>User</th>
<th>Email</th>
<th>Phone</th>

<th>Address</th>

<th>Product ID</th>
<th>Product</th>
<th>Qty</th>
<th>Price</th>
<th>Total Price</th>

<th>Payment Screenshot</th>

<th>Status</th>
<th>Payment</th>
<th>Date</th>

<th>Action</th>

</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['order_id']; ?></td>

<td><?php echo $row['user_id']; ?></td>

<td><?php echo $row['address_id']; ?></td>

<td><?php echo $row['fname']." ".$row['lname']; ?></td>

<td><?php echo $row['email']; ?></td>

<td><?php echo $row['phone']; ?></td>

<td>
<?php
echo $row['address']."<br>";
echo $row['city']." - ".$row['pincode'];
?>
</td>

<td><?php echo $row['product_id']; ?></td>

<td><?php echo $row['product_name']; ?></td>

<td><?php echo $row['qty']; ?></td>

<td>₹<?php echo $row['price']; ?></td>

<td><b>₹<?php echo $row['total_price']; ?></b></td>

<td>

<?php
if($row['payment_ss']){
?>
<a href="../payments/<?php echo $row['payment_ss']; ?>.png" target="_blank">
<img src="../payments/<?php echo $row['payment_ss']; ?>.png" width="80">
</a>

<?php
}else{

echo "No Screenshot";

}
?>

</td>

<td class="<?php echo strtolower($row['status']); ?>">
<?php echo $row['status']; ?>
</td>

<td class="<?php echo strtolower($row['payment_status']); ?>">
<?php echo $row['payment_status']; ?>
</td>

<td><?php echo $row['order_date']; ?></td>

<td>

<form method="post">

<input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">

<select name="status">

<option value="Pending" <?php if($row['status']=="Pending") echo "selected"; ?>>Pending</option>

<option value="Shipped" <?php if($row['status']=="Shipped") echo "selected"; ?>>Shipped</option>

<option value="Delivered" <?php if($row['status']=="Delivered") echo "selected"; ?>>Delivered</option>

<option value="Cancelled" <?php if($row['status']=="Cancelled") echo "selected"; ?>>Cancelled</option>

</select>

<select name="payment_status">

<option value="Pending" <?php if($row['payment_status']=="Pending") echo "selected"; ?>>Pending</option>

<option value="Completed" <?php if($row['payment_status']=="Completed") echo "selected"; ?>>Completed</option>

<option value="Failed" <?php if($row['payment_status']=="Failed") echo "selected"; ?>>Failed</option>

</select>

<br><br>

<button type="submit" name="update_status">Update</button>

</form>

</td>

</tr>

<?php } ?>

</table>

</body>

</html>