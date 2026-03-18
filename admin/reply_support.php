<?php
session_start();
include("../db.php");

$id = $_GET['id'];

if(isset($_POST['reply'])){

$reply = $_POST['reply_text'];

mysqli_query($conn,"UPDATE support_messages 
SET reply='$reply' WHERE id='$id'");

header("Location: support.php");
}

$data = mysqli_query($conn,"SELECT * FROM support_messages WHERE id='$id'");
$row = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html>
<head>
<title>Reply Support</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-dark text-white">
<h4>Reply to User</h4>
</div>

<div class="card-body">

<p><b>Name:</b> <?php echo $row['name']; ?></p>
<p><b>Email:</b> <?php echo $row['email']; ?></p>
<p><b>Message:</b> <?php echo $row['message']; ?></p>

<form method="post">

<div class="mb-3">
<label class="form-label">Reply</label>
<textarea name="reply_text" class="form-control" rows="5"></textarea>
</div>

<button name="reply" class="btn btn-success">Send Reply</button>
<a href="support.php" class="btn btn-secondary">Back</a>

</form>

</div>

</div>

</div>

</body>
</html>