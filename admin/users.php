
<?php
session_start();
include("../db.php");

/* Admin login check */

if(!isset($_SESSION['admin'])){
header("Location: admin_login.php");
exit();
}

/* Delete User */

if(isset($_GET['delete'])){
$id = $_GET['delete'];

mysqli_query($conn,"DELETE FROM users WHERE id='$id'");

header("Location: users.php");
exit();
}

/* Fetch Users */

$sql = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>

<head>

<title>Manage Users</title>

<style>

body{
font-family:Arial;
background:#f5f5f5;
margin:0;
}

/* Navbar */

.admin-nav{
background:#222;
padding:15px;
display:flex;
justify-content:space-between;
align-items:center;
}

.admin-nav a{
color:white;
text-decoration:none;
margin-right:20px;
font-weight:bold;
}

.admin-nav a:hover{
color:#4CAF50;
}

.container{
width:1100px;
margin:40px auto;
}

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

th,td{
padding:10px;
border-bottom:1px solid #ddd;
text-align:center;
}

th{
background:#4CAF50;
color:white;
}

tr:hover{
background:#f2f2f2;
}

.delete-btn{
background:#dc3545;
color:white;
padding:5px 10px;
text-decoration:none;
border-radius:4px;
}

.delete-btn:hover{
background:#c82333;
}

</style>

</head>

<body>

<div class="admin-nav">

<div style="color:white;font-size:20px;font-weight:bold;">
Admin Panel
</div>

<div>
<a href="admin_panel.php">Dashboard</a>
<a href="orders.php">Orders</a>
<a href="products.php">Products</a>
<a href="users.php">Users</a>
<a href="logout.php">Logout</a>
</div>

</div>


<div class="container">

<h2>All Users</h2>

<table>

<tr>
<th>ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Email</th>
<th>Phone</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['fname']; ?></td>

<td><?php echo $row['lname']; ?></td>

<td><?php echo $row['email']; ?></td>

<td><?php echo $row['phone']; ?></td>

<td>

<a href="users.php?delete=<?php echo $row['id']; ?>" 
class="delete-btn"
onclick="return confirm('Delete this user?')">

Delete

</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>
```
