<?php
include("db.php");
include("navbar.php");
if(isset($_POST['send'])){

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

mysqli_query($conn,"INSERT INTO support_messages(name,email,message) 
VALUES('$name','$email','$message')");

$success = "Message Sent Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Help Support</title>

<style>

body{
font-family:Arial;
background:#f5f5f5;
}

.container{
width:400px;
margin:auto;
margin-top:60px;
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 0 10px #ccc;
}

input,textarea{
width:100%;
padding:10px;
margin-top:10px;
border:1px solid #ccc;
border-radius:5px;
}

button{
margin-top:15px;
padding:10px;
background:#007bff;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
}

.success{
color:green;
}

</style>

</head>

<body>

<div class="container">

<h2>Help / Support</h2>

<?php if(isset($success)){ ?>
<p class="success"><?php echo $success; ?></p>
<?php } ?>

<form method="post">

<input type="text" name="name" placeholder="Your Name" required>

<input type="email" name="email" placeholder="Your Email" required>

<textarea name="message" placeholder="Your Problem" rows="5"></textarea>

<button name="send">Send Message</button>

</form>

</div>

</body>
</html>