
<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
include("db.php");

$cart_count = 0;

if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        $cart_count += $item['quantity'];
    }
}
$wishlist_count = 0;
if(isset($_SESSION['user_id'])){
    $uid = $_SESSION['user_id'];
    $res = mysqli_query($conn,"SELECT COUNT(*) as total FROM wishlist WHERE user_id='$uid'");
    $data = mysqli_fetch_assoc($res);
    $wishlist_count = $data['total'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>The IT Depot</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
}

/* Top red bar */
.navbar-top {
    background-color: #e53935;
    color: #fff;
    padding: 5px 20px;
    font-size: 14px;
    text-align: center;
}
.navbar-top i {
    margin-right: 8px;
}

/* Main navbar */
.navbar-main {
    background-color: #48d472;
    display: flex;
    align-items: center;
    padding: 10px 20px;
    flex-wrap: wrap;
    justify-content: space-between;
}
.logo {
    font-weight: bold;
    font-size: 24px;
}
.logo span {
    color: #e53935;
}
.search-container {
    flex: 1;
    display: flex;
    margin: 10px 20px;
}
.search-container input {
    flex: 1;
    padding: 7px;
    border: none;
    border-radius: 3px 0 0 3px;
}
.search-container button {
    padding: 7px 10px;
    border: none;
    background-color: #e53935;
    color: white;
    border-radius: 0 3px 3px 0;
    cursor: pointer;
}
.menu {
    display: flex;
    align-items: center;
    gap: 20px;
    margin: auto;

}

.menu a{color:black;text-decoration:none;margin:10px;font-size:18px;
 font-weight: bold;display:flex;
    
}
.menu a:hover {
    color: #e53935;
}
.cart {
    background-color: #4caf50;
    color: white;
    padding: 7px 10px;
    border-radius: 3px;
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Account dropdown */
.account-dropdown {
    position: relative;
    display: inline-block;
}
.account-dropdown:hover .dropdown-content {
    display: block ;
}
.dropdown-content {
    display: none;
    position: absolute;
    top: 35px;
    right: 0;
    background-color: white;
    min-width: 120px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.3);
    z-index: 1000;
    border-radius: 5px;
}
.dropdown-content a {
    color: black;
    padding: 10px;
    text-decoration: none;
    display: block;
    font-weight: normal;
}
.dropdown-content a:hover {
    background-color: #f0f0f0;
}

/* Hamburger menu for mobile */
.hamburger {
    display: none;
    font-size: 24px;
    cursor: pointer;
}

.wishlist-link{
color:red;
text-decoration:none;
font-weight:bold;
}

.wishlist-link i{
color:red;
}

/* Mobile dropdown menu */
.mobile-menu {
    display: none;
    flex-direction: column;
    background-color: #fff;
    width: 100%;
    border-top: 1px solid #ccc;
     font-weight:bold; text-decoration:none;
}
.mobile-menu a {
    padding: 10px 20px;
    border-bottom: 1px solid #ccc;
    color: black;
}

/* Responsive */
@media screen and (max-width: 768px) {
    .search-container {
        order: 3;
        width: 100%;
        margin: 10px 0;
    }
    .menu {
        display: none;
    }
    .hamburger {
        display: block;
    }
}
</style>
</head>
<body>

<!-- Top red bar -->
<div class="navbar-top">
    <i class="fab fa-facebook-f"></i>
    <i class="fab fa-instagram"></i>
    <i class="fab fa-youtube"></i>
    <i class="fab fa-whatsapp"></i>
    SHIPPING ON ORDERS AT CHENNAI & BANGALORE
</div>

<!-- Main navbar -->
<div class="navbar-main">
    <div class="logo">THE <span>IT</span> STORE</div>

    <!-- <form class="search-container" method="GET" action="products.php">
        <input type="text" name="search" placeholder="Search products...">
        <button type="submit">Search</button>
    </form> -->
    <form method="GET"  class="search-container" action="products.php">

<input type="text" name="search"
placeholder="Search product..."
value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">

<button type="submit">Search</button>

</form>

    <div class="hamburger" onclick="toggleMobileMenu()"><i class="fas fa-bars"></i></div>

    <div class="menu" >
        <a href="#">All Departments</a>
        <a href="items-page.php">Home</a>
     <!-- <a href="chat.php" onclick="toggleChat()">💬 support</a> -->
          <a href="specials.php">Specials</a>
       <a href="help.php">Help / Support</a>
        <a href="chat.php">chat support </a>
       
       <a href="wishlist-page.php" class="wishlist-link">
<a href="wishlist-page.php" class="wishlist-link">
❤️ Wishlist (<?php echo $wishlist_count; ?>)
</a>

        <div class="sec-cart">
            <!-- <a href="cart.php"> <i class="fas fa-shopping-cart"></i> 0 item(s) - ₹0.00 </a> -->
             <a href="cart.php" style="font-weight:bold; text-decoration:none;">
🛒 Cart (<?php echo $cart_count; ?>)
</a>
       
   </div>
        <!-- Account Dropdown -->
        <div class="account-dropdown">
            <a  href="user-dashboard.php" style="font-size:18px;">
                <i class="fas fa-user-circle"></i> Account
            </a>
           <div class="dropdown-content">

<?php

if(isset($_SESSION['user_id'])){
?>

<a href="user-dashboard.php">Welcome <?php echo $_SESSION['user_name']; ?></a>
<a href="logout.php">Logout</a>

<?php
}else{
?>

<a href="login.php">Login</a>
<a href="register.php">Register</a>

<?php
}
?>

</div>
        </div>
    </div>
</div>

<!-- Mobile dropdown menu -->
<div class="mobile-menu" id="mobileMenu">
    <a href="#">All Departments</a>
    <a href="items-page.php">Home</a>
    <a href="help.php">Help / Support</a>
    <a href="#">Specials</a>
    <a href="customize-pc.php">Customize your PC</a>
      <a href="wishlist-page.php" class="wishlist-link">
<i class="fas fa-heart"></i> Wishlist (0)
</a>
    <!-- <a href="cart.php">0 item(s) - ₹0.00</a> -->
         <a href="cart.php" style="font-weight:bold; text-decoration:none;">
🛒 Cart (<?php echo $cart_count; ?>)
 <
            <a  href="user-dashboard.php" style="font-size:18px;">
                <i class="fas fa-user-circle"></i> Account
            </a>
        

</a>

    <a href="login.php">Login</a>
    <a href="register.php">Register</a>



    <div class="dropdown-content">

<?php
if(isset($_SESSION['user_id'])){
?>

<a href="user-dashboard.php">Welcome <?php echo $_SESSION['user_name']; ?></a>
<a href="logout.php">Logout</a>

<?php
}else{
?>

<a href="login.php">Login</a>
<a href="register.php">Register</a>

<?php
}
?>

</div>
</div>

<script>
function toggleMobileMenu() {
    var menu = document.getElementById("mobileMenu");
    if (menu.style.display === "flex") {
        menu.style.display = "none";
    } else {
        menu.style.display = "flex";
    }
}
</script>

</body>
</html> 




