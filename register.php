<?php
session_start();
include("db.php");
include("items-page.php");

// Agar form submit hua
if(isset($_POST['register'])){
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = strtolower(trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // Password match check
    if($password !== $confirm){
        $error = "Passwords do not match!";
    } else {
        // Email check
        $result = mysqli_query($conn, "SELECT * FROM users WHERE LOWER(email)='$email'");
        if(mysqli_num_rows($result) > 0){
            $error = "Email already registered!";
        } else {
            // Password hashing & insert
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (fname, lname, email, phone, password) 
                    VALUES ('$fname','$lname','$email','$phone','$hashed_password')";
            if(mysqli_query($conn, $sql)){
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Database Error: ".mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register - IT Depot</title>
<style>
body {font-family:Arial; margin:0; background:#f4f4f4;}
.modal{display:block; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:1000;}
.modal-box{background:white; width:420px; padding:20px; border-radius:8px; margin:5% auto; position:relative;}
.modal-box h2{margin-bottom:15px; text-align:center; color:#e53935;}
.modal-box input{width:100%; padding:10px; margin:8px 0; border:1px solid #ccc; border-radius:4px;}
.modal-box button{width:100%; padding:10px; background:#e53935; color:white; border:none; border-radius:4px; cursor:pointer;}
.modal-box button:hover{background:#d32f2f;}
.close{position:absolute; right:12px; top:8px; font-size:24px; cursor:pointer;}
.error{background:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px; border-radius:4px; text-align:center;}
.success{background:#d4edda; color:#155724; padding:10px; margin-bottom:15px; border-radius:4px; text-align:center;}
.modal-box p{text-align:center; margin-top:10px;}
.modal-box p a{color:#e53935; text-decoration:none;}
.modal-box p a:hover{text-decoration:underline;}
</style>
</head>
<body>

<div class="modal">
  <div class="modal-box">
    <span class="close" onclick="window.location.href='items-page.php';">&times;</span>
    <h2>Register</h2>

    <?php
    if(isset($error)){
        echo '<div class="error">'.$error.'</div>';
    }
    if(isset($success)){
        echo '<div class="success">'.$success.'</div>';
    }
    ?>

    <form method="POST" action="">
      <input type="text" name="fname" placeholder="First Name" required>
      <input type="text" name="lname" placeholder="Last Name" required>
      <input type="email" name="email" placeholder="E-Mail" required>
      <input type="text" name="phone" placeholder="Phone" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm" placeholder="Confirm Password" required>
      <button type="submit" name="register">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
  </div>
</div>

</body>
</html>