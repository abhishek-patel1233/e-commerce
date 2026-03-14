<?php
session_start();
include("db.php");

/* LOGIN CHECK */

if(!isset($_SESSION['user_id']) || !isset($_SESSION['address_id'])){
header("Location: checkout.php");
exit();
}

$cart = $_SESSION['cart'] ?? [];

if(empty($cart)){
echo "Cart Empty";
exit();
}

$user_id = $_SESSION['user_id'];
$address_id = $_SESSION['address_id'];


/* GET USER EMAIL */

$user_query=mysqli_query($conn,"SELECT email FROM users WHERE id='$user_id'");
$user=mysqli_fetch_assoc($user_query);
$email=$user['email'];


/* TOTAL CALCULATE */

$total=0;

foreach($cart as $item){
$total += $item['price'] * $item['quantity'];
}

$error="";


/* PAY NOW */

if(isset($_POST['pay_now'])){

$payment_method=$_POST['payment_method'];
$upi_id=$_POST['upi_id'] ?? NULL;

$payment_ss=NULL;


/* =====================
UPI PAYMENT
===================== */

if($payment_method=="UPI"){

if(empty($_FILES['payment_ss']['name'])){

$error="Upload payment screenshot";

}else{

$folder="payments/";

/* create folder */

if(!is_dir($folder)){
mkdir($folder,0777,true);
}

$file_name=$_FILES['payment_ss']['name'];
$tmp_name=$_FILES['payment_ss']['tmp_name'];

/* get extension */

$ext=strtolower(pathinfo($file_name,PATHINFO_EXTENSION));

if($ext==""){
$ext="png";
}

/* new filename */

$payment_ss=time().".".$ext;

/* upload */

move_uploaded_file($tmp_name,$folder.$payment_ss);

}

$status="Pending";

}else{

/* COD */

$status="Completed";

}


/* =====================
INSERT ORDER
===================== */

if($error==""){

$stmt=mysqli_prepare($conn,"
INSERT INTO orders
(user_id,email,address_id,payment_method,upi_id,payment_ss,total,status,order_date)
VALUES (?,?,?,?,?,?,?,?,NOW())
");

mysqli_stmt_bind_param($stmt,"isissdss",
$user_id,
$email,
$address_id,
$payment_method,
$upi_id,
$payment_ss,
$total,
$status
);

mysqli_stmt_execute($stmt);

$order_id=mysqli_insert_id($conn);


/* =====================
INSERT ORDER ITEMS
===================== */

foreach($cart as $item){

$stmt2=mysqli_prepare($conn,"
INSERT INTO order_items
(order_id,product_id,product_name,price,qty)
VALUES (?,?,?,?,?)
");

mysqli_stmt_bind_param($stmt2,"iisdi",
$order_id,
$item['id'],
$item['name'],
$item['price'],
$item['quantity']
);

mysqli_stmt_execute($stmt2);

}


/* CLEAR CART */

unset($_SESSION['cart']);
unset($_SESSION['address_id']);


/* REDIRECT */

header("Location: order_success.php?id=".$order_id);
exit();

}

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Payment</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f4f4f4;
font-family:Arial;
}

.payment-container{
max-width:900px;
margin:40px auto;
display:flex;
gap:30px;
flex-wrap:wrap;
}

.box{
background:white;
padding:25px;
border-radius:8px;
flex:1;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

#upi_box{
display:none;
margin-top:15px;
}

img{
width:150px;
margin-top:10px;
}

.btn-pay{
width:100%;
margin-top:20px;
}

.order-summary div{
display:flex;
justify-content:space-between;
padding:8px 0;
border-bottom:1px dashed #ddd;
}

</style>

<script>

function showPayment(){

let method=document.querySelector('input[name="payment_method"]:checked').value;

if(method=="UPI"){
document.getElementById("upi_box").style.display="block";
}else{
document.getElementById("upi_box").style.display="none";
}

}

</script>

</head>

<body>

<div class="payment-container">

<div class="box">

<h4>Select Payment Method</h4>

<?php if($error!=""){ ?>

<div class="alert alert-danger">
<?php echo $error; ?>
</div>

<?php } ?>

<form method="POST" enctype="multipart/form-data">

<div class="form-check">

<input type="radio" name="payment_method" value="COD" onclick="showPayment()" required>

Cash on Delivery

</div>

<div class="form-check mt-2">

<input type="radio" name="payment_method" value="UPI" onclick="showPayment()">

UPI / Online

</div>

<div id="upi_box">

<input type="text" name="upi_id" class="form-control mt-2" placeholder="Enter UPI ID">

<p class="mt-2">OR Scan QR</p>

<img src="images/upi_qr.png">

<input type="file" name="payment_ss" class="form-control mt-2">

</div>

<button type="submit" name="pay_now" class="btn btn-success btn-pay">

Pay ₹<?php echo number_format($total,2); ?>

</button>

</form>

</div>


<div class="box">

<h4>Order Summary</h4>

<?php foreach($cart as $item){ ?>

<div class="order-summary">

<div>
<?php echo $item['name']; ?> x <?php echo $item['quantity']; ?>
</div>

<div>
₹<?php echo $item['price']*$item['quantity']; ?>
</div>

</div>

<?php } ?>

<hr>

<div class="order-summary">

<div><b>Total</b></div>

<div><b>₹<?php echo $total; ?></b></div>

</div>

</div>

</div>

</body>
</html>