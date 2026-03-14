<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
<title>Your PC Build</title>
</head>

<body style="font-family:Arial;text-align:center;margin-top:100px;">

<h2>Your Custom PC Build</h2>

<h1>Total Price : ₹<?php echo $_SESSION['pc_build_price']; ?></h1>

<a href="cart.php">Proceed to Cart</a>

</body>

</html>