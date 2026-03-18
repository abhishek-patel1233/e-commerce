

<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
header("Location: login.php");
exit();
}

$products = $_SESSION['checkout_products'] ?? [];
$total = $_SESSION['total'] ?? 0;

if(isset($_POST['submit_address'])){

$user_id = $_SESSION['user_id'];

$name=$_POST['name'];
$phone=$_POST['phone'];
$email=$_POST['email'];
$address=$_POST['address'];
$city=$_POST['city'];
$pincode=$_POST['pincode'];

$stmt=mysqli_prepare($conn,"INSERT INTO address(user_id,name,phone,email,address,city,pincode) VALUES (?,?,?,?,?,?,?)");

mysqli_stmt_bind_param($stmt,"issssss",$user_id,$name,$phone,$email,$address,$city,$pincode);

mysqli_stmt_execute($stmt);

$_SESSION['address_id']=mysqli_insert_id($conn);

header("Location: payment.php");
exit();

}
include("navbar.php");

?>

<!DOCTYPE html>
<html>

<head>

<title>Shipping Address</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f4f4f4;
}

.container{
max-width:600px;
margin:50px auto;
background:white;
padding:30px;
border-radius:10px;
}

</style>

</head>

<body>

<div class="container">

<h3>Shipping Address</h3>

<form method="POST">

<input type="text" name="name" placeholder="Name" class="form-control mb-3" required>

<input type="text" name="phone" placeholder="Phone" class="form-control mb-3" required>

<input type="email" name="email" placeholder="Email" class="form-control mb-3">

<textarea name="address" class="form-control mb-3" placeholder="Address" required></textarea>

<input type="text" name="city" placeholder="City" class="form-control mb-3" required>

<input type="text" name="pincode" placeholder="Pincode" class="form-control mb-3" required>

<button type="submit" name="submit_address" class="btn btn-success w-100">
Continue To Payment
</button>

</form>

</div>

</body>
</html>