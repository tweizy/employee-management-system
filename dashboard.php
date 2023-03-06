<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// $query = "SELECT emp_no, password FROM dept_manager WHERE emp_no = ?";
// $stmt = $db-> prepare($query);

// $stmt->bind_param("s", $_SESSION["id"]);
// $stmt->execute();

// $result = $stmt->get_result();

// foreach($result as $row){
//     echo $row["password"];
// }

echo "You are logged in, this is the dashboard.<br>";
echo "Your ID is: ".$_SESSION["id"]."<br>";
?>
<a href="logout.php">Logout</a>