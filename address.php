<?php
session_start();
include("db.php");

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Handle form submission
if(isset($_POST['submit_address'])){
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $address_text = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $pincode = $_POST['pincode'] ?? '';

    // Basic validation
    if(empty($name) || empty($phone) || empty($address_text) || empty($city) || empty($pincode)){
        $error = "Please fill all required fields.";
    } else {
        // Insert into address table
        $stmt = mysqli_prepare($conn, "INSERT INTO address (user_id, name, phone, email, address, city, pincode) VALUES (?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, "issssss", $user_id, $name, $phone, $email, $address_text, $city, $pincode);
        mysqli_stmt_execute($stmt);

        // Get inserted address ID
        $address_id = mysqli_insert_id($conn);
        $_SESSION['address_id'] = $address_id;

        // Redirect to payment page
        header("Location: payment.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shipping Address</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{background:#f4f4f4;font-family:Arial;}
        .container{max-width:600px;margin:50px auto;background:#fff;padding:30px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,0.1);}
        h3{margin-bottom:20px;}
    </style>
</head>
<body>
<div class="container">
    <h3>Shipping Address</h3>
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Name *</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Phone *</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label>Address *</label>
            <textarea name="address" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label>City *</label>
            <input type="text" name="city" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Pincode *</label>
            <input type="text" name="pincode" class="form-control" required>
        </div>
        <button type="submit" name="submit_address" class="btn btn-success w-100">Continue to Payment</button>
    </form>
</div>
</body>
</html>