<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
header("Location: login.php");
exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id='$user_id'";
$res = mysqli_query($conn,$sql);
$user = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html>
<head>
<title>User Account - IT Depot</title>

<style>

body{
font-family: Arial;
background:#f2f2f2;
margin:0;
}

.dashboard{
width:90%;
margin:auto;
display:flex;
margin-top:40px;
}

.sidebar{
width:250px;
background:white;
padding:20px;
border-radius:6px;
}

.sidebar h3{
color:#e53935;
}

.sidebar a{
display:block;
padding:10px;
text-decoration:none;
color:black;
border-bottom:1px solid #eee;
}

.sidebar a:hover{
background:#f5f5f5;
}

.content{
flex:1;
background:white;
margin-left:20px;
padding:20px;
border-radius:6px;
}

</style>

</head>
<body>

<?php include("navbar.php"); ?>

<div class="dashboard">

<!-- LEFT MENU -->

<div class="sidebar">

<h3>My Account</h3>

<a href="user-dashboard.php">Dashboard</a>
<a href="profile.php">My Profile</a>
<a href="orders.php">My Orders</a>
<a href="wishlist.php">Wishlist</a>
<a href="address.php">My Address</a>
<a href="change-password.php">Change Password</a>
<a href="logout.php">Logout</a>

</div>

<!-- RIGHT CONTENT -->

<div class="content">

<h2>Welcome <?php echo $user['fname']; ?></h2>

<p><b>Name:</b> <?php echo $user['fname']; ?></p>
<p><b>Email:</b> <?php echo $user['email']; ?></p>
<p><b>User ID:</b> <?php echo $user['id']; ?></p>

<p>This is your account dashboard. You can check orders, wishlist, address and profile details.</p>

</div>

</div>

</body>
</html>