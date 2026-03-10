<?php
session_start();
include("db.php");
include("navbar.php");

/* Cart array create */
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

/* Add to cart */
if(isset($_POST['add_to_cart'])){

    $product_id = $_POST['product_id'];

    $sql = "SELECT * FROM products WHERE id='$product_id'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)>0){

        $row = mysqli_fetch_assoc($result);

        $item = [
            "id"=>$row['id'],
            "name"=>$row['name'],
            "price"=>$row['price'],
            "image"=>$row['image']
        ];

        /* SESSION me add */
        $_SESSION['cart'][] = $item;

        /* DATABASE me save */
        $name = $row['name'];
        $price = $row['price'];
        $image = $row['image'];

        $insert = "INSERT INTO cart (product_id,name,price,image) 
                   VALUES ('$product_id','$name','$price','$image')";

        mysqli_query($conn,$insert);
    }
}

/* Remove item */
if(isset($_GET['remove'])){

    $index = $_GET['remove'];

    /* Session se remove */
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html>
<head>
<style>







body{
font-family:Arial;
background:#f4f4f4;
}

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

.remove-btn{
background:red;
color:white;
padding:8px 12px;
text-decoration:none;
border-radius:5px;
}

.remove-btn:hover{
background:darkred;
}

.buy-btn{
background:green;
color:white;
padding:8px 12px;
text-decoration:none;
border-radius:5px;
}

.buy-btn:hover{
background:darkgreen;
}

</style>
</head>
<body>

<h2 style="text-align:center;">Your Cart</h2>

<table>

<tr>
<th>Image</th>
<th>Product</th>
<th>Price</th>
<th>Action</th>
<th>Buy</th>
</tr>

<?php

$total = 0;

if(!empty($_SESSION['cart'])){

foreach($_SESSION['cart'] as $index=>$item){

$total += $item['price'];

echo "<tr>";
echo "<td><img src='images/".$item['image']."'></td>";
echo "<td>".$item['name']."</td>";
echo "<td>₹".$item['price']."</td>";
echo "<td><a class='remove-btn' href='cart.php?remove=".$index."'>Remove</a></td>";
echo "<td><a class='buy-btn' href='checkout.php?buy=".$item['id']."'>Buy</a></td>";
echo "</tr>";

}

}

?>

</table>

<h3 style="text-align:center; margin-top:20px;">
Total Price: ₹<?php echo $total; ?>
</h3>

<div style="text-align:center; margin-top:15px;">
<a href="checkout.php" style="background:green;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;font-size:18px;">
Buy Now
</a>
</div>

</body>
</html>