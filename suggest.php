<?php

include("db.php");

$search = mysqli_real_escape_string($conn,$_GET['search']);

$sql = "SELECT name FROM products 
WHERE name LIKE '%$search%' 
LIMIT 5";

$result = mysqli_query($conn,$sql);

while($row = mysqli_fetch_assoc($result)){

echo "<div style='padding:8px;border-bottom:1px solid #ddd;cursor:pointer'>".$row['name']."</div>";

}

?>