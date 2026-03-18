
<?php
session_start();
include("db.php");

if(isset($_POST['login'])){

$email=$_POST['email'];
$password=$_POST['password'];

$q=mysqli_query($conn,"SELECT * FROM users 
WHERE email='$email' AND verified=1");

$data=mysqli_fetch_assoc($q);

if(password_verify($password,$data['password'])){

$_SESSION['user']=$email;

header("Location: items-page.php");

}
else{
echo "Login Failed";
}

}
?>











<?php
session_start();
include("items-page.php"); // navbar & other items included

// Login process
if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $res = mysqli_query($conn, $sql);

    if(mysqli_num_rows($res) > 0){
        $user = mysqli_fetch_assoc($res);
        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['fname'];
            $_SESSION['success'] = "Login successful! Redirecting...";
        } else {
            $_SESSION['error'] = "Invalid password!";
        }
    } else {
        $_SESSION['error'] = "Email not registered!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - IT Depot</title>
<style>
/* Modal style like register page */
.modal {
    display: block; /* show by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    z-index: 1000;
}

.modal-box {
    background: white;
    width: 400px;
    padding: 30px;
    border-radius: 8px;
    margin: 5% auto;
    position: relative;
    text-align: center;
}

.modal-box h2 { color: #e53935; margin-bottom: 20px; }

.modal-box input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border:1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.modal-box button {
    width: 100%;
    padding: 12px;
    background: #e53935;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.modal-box button:hover { background: #d32f2f; }

.close { position: absolute; right: 12px; top: 8px; font-size: 24px; cursor: pointer; }

.error { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
.success { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px; }

.modal-box p a { color: #e53935; text-decoration: none; }
.modal-box p a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="modal">
  <div class="modal-box">
    <span class="close" onclick="window.location.href='items-page.php';">&times;</span>
    <h2>Login</h2>

    <?php
    if(isset($_SESSION['error'])){
        echo '<div class="error">'.$_SESSION['error'].'</div>';
        unset($_SESSION['error']);
    }

    if(isset($_SESSION['success'])){
        echo '<div class="success">'.$_SESSION['success'].'</div>';
        // JS redirect after 2 sec
        echo "<script>
            setTimeout(function(){
                window.location.href='items-page.php';
            }, 2000);
        </script>";
        unset($_SESSION['success']);
    }
    ?>

    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
  </div>
</div>

</body>
</html>