<?php
session_start();
include("db.php");

// Trim & lowercase for email
$fname = mysqli_real_escape_string($conn, $_POST['fname']);
$lname = mysqli_real_escape_string($conn, $_POST['lname']);
$email = strtolower(trim($_POST['email']));
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$password = $_POST['password'];
$confirm = $_POST['confirm'];

// Password match check
if($password !== $confirm){
    $_SESSION['error'] = "Passwords do not match!";
    header("Location: register.php");
    exit();
}

// Password hashing
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if email already exists
$result = mysqli_query($conn, "SELECT * FROM users WHERE LOWER(email)= '$email'") or die(mysqli_error($conn));
if(mysqli_num_rows($result) > 0){
    $_SESSION['error'] = "Email already registered!";
    header("Location: register.php");
    exit();
}

// Insert into database
$sql = "INSERT INTO users (fname, lname, email, phone, password) VALUES ('$fname', '$lname', '$email', '$phone', '$hashed_password')";
if(mysqli_query($conn, $sql)){
    $_SESSION['success'] = "Registration successful! You can now login.";
    header("Location: login.php");
    exit();
} else {
    $_SESSION['error'] = "Database Error: " . mysqli_error($conn);
    header("Location: register.php");
    exit();
}
?>