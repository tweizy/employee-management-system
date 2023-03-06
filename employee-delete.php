<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once("dbconnect.php");

$sql = "DELETE FROM employees WHERE emp_no = ?";

if($stmt = $db->prepare($sql)){
    $stmt->bind_param("s", $_GET["emp_no"]);
    $stmt->execute();
    header("location: dashboard.php");
}
?>