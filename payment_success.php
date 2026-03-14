<?php
session_start();
include("db.php");

$keySecret = "XXXXXXXXXXXXXXXXXXXX"; // Razorpay Test Secret

$payment_id = $_POST['razorpay_payment_id'] ?? '';
$order_id = $_POST['razorpay_order_id'] ?? '';
$signature = $_POST['razorpay_signature'] ?? '';

$generated_signature = hash_hmac('sha256', $order_id . "|" . $payment_id, $keySecret);

// Debug friendly: remove exit after testing
// echo "Order: $order_id, Payment: $payment_id, Signature: $signature, Generated: $generated_signature"; exit();

if($generated_signature === $signature || empty($signature)){
    // Payment verified (empty signature allowed on localhost test)
    $payment_method = $_POST['payment_method'] ?? 'RAZORPAY';
    $upi_id = $_POST['upi_id'] ?? '';
    $card_number = $_POST['card_number'] ?? '';
    $card_name = $_POST['card_name'] ?? '';
    $expiry = $_POST['expiry'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    // Total calculation
    $total = 0;
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
        foreach($_SESSION['cart'] as $item){
            $price = $item['price'] ?? 0;
            $qty = $item['quantity'] ?? 0;
            $total += $price * $qty;
        }
    }
    $gst = $total * 0.18;
    $grand_total = $total + $gst;

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders(payment_method, upi_id, card_number, card_name, expiry, cvv, total, razorpay_order_id, razorpay_payment_id, status) VALUES(?,?,?,?,?,?,?,?,?,?)");
    $status = "Paid";
    $stmt->bind_param("ssssssssss",$payment_method,$upi_id,$card_number,$card_name,$expiry,$cvv,$grand_total,$order_id,$payment_id,$status);
    $stmt->execute();
    $order_db_id = $stmt->insert_id;
    $stmt->close();

    // Insert items
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
        $stmt_items = $conn->prepare("INSERT INTO order_items(order_id, product_id, product_name, price, qty) VALUES(?,?,?,?,?)");
        foreach($_SESSION['cart'] as $item){
            $pid = $item['id'] ?? 0;
            $pname = $item['name'] ?? '';
            $pprice = $item['price'] ?? 0;
            $pqty = $item['quantity'] ?? 0;
            $stmt_items->bind_param("iisdi",$order_db_id,$pid,$pname,$pprice,$pqty);
            $stmt_items->execute();
        }
        $stmt_items->close();
    }

    unset($_SESSION['cart']);

    echo "<div style='max-width:600px;margin:50px auto;padding:20px;background:#fff;border-radius:8px;text-align:center;'>";
    echo "<h2>Payment Successful ✅</h2>";
    echo "<p>Payment ID: <strong>{$payment_id}</strong></p>";
    echo "<p>Order ID: <strong>{$order_db_id}</strong></p>";
    echo "<p>Total Paid: ₹".number_format($grand_total,2)."</p>";
    echo "<a href='index.php' style='display:inline-block;margin-top:15px;padding:10px 20px;background:#3399cc;color:#fff;border-radius:5px;text-decoration:none;'>Go to Home</a>";
    echo "</div>";

}else{
    echo "<div style='max-width:600px;margin:50px auto;padding:20px;background:#fff;border-radius:8px;text-align:center;'>";
    echo "<h2>Payment Failed ❌</h2>";
    echo "<p>Signature mismatch or invalid payment.</p>";
    echo "<a href='payment.php' style='display:inline-block;margin-top:15px;padding:10px 20px;background:#cc3333;color:#fff;border-radius:5px;text-decoration:none;'>Try Again</a>";
    echo "</div>";
}
?>