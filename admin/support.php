<?php
session_start();
include("../db.php");
include("admin_navbar.php");
if(!isset($_SESSION['admin'])){
header("Location: admin_login.php");
exit();
}

$result = mysqli_query($conn,"SELECT * FROM support_messages ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Support Messages</title>

<style>

table{
width:100%;
border-collapse:collapse;
}

th,td{
border:1px solid #ddd;
padding:10px;
}

th{
background:#333;
color:white;
}

a{
background:#28a745;
color:white;
padding:5px 10px;
text-decoration:none;
}

</style>

</head>

<body>

<h2>Support Messages</h2>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Message</th>
<th>Reply</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['message']; ?></td>
<td><?php echo $row['reply']; ?></td>

<td>
<a href="reply_support.php?id=<?php echo $row['id']; ?>">Reply</a>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>