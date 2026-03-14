<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
$cart_count = 0;

foreach($cart as $item){
    $total += $item['price'] * $item['quantity'];
    $cart_count += $item['quantity'];
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Checkout</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.checkout-container { max-width: 800px; margin:50px auto; background:#fff; padding:30px; border-radius:10px; }
.item { display:flex; align-items:center; gap:20px; border-bottom:1px solid #ddd; padding:15px 0; }
.item img { width:80px; height:80px; object-fit:cover; border-radius:5px; }
.total { text-align:right; font-size:1.5rem; font-weight:bold; margin-top:20px; }
.btn-continue { display:block; margin-left:auto; background:green; color:white; padding:10px 25px; border-radius:5px; text-decoration:none; }
</style>
</head>
<body class="bg-light">
<div class="checkout-container">
<h2>Checkout (<?php echo $cart_count; ?> items)</h2>

<?php if($cart_count>0): ?>
<?php foreach($cart as $item): ?>
<div class="item">
<img src="images/<?php echo htmlspecialchars($item['image']); ?>">
<div>
<h5><?php echo htmlspecialchars($item['name']); ?></h5>
<p>₹<?php echo number_format($item['price'],2); ?> x <?php echo $item['quantity']; ?> = ₹<?php echo number_format($item['price']*$item['quantity'],2); ?></p>
</div>
</div>
<?php endforeach; ?>
<div class="total">Total: ₹<?php echo number_format($total,2); ?></div>
<a href="address.php" class="btn btn-continue mt-3">Proceed to Shipping</a>
<?php else: ?>
<p>Your cart is empty!</p>
<?php endif; ?>
</div>
</body>
</html>