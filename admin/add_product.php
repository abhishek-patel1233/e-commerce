<?php
session_start();
include("../db.php"); // Make sure path is correct
include("admin_navbar.php");
// Check Admin Session
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

// Handle form submission
if(isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $category = trim($_POST['category']);
    $brand = trim($_POST['brand']);
    $description = trim($_POST['description']);

    // Handle image upload
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_folder = "../images/" . $image_name;

        // Make sure images folder exists
        if(!is_dir("../images")) {
            mkdir("../images", 0755, true);
        }

        if(move_uploaded_file($image_tmp, $image_folder)) {
            // Prepare SQL to prevent injection
            $stmt = $conn->prepare("INSERT INTO products (name, price, image, category, brand, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdssss", $name, $price, $image_name, $category, $brand, $description);

            if($stmt->execute()) {
                $success = "Product added successfully!";
            } else {
                $error = "Database error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error = "Failed to upload image.";
        }
    } else {
        $error = "Please select an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- optional -->
</head>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f7f7f7;
    margin: 0;
    padding: 0;
}

h1 {
    text-align: center;
    color: #333;
    margin-top: 30px;
}

form {
    background-color: #fff;
    max-width: 500px;
    margin: 40px auto;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
}

input[type="text"],
input[type="number"],
input[type="file"],
textarea {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 14px;
    transition: border-color 0.3s;
}

input[type="text"]:focus,
input[type="number"]:focus,
textarea:focus,
input[type="file"]:focus {
    border-color: #007bff;
    outline: none;
}

textarea {
    resize: vertical;
    min-height: 100px;
}

button {
    background-color: #007bff;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #0056b3;
}

p {
    text-align: center;
    font-size: 14px;
}

p[style*="green"] {
    color: green;
}

p[style*="red"] {
    color: red;
}
</style>
<body>
    <h1>Add Product</h1>

    <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Product Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Price:</label><br>
        <input type="number" name="price" step="0.01" required><br><br>

        <label>Category:</label><br>
        <input type="text" name="category"><br><br>

        <label>Brand:</label><br>
        <input type="text" name="brand"><br><br>

        <label>Description:</label><br>
        <textarea name="description"></textarea><br><br>

        <label>Product Image:</label><br>
        <input type="file" name="image" accept="image/*" required><br><br>

        <button type="submit" name="add_product">Add Product</button>
    </form>
</body>
</html>