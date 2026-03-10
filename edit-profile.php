<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
header("Location: login.php");
exit();
}

$user_id = $_SESSION['user_id'];

/* Update profile */

if(isset($_POST['update'])){

$name = mysqli_real_escape_string($conn,$_POST['name']);
$email = mysqli_real_escape_string($conn,$_POST['email']);

$sql = "UPDATE users SET fname='$name', email='$email' WHERE id='$user_id'";
mysqli_query($conn,$sql);

header("Location: profile.php");
}

/* Fetch user data */

$sql = "SELECT * FROM users WHERE id='$user_id'";
$res = mysqli_query($conn,$sql);
$user = mysqli_fetch_assoc($res);

?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Profile</title>

<style>

body{
font-family:Arial;
background:#f4f4f4;
margin:0;
}

.edit-box{
width:400px;
margin:60px auto;
background:white;
padding:25px;
border-radius:8px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

.edit-box h2{
text-align:center;
color:#e53935;
}

.edit-box input{
width:100%;
padding:10px;
margin:10px 0;
border:1px solid #ccc;
border-radius:4px;
}

.edit-box button{
width:100%;
padding:10px;
background:#e53935;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
}

</style>

</head>

<body>

<?php include("navbar.php"); ?>

<div class="edit-box">

<h2>Edit Profile</h2>

<form method="POST">

<input type="text" name="name" value="<?php echo $user['fname']; ?>" required>

<input type="email" name="email" value="<?php echo $user['email']; ?>" required>

<button type="submit" name="update">Update Profile</button>

</form>

</div>

</body>
</html>