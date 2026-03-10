<?php
session_start();
include("db.php");

$email = mysqli_real_escape_string($conn,$_POST['email']);
$password = $_POST['password'];

$result = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
if(mysqli_num_rows($result)===1){
    $user = mysqli_fetch_assoc($result);
    if(password_verify($password, $user['password'])){
        $_SESSION['user_id']=$user['id'];
        $_SESSION['user_name']=$user['fname'];
        $_SESSION['success']="Welcome, ".$user['fname']."!";
        header("Location: items-page.php");
        exit();
    } else {
        $_SESSION['error']="Invalid password!";
        header("Location: items-page.php");
        exit();
    }
}else{
    $_SESSION['error']="Email not registered!";
    header("Location: items-page.php");
    exit();
}
?>