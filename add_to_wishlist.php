<?php
session_start();
include("db.php");

/* Login check */

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['product_id'];

/* Check already exists */

$check = "SELECT * FROM wishlist WHERE user_id='$user_id' AND product_id='$product_id'";
$result = mysqli_query($conn,$check);

if(mysqli_num_rows($result) == 0){

    $insert = "INSERT INTO wishlist (user_id,product_id) VALUES ('$user_id','$product_id')";
    mysqli_query($conn,$insert);

}

/* redirect back */

header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
?>