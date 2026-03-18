<?php
include("db.php");

if(isset($_POST['message'])){

$msg = strtolower(trim($_POST['message']));
$reply = "";

// Auto replies
if(strpos($msg, "order") !== false){
    $reply = "📦 Please check your order in My Orders section.";
}
elseif(strpos($msg, "payment") !== false){
    $reply = "💳 Payment issue? Try again or use another method.";
}
elseif(strpos($msg, "delivery") !== false){
    $reply = "🚚 Delivery takes 3-5 working days.";
}
elseif(strpos($msg, "refund") !== false){
    $reply = "💰 Refund will be processed within 5-7 days.";
}
else{
    $reply = "🤖 Sorry, I didn't understand. Please contact support.";
}

// Save chat
mysqli_query($conn,"INSERT INTO chatbot_messages(user_message, bot_reply)
VALUES('$msg','$reply')");

// Return reply
echo $reply;

}
?>