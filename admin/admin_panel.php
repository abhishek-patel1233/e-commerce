
<?php
session_start();
include("../db.php");
include("admin_navbar.php");

/* Count Data */

$orders = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM orders"));
$products = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM products"));
$users = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM users"));
?>

<!DOCTYPE html>
<html>

<head>

<title>Admin Dashboard</title>

<style>

body{
font-family:Arial;
background:#f5f5f5;
}

.dashboard{
width:1000px;
margin:40px auto;
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
}

.card{
background:white;
padding:30px;
border-radius:8px;
text-align:center;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

.card h2{
margin:0;
}

</style>

</head>

<body>

<div class="dashboard">

<div class="card">
<h2><?php echo $orders; ?></h2>
<p>Total Orders</p>
</div>

<div class="card">
<h2><?php echo $products; ?></h2>
<p>Total Products</p>
</div>

<div class="card">
<h2><?php echo $users; ?></h2>
<p>Total Users</p>
</div>

</div>

</body>
</html>
```
