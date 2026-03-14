<?php
session_start();
include("db.php");

$cpu = mysqli_query($conn,"SELECT * FROM pc_components WHERE category='CPU'");
$ram = mysqli_query($conn,"SELECT * FROM pc_components WHERE category='RAM'");
$storage = mysqli_query($conn,"SELECT * FROM pc_components WHERE category='Storage'");
$gpu = mysqli_query($conn,"SELECT * FROM pc_components WHERE category='GPU'");
?>

<!DOCTYPE html>
<html>
<head>

<title>Customize Your PC</title>

<style>

body{
font-family:Arial;
background:#f4f4f4;
margin:0;
}

.container{
width:600px;
margin:auto;
background:white;
padding:30px;
margin-top:50px;
border-radius:8px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

select{
width:100%;
padding:10px;
margin-bottom:15px;
}

button{
background:green;
color:white;
border:none;
padding:12px 20px;
cursor:pointer;
border-radius:5px;
}

.total{
font-size:20px;
font-weight:bold;
margin-top:10px;
}

</style>

<script>

function calculateTotal(){

var cpu = document.getElementById("cpu").value;
var ram = document.getElementById("ram").value;
var storage = document.getElementById("storage").value;
var gpu = document.getElementById("gpu").value;

var total = parseInt(cpu) + parseInt(ram) + parseInt(storage) + parseInt(gpu);

document.getElementById("total").innerHTML = "Total Price : ₹"+total;

document.getElementById("total_price").value = total;

}

</script>

</head>

<body>

<div class="container">

<h2>Customize Your PC</h2>

<form method="POST" action="pc-cart.php">

<label>Processor</label>

<select name="cpu" id="cpu" onchange="calculateTotal()">

<?php while($row=mysqli_fetch_assoc($cpu)){ ?>

<option value="<?php echo $row['price']; ?>">

<?php echo $row['name']." - ₹".$row['price']; ?>

</option>

<?php } ?>

</select>

<label>RAM</label>

<select name="ram" id="ram" onchange="calculateTotal()">

<?php while($row=mysqli_fetch_assoc($ram)){ ?>

<option value="<?php echo $row['price']; ?>">

<?php echo $row['name']." - ₹".$row['price']; ?>

</option>

<?php } ?>

</select>

<label>Storage</label>

<select name="storage" id="storage" onchange="calculateTotal()">

<?php while($row=mysqli_fetch_assoc($storage)){ ?>

<option value="<?php echo $row['price']; ?>">

<?php echo $row['name']." - ₹".$row['price']; ?>

</option>

<?php } ?>

</select>

<label>Graphics Card</label>

<select name="gpu" id="gpu" onchange="calculateTotal()">

<?php while($row=mysqli_fetch_assoc($gpu)){ ?>

<option value="<?php echo $row['price']; ?>">

<?php echo $row['name']." - ₹".$row['price']; ?>

</option>

<?php } ?>

</select>

<div class="total" id="total">Total Price : ₹0</div>

<input type="hidden" name="total_price" id="total_price">

<br>

<button type="submit" name="build_pc">Add Build to Cart</button>

</form>

</div>

</body>

</html>