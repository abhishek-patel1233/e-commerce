<?php
session_start();
include("../db.php");
include("admin_navbar.php");

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

if(isset($_POST['add_product'])){

$name = $_POST['name'];
$price = $_POST['price'];
$category = $_POST['category'];
$brand = $_POST['brand'];
$description = $_POST['description'];

/* MAIN IMAGE */

$image = time().'_'.$_FILES['image']['name'];
$tmp = $_FILES['image']['tmp_name'];

move_uploaded_file($tmp,"../images/".$image);

/* INSERT PRODUCT */

$stmt=$conn->prepare("INSERT INTO products(name,price,image,category,brand,description) VALUES (?,?,?,?,?,?)");
$stmt->bind_param("sdssss",$name,$price,$image,$category,$brand,$description);
$stmt->execute();

$product_id=$stmt->insert_id;

/* MULTIPLE IMAGES */

if(!empty($_FILES['images']['name'][0])){

foreach($_FILES['images']['name'] as $key=>$img){

$image_name=time().'_'.$img;
$tmp_name=$_FILES['images']['tmp_name'][$key];

move_uploaded_file($tmp_name,"../images/".$image_name);

$stmt2=$conn->prepare("INSERT INTO product_images(product_id,image_name) VALUES (?,?)");
$stmt2->bind_param("is",$product_id,$image_name);
$stmt2->execute();

}

}

$success="Product Added Successfully";

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Add Product</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
margin:0;
}

/* container */

.container{
max-width:550px;
margin:50px auto;
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

h2{
text-align:center;
margin-bottom:20px;
}

/* form */

label{
font-weight:bold;
display:block;
margin-bottom:5px;
}

input,textarea{
width:100%;
padding:10px;
margin-bottom:15px;
border:1px solid #ccc;
border-radius:6px;
font-size:14px;
}

textarea{
resize:none;
height:100px;
}

/* file input */

.file-box{
background:#f9f9f9;
padding:10px;
border:1px dashed #ccc;
border-radius:6px;
}

/* button */

button{
width:100%;
background:#1f7a3f;
color:white;
border:none;
padding:12px;
font-size:16px;
border-radius:6px;
cursor:pointer;
}

button:hover{
background:#166532;
}

/* message */

.success{
background:#d4edda;
color:#155724;
padding:10px;
border-radius:5px;
margin-bottom:15px;
}

.error{
background:#f8d7da;
color:#721c24;
padding:10px;
border-radius:5px;
margin-bottom:15px;
}

</style>

</head>

<body>

<div class="container">

<h2>Add Product</h2>

<?php if(isset($success)){ ?>

<div class="success"><?php echo $success; ?></div>

<?php } ?>

<form method="POST" enctype="multipart/form-data">

<label>Product Name</label>
<input type="text" name="name" required>

<label>Price</label>
<input type="number" name="price" required>

<label>Category</label>
<input type="text" name="category">

<label>Brand</label>
<input type="text" name="brand">

<label>Description</label>
<textarea name="description"></textarea>

<label>Main Image</label>
<div class="file-box">
<input type="file" name="image" required>
</div>

<label>Extra Images</label>
<div class="file-box">
<input type="file" name="images[]" multiple>
</div>

<button type="submit" name="add_product">
Add Product
</button>

</form>

</div>

</body>
</html>