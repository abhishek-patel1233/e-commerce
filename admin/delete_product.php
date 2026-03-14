<?php
session_start();
include("../db.php");

// Check Admin Session
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

// Get product ID from URL
if(isset($_GET['id'])){
    $product_id = $_GET['id'];

    // First, delete the image file from the server
    $sql = "SELECT image FROM products WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($image_name);
    $stmt->fetch();
    $stmt->close();

    if($image_name && file_exists("../images/$image_name")){
        unlink("../images/$image_name"); // delete image file
    }

    // Delete product from database
    $sql = "DELETE FROM products WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if($stmt->execute()){
        $stmt->close();
        header("Location: products_list.php?msg=deleted");
        exit();
    } else {
        echo "Error deleting product: " . $stmt->error;
    }
} else {
    echo "No product ID provided.";
}
?>