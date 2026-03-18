<?php
session_start();
include("db.php");

if(isset($_POST['verify'])){

$email=$_POST['email'];
$otp=$_POST['otp'];

$query=mysqli_query($conn,"SELECT * FROM users 
WHERE email='$email' AND otp='$otp'");

if(mysqli_num_rows($query)>0){

mysqli_query($conn,"UPDATE users SET verified=1 WHERE email='$email'");

$_SESSION['user']=$email;

header("Location: items-page.php");

}
else{
echo "Invalid OTP";
}

}
?>
<!DOCTYPE html>
<html>
<head>

<title>Email Verification</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container">

<div class="row justify-content-center">

<div class="col-md-4">

<div class="card mt-5 shadow">

<div class="card-body">

<h4 class="text-center text-danger mb-4">Email Verification</h4>

<?php
if(isset($error)){
echo "<div class='alert alert-danger'>$error</div>";
}
?>

<form method="POST">

<input type="hidden" name="email" value="<?php echo $_GET['email']; ?>">

<div class="mb-3">

<input type="text" class="form-control" name="otp" placeholder="Enter OTP" required>

</div>

<button class="btn btn-danger w-100" name="verify">Verify OTP</button>

</form>

</div>

</div>

</div>

</div>

</div>

</body>
</html>