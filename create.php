<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once("dbconnect.php");



$title_sql = "INSERT INTO employees (emp_no, birth_date, first_name, last_name, gender, hire_date) VALUES (?, ?, ?, ?, ?, ?)";
$salary_sql = "UPDATE salaries SET salary = ? WHERE emp_no = ?";
$dept_sql = "UPDATE dept_emp SET dept_no = ? WHERE emp_no = ?";

if(($title_sql = $db->prepare($title_sql)) && ($salary_sql = $db->prepare($salary_sql)) && ($dept_sql = $db->prepare($dept_sql))){
    $title_sql->bind_param("ss", $_POST["title"],$_GET["emp_no"]);
    $salary_sql->bind_param("ss", $_POST["salary"],$_GET["emp_no"]);
    $dept_sql->bind_param("ss", $_POST["dept"],$_GET["emp_no"]);
    $title_sql->execute();
    $salary_sql->execute();
    $dept_sql->execute();
    header("location: dashboard.php");
}
?>

