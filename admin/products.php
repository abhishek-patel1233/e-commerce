
<?php
session_start();
include("../db.php");
include("admin_navbar.php");

if(!isset($_SESSION['admin'])){
header("Location: admin_login.php");
exit();
}

$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>

<head>

<title>Products Management</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
margin:0;
}

.container{
width:90%;
margin:auto;
margin-top:40px;
}

h2{
margin-bottom:10px;
}

/* Add Button */

.add-btn{
background:#1f7a3f;
color:white;
padding:12px 18px;
text-decoration:none;
border-radius:6px;
font-weight:bold;
display:inline-block;
margin-bottom:20px;
}

.add-btn:hover{
background:#166532;
}

/* Table */

table{
width:100%;
border-collapse:collapse;
background:white;
border-radius:8px;
overflow:hidden;
box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

th{
background:#1f7a3f;
color:white;
padding:14px;
text-align:left;
}

td{
padding:12px;
border-bottom:1px solid #ddd;
}

tr:nth-child(even){
background:#f7f7f7;
}

/* Image */

.product-img{
width:60px;
height:60px;
object-fit:cover;
border-radius:6px;
}

/* Buttons */

.edit-btn{
background:#1f7a3f;
color:white;
padding:6px 12px;
border-radius:5px;
text-decoration:none;
margin-right:5px;
}

.delete-btn{
background:#dc3545;
color:white;
padding:6px 12px;
border-radius:5px;
text-decoration:none;
}

.edit-btn:hover{
background:#166532;
}

.delete-btn:hover{
background:#b52a37;
}

</style>

</head>

<body>

<div class="container">

<h2>Products Management</h2>

<a href="add_product.php" class="add-btn">+ Add Product</a>

<table>

<tr>
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Price</th>
<th>Category</th>
<th>Action</th>
</tr>

<?php while($row = $products->fetch_assoc()){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td>
<img src="../images/<?php echo $row['image']; ?>" class="product-img">
</td>

<td><?php echo $row['name']; ?></td>

<td>₹<?php echo $row['price']; ?></td>

<td><?php echo $row['category']; ?></td>

<td>

<a href="edit_product.php?id=<?php echo $row['id']; ?>" class="edit-btn">
Edit
</a>

<a href="delete_product.php?id=<?php echo $row['id']; ?>" class="delete-btn">
Delete
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>
```
