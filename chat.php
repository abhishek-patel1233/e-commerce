<!DOCTYPE html>
<html>
<head>
<title>Chat Support</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#ece5dd;
}

/* Floating Button */
.chat-btn{
position:fixed;
bottom:20px;
right:20px;
background:#25d366;
color:white;
border:none;
padding:15px;
border-radius:50%;
font-size:20px;
cursor:pointer;
}

/* Chat Box */
.chat-box{
width:350px;
height:500px;
background:white;
position:fixed;
bottom:80px;
right:20px;
border-radius:10px;
display:none;
flex-direction:column;
box-shadow:0 0 10px #ccc;
}

/* Header */
.chat-header{
background:#075e54;
color:white;
padding:10px;
display:flex;
justify-content:space-between;
align-items:center;
}

/* Body */
.chat-body{
flex:1;
padding:10px;
overflow-y:auto;
}

/* Messages */
.msg-user{
background:#dcf8c6;
padding:8px;
margin:5px;
border-radius:5px;
text-align:right;
}

.msg-bot{
background:#f1f0f0;
padding:8px;
margin:5px;
border-radius:5px;
text-align:left;
}

/* Footer */
.chat-footer{
display:flex;
padding:10px;
border-top:1px solid #ddd;
}

.chat-footer input{
flex:1;
padding:5px;
}

.chat-footer button{
margin-left:5px;
}

</style>

</head>

<body>

<!-- Floating Button -->
<button class="chat-btn" onclick="toggleChat()">💬 support</button>

<!-- Chat Box -->
<div class="chat-box" id="chatBox">

<div class="chat-header">
<span>Chat Support 🤖</span>
<button onclick="toggleChat()" class="btn btn-sm btn-light">X</button>
</div>

<div class="chat-body" id="chatBody">
<div class="msg-bot">👋 Hello! How can I help you?</div>
</div>

<div class="chat-footer">
<input type="text" id="message" placeholder="Type message...">
<button class="btn btn-success" onclick="sendMessage()">Send</button>
</div>

</div>

<script>

function toggleChat(){
let box = document.getElementById("chatBox");
box.style.display = (box.style.display == "flex") ? "none" : "flex";
}

function sendMessage(){

let msg = document.getElementById("message").value;

if(msg.trim() == "") return;

let chatBody = document.getElementById("chatBody");

// show user message
chatBody.innerHTML += "<div class='msg-user'>"+msg+"</div>";

// AJAX request
let xhr = new XMLHttpRequest();
xhr.open("POST","chatbot.php",true);
xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");

xhr.onload = function(){
chatBody.innerHTML += "<div class='msg-bot'>"+this.responseText+"</div>";
chatBody.scrollTop = chatBody.scrollHeight;
}

xhr.send("message="+encodeURIComponent(msg));

// clear input
document.getElementById("message").value="";
}

</script>

</body>
</html>