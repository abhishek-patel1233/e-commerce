<?php
session_start();
include("db.php");
include("navbar.php");

// Get order ID from URL
$order_id = $_GET['id'] ?? null;
if(!$order_id){
    echo "Invalid order.";
    exit();
}

// Fetch order
$stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);

if(!$order){
    echo "Order not found.";
    exit();
}

// Fetch order items
$stmt2 = mysqli_prepare($conn, "SELECT * FROM order_items WHERE order_id = ?");
mysqli_stmt_bind_param($stmt2, "i", $order_id);
mysqli_stmt_execute($stmt2);
$order_items = mysqli_stmt_get_result($stmt2);
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Success</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{font-family:Arial;background:#f4f4f4;}
.container{max-width:800px;margin:50px auto;background:#fff;padding:30px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,0.1);}
h3{margin-bottom:20px;}
.order-item{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed #ddd;}
.status-pending{color:#ff9800;font-weight:bold;}
.status-completed{color:#28a745;font-weight:bold;}
.btn-shop{margin-top:20px;}
</style>
</head>
<body>
<div class="container">
<h3>Order #<?php echo htmlspecialchars($order['id']); ?> Details</h3>

<p><strong>Order Date & Time:</strong> <?php echo date("d-m-Y H:i:s", strtotime($order['order_date'])); ?></p>
<p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
<p><strong>Payment Status:</strong> 
<?php 
$status = $order['status'] ?? $order['payment_status'];
if(strtolower($status) == "pending"){
    echo '<span class="status-pending">Pending (awaiting admin verification)</span>';
}else{
    echo '<span class="status-completed">Completed</span>';
}
?>
</p>

<h4>Order Items</h4>
<?php while($item = mysqli_fetch_assoc($order_items)): ?>
<div class="order-item">
<div><?php echo htmlspecialchars($item['product_name']); ?> x <?php echo $item['qty']; ?></div>
<div>₹<?php echo number_format($item['price']*$item['qty'],2); ?></div>
</div>
<?php endwhile; ?>
<hr>
<div class="order-item">
<div><strong>Total:</strong></div>
<div><strong>₹<?php echo number_format($order['total'],2); ?></strong></div>
</div>

<?php if(strtolower($status) == "pending" && strtolower($order['payment_method']) == "upi"): ?>
<p class="mt-3 text-warning">Your UPI payment is pending. The admin will verify your payment soon.</p>
<?php endif; ?>

<a href="items-page.php" class="btn btn-primary btn-shop">Continue Shopping</a>
</div>
</body>
</html>