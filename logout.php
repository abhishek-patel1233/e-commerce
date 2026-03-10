<?php
session_start();
session_destroy();
header("Location: items-page.php");
?>