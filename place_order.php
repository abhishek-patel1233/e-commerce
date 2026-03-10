<?php
session_start();
include("db.php");

/* Login check */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* Check cart */
if(!empty($_SESSION['cart'])){

    foreach($_SESSION['cart'] as $item){

        $product_id = $item['id'];
        $name = $item['name'];
        $price = $item['price'];

        $sql = "INSERT INTO orders (user_id, product_id, product_name, price, status)
                VALUES ('$user_id','$product_id','$name','$price','Pending')";

        mysqli_query($conn,$sql);
    }

    /* Cart empty */
    unset($_SESSION['cart']);

    header("Location: order_success.php");
    exit();

}else{

    echo "Cart is empty";
     header("Location: order_success.php");

}
?>