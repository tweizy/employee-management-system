<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

echo "You are logged in, this is the dashboard.<br>";
echo "Your ID is: ".$_SESSION["id"]."<br>";
?>
<a href="logout.php">Logout</a>
<br>
<a href="changepassword.php">Change password</a>
<br>
<a href="test_dashboard.php">Go to test dashboard</a>