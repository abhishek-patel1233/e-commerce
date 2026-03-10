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
<title>My Account - IT Depot</title>

<style>

body{
font-family:Arial;
margin:0;
background:#f4f4f4;
}

/* dashboard layout */

.dashboard{
display:flex;
width:90%;
margin:auto;
margin-top:40px;
}

/* sidebar */

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

/* content */

.content{
flex:1;
background:white;
margin-left:20px;
padding:25px;
border-radius:6px;
}

.profile-card{
background:#fafafa;
padding:20px;
border-radius:6px;
}

.profile-card p{
font-size:16px;
border-bottom:1px solid #eee;
padding:8px 0;
}

.edit-btn{
display:inline-block;
margin-top:15px;
background:#e53935;
color:white;
padding:10px 15px;
text-decoration:none;
border-radius:5px;
}

</style>

</head>

<body>

<?php include("navbar.php"); ?>

<div class="dashboard">

<!-- Sidebar -->

<div class="sidebar">

<h3>My Account</h3>

<a href="profile-dashboard.php">Dashboard</a>
<a href="profile.php">My Profile</a>
<a href="orders.php">My Orders</a>
<a href="wishlist-page.php">Wishlist</a>
<a href="change-password.php">Change Password</a>
<a href="logout.php">Logout</a>

</div>

<!-- Content -->

<div class="content">

<h2>Welcome <?php echo $user['fname']; ?></h2>

<div class="profile-card">

<p><b>Name :</b> <?php echo $user['fname']; ?></p>

<p><b>Email :</b> <?php echo $user['email']; ?></p>

<p><b>User ID :</b> <?php echo $user['id']; ?></p>
 <p><b>phone number :</b> <?php echo $user['phone']; ?></p>


</div>

</div>

</div>

</body>
</html>