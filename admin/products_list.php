<?php
if(isset($_GET['msg'])){
    if($_GET['msg'] == "deleted"){
        echo "<p style='color:green; text-align:center;'>Product deleted successfully!</p>";
    } elseif($_GET['msg'] == "updated"){
        echo "<p style='color:blue; text-align:center;'>Product updated successfully!</p>";
    } elseif($_GET['msg'] == "added"){
        echo "<p style='color:green; text-align:center;'>Product added successfully!</p>";
    }
}
?>