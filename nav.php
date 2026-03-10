<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<nav>

<div class="logo">
    IT Depot
</div>

<div class="menu">

<?php
if(isset($_SESSION['user_id'])){
?>

<span>Welcome <?php echo $_SESSION['user_name']; ?></span>
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

</nav>