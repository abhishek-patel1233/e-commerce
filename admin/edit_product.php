<?php
session_start();
include("../db.php");
include("admin_navbar.php");

if(!isset($_SESSION['admin'])){
header("Location: admin_login.php");
exit();
}

$product_id = $_GET['id'] ?? 0;

/* GET PRODUCT */

$stmt=$conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i",$product_id);
$stmt->execute();
$product=$stmt->get_result()->fetch_assoc();

if(!$product){
echo "Product not found";
exit();
}

/* GET EXTRA IMAGES */

$images=$conn->query("SELECT * FROM product_images WHERE product_id='$product_id'");

/* DELETE EXTRA IMAGE */

if(isset($_GET['delete_img'])){

$img_id=$_GET['delete_img'];

$row=$conn->query("SELECT * FROM product_images WHERE id='$img_id'")->fetch_assoc();

if($row){
unlink("../images/".$row['image_name']);
$conn->query("DELETE FROM product_images WHERE id='$img_id'");
}

header("Location: edit_product.php?id=$product_id");
exit();

}

/* UPDATE PRODUCT */

if(isset($_POST['update_product'])){

$name=$_POST['name'];
$price=$_POST['price'];
$category=$_POST['category'];
$brand=$_POST['brand'];
$description=$_POST['description'];

$image_name=$product['image'];

/* MAIN IMAGE UPDATE */

if(!empty($_FILES['image']['name'])){

if(file_exists("../images/".$product['image'])){
unlink("../images/".$product['image']);
}

$image_name=time().$_FILES['image']['name'];

move_uploaded_file($_FILES['image']['tmp_name'],"../images/".$image_name);

}

/* UPDATE DB */

$stmt=$conn->prepare("UPDATE products SET name=?,price=?,image=?,category=?,brand=?,description=? WHERE id=?");
$stmt->bind_param("sdssssi",$name,$price,$image_name,$category,$brand,$description,$product_id);
$stmt->execute();

/* ADD EXTRA IMAGES */

if(!empty($_FILES['images']['name'][0])){

foreach($_FILES['images']['name'] as $key=>$img){

$image_name=time().$img;

$tmp=$_FILES['images']['tmp_name'][$key];

move_uploaded_file($tmp,"../images/".$image_name);

$conn->query("INSERT INTO product_images(product_id,image_name) VALUES('$product_id','$image_name')");

}

}

header("Location: products.php");
exit();

}

?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Product</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h2>Edit Product</h2>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">
<label>Name</label>
<input type="text" name="name" class="form-control" value="<?php echo $product['name']; ?>">
</div>

<div class="mb-3">
<label>Price</label>
<input type="number" name="price" class="form-control" value="<?php echo $product['price']; ?>">
</div>

<div class="mb-3">
<label>Category</label>
<input type="text" name="category" class="form-control" value="<?php echo $product['category']; ?>">
</div>

<div class="mb-3">
<label>Brand</label>
<input type="text" name="brand" class="form-control" value="<?php echo $product['brand']; ?>">
</div>

<div class="mb-3">
<label>Description</label>
<textarea name="description" class="form-control"><?php echo $product['description']; ?></textarea>
</div>

<div class="mb-3">
<label>Main Image</label><br>

<img src="../images/<?php echo $product['image']; ?>" width="120"><br><br>

<input type="file" name="image" class="form-control">
</div>

<div class="mb-3">
<label>Add More Images</label>
<input type="file" name="images[]" class="form-control" multiple>
</div>

<h5>Extra Images</h5>

<div class="row">

<?php while($img=$images->fetch_assoc()){ ?>

<div class="col-md-2 text-center">

<img src="../images/<?php echo $img['image_name']; ?>" class="img-thumbnail">

<br>

<a href="edit_product.php?id=<?php echo $product_id ?>&delete_img=<?php echo $img['id']; ?>" class="btn btn-danger btn-sm mt-1">

Delete

</a>

</div>

<?php } ?>

</div>

<br>

<button type="submit" name="update_product" class="btn btn-success">

Update Product

</button>

<a href="products.php" class="btn btn-secondary">

Back

</a>

</form>

</div>

</body>
</html>