<?php
session_start();

if(isset($_POST['build_pc'])){

$total = $_POST['total_price'];

$_SESSION['pc_build_price'] = $total;

header("Location: pc-summary.php");

}
?>