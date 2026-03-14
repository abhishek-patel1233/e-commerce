<?php
session_start();
include("db.php");

/* Create cart session */
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

/* =========================
   ADD TO CART
========================= */

if(isset($_POST['add_to_cart'])){

    $product_id = intval($_POST['product_id']);

    $sql = "SELECT * FROM products WHERE id='$product_id'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_assoc($result);

        $found = false;

        foreach($_SESSION['cart'] as $key => $item){

            if($item['id'] == $product_id){

                $_SESSION['cart'][$key]['quantity'] += 1;

                $found = true;

                break;
            }
        }

        if(!$found){

            $_SESSION['cart'][] = [

                "id" => $row['id'],
                "name" => $row['name'],
                "price" => $row['price'],
                "image" => $row['image'],
                "quantity" => 1

            ];
        }

        /* Optional database cart */

        $check = mysqli_query($conn,"SELECT * FROM cart WHERE product_id='$product_id'");

        if(mysqli_num_rows($check) > 0){

            mysqli_query($conn,"UPDATE cart SET quantity = quantity + 1 WHERE product_id='$product_id'");

        }else{

            mysqli_query($conn,"INSERT INTO cart (product_id,name,price,image,quantity)
            VALUES ('$product_id','".$row['name']."','".$row['price']."','".$row['image']."',1)");

        }
    }

    header("Location: cart.php");
    exit();
}


/* =========================
   REMOVE ITEM
========================= */

if(isset($_GET['remove'])){

    $index = $_GET['remove'];

    $product_id = $_SESSION['cart'][$index]['id'];

    mysqli_query($conn,"DELETE FROM cart WHERE product_id='$product_id'");

    unset($_SESSION['cart'][$index]);

    $_SESSION['cart'] = array_values($_SESSION['cart']);

    header("Location: cart.php");
    exit();
}


/* =========================
   INCREASE QUANTITY
========================= */

if(isset($_GET['increase'])){

    $index = $_GET['increase'];

    $_SESSION['cart'][$index]['quantity'] += 1;

    $product_id = $_SESSION['cart'][$index]['id'];

    mysqli_query($conn,"UPDATE cart SET quantity = quantity + 1 WHERE product_id='$product_id'");

    header("Location: cart.php");
    exit();
}


/* =========================
   DECREASE QUANTITY
========================= */

if(isset($_GET['decrease'])){

    $index = $_GET['decrease'];

    if($_SESSION['cart'][$index]['quantity'] > 1){

        $_SESSION['cart'][$index]['quantity'] -= 1;

        $product_id = $_SESSION['cart'][$index]['id'];

        mysqli_query($conn,"UPDATE cart SET quantity = quantity - 1 WHERE product_id='$product_id'");

    }else{

        $product_id = $_SESSION['cart'][$index]['id'];

        mysqli_query($conn,"DELETE FROM cart WHERE product_id='$product_id'");

        unset($_SESSION['cart'][$index]);

        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    header("Location: cart.php");
    exit();
}


/* =========================
   TOTAL ITEMS & PRICE
========================= */

$cart_count = 0;
$total = 0;

foreach($_SESSION['cart'] as $item){

    $cart_count += $item['quantity'];

    $total += $item['price'] * $item['quantity'];
}

include("navbar.php");

?>

<!DOCTYPE html>
<html>

<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Cart</title>

<style>

body{
font-family:Arial;
background:#f4f4f4;
margin:0;
}

/* CART TABLE */

table{
width:80%;
margin:auto;
border-collapse:collapse;
background:white;
}

th,td{
padding:15px;
text-align:center;
border-bottom:1px solid #ddd;
font-size:18px;
}

th{
background:#222;
color:white;
}

img{
width:80px;
}

/* BUTTONS */

.remove-btn{
background:red;
color:white;
padding:8px 12px;
text-decoration:none;
border-radius:5px;
}

.buy-btn{
background:green;
color:white;
padding:8px 12px;
text-decoration:none;
border-radius:5px;
}

.quantity a{
padding:4px 8px;
background:#ddd;
border-radius:3px;
text-decoration:none;
margin:0 5px;
}

/* TOTAL BOX */

.total-box{
text-align:center;
margin-top:20px;
background:white;
padding:15px;
border-radius:8px;
width:80%;
margin-left:auto;
margin-right:auto;
}

</style>

</head>

<body>

<h2 style="text-align:center;margin:20px 0;">
🛒 Your Cart (<?php echo $cart_count; ?> items)
</h2>

<table>

<tr>
<th>Image</th>
<th>Product</th>
<th>Price</th>
<th>Quantity</th>
<th>Remove</th>
<th>Buy</th>
</tr>

<?php

if(!empty($_SESSION['cart'])){

foreach($_SESSION['cart'] as $index => $item){

echo "<tr>";

echo "<td>
<a href='product_detail.php?id=".$item['id']."'>
<img src='images/".$item['image']."'>
</a>
</td>";

echo "<td>".$item['name']."</td>";

echo "<td>₹".$item['price']."</td>";

echo "<td class='quantity'>
<a href='cart.php?decrease=".$index."'>-</a>
<span>".$item['quantity']."</span>
<a href='cart.php?increase=".$index."'>+</a>
</td>";

echo "<td><a class='remove-btn' href='cart.php?remove=".$index."'>Remove</a></td>";

echo "<td><a class='buy-btn' href='checkout.php'>Buy</a></td>";

echo "</tr>";

}

}else{

echo "<tr><td colspan='6'>Cart Empty</td></tr>";

}

?>

</table>

<div class="total-box">

<p style="font-size:18px;font-weight:bold;">
Total Items: <?php echo $cart_count; ?> |
Total Price: ₹<?php echo $total; ?>
</p>

<a href="checkout.php"
style="background:green;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;font-size:18px;">
Proceed To Checkout
</a>

</div>

</body>
</html>