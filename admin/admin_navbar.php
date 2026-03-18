
<?php
if(!isset($_SESSION['admin'])){
header("Location: admin_login.php");
exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>

.admin-nav{
background:#222;
padding:15px;
display:flex;
justify-content:space-between;
align-items:center;

}

.admin-title{
color:white;
font-size:20px;
font-weight:bold;
}

/* Menu Links */

.admin-menu a{
color:white;
text-decoration:none;
margin-right:20px;
font-weight:bold;
padding:6px 12px;
border-radius:4px;
}

/* Hover */

.admin-menu a:hover{
    color:#4CAF50;
}

/* Active */

.admin-menu a.active{
/* color:#4CAF50; */
/* :white; */
}

</style>

<div class="admin-nav">

<div class="admin-title">
Admin Panel
</div>

<div class="admin-menu">

<a href="admin_panel.php"
class="<?php if($current_page=='admin_panel.php') echo 'active'; ?>">
Dashboard
</a>

<a href="orders.php"
class="<?php if($current_page=='orders.php') echo 'active'; ?>">
Orders
</a>

<a href="products.php"
class="<?php if($current_page=='products.php') echo 'active'; ?>">
Products
</a>
 
<a href="support.php"
class="<?php if($current_page=='support.php') echo 'active'; ?>">
Support Messages
</a>

<a href="users.php"
class="<?php if($current_page=='users.php') echo 'active'; ?>">
Users
</a>

<a href="address.php"
class="<?php if($current_page=='address.php') echo 'active'; ?>">
Address
</a>

<a href="logout.php">
Logout
</a>

</div>

</div>

