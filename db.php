<?php
$conn = mysqli_connect("localhost","root","","itstore");
if(!$conn){
    die("Database not connected: " . mysqli_connect_error());
}
?>


<?php
$conn = mysqli_connect("localhost", "root", "", "itstore");
if(!$conn){
    die("Database connection failed: " . mysqli_connect_error());
}
?>