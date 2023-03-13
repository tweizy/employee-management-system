<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once("dbconnect.php");
$current_date = strval(date('y-m-d'));
$employees_insert = "INSERT INTO employees (emp_no, birth_date, first_name, last_name, gender, hire_date) VALUES (?, ?, ?, ?, ?, curdate())";

$salary_insert = "INSERT INTO salaries (emp_no, salary, from_date, to_date) VALUES (?, ?, curdate(), '9999-01-01')";

$dept_insert = "INSERT INTO dept_emp (emp_no, dept_no, from_date, to_date) VALUES (?, ?, curdate(), '9999-01-01')";

$title_insert = "INSERT INTO titles (emp_no, title, from_date, to_date) VALUES (?, ?, curdate(), '9999-01-01')";

if(($employees_insert = $db->prepare($employees_insert)) && 
   ($salary_insert = $db->prepare($salary_insert)) && 
   ($dept_insert = $db->prepare($dept_insert)) && 
   ($title_insert = $db->prepare($title_insert))){

        $employees_insert->bind_param("sssss", $_POST["employee-number"],$_POST["birthdate"], $_POST["first_name"], $_POST["last_name"], $_POST["gender"]);
        $salary_insert->bind_param("ss", $_POST["employee-number"], $_POST["salary"]);
        $dept_insert->bind_param("ss", $_POST["employee-number"], $_SESSION["department"]);
        $title_insert->bind_param("ss", $_POST["employee-number"], $_POST["title"]);
        $employees_insert->execute();
        $salary_insert->execute();
        $dept_insert->execute();
        $title_insert->execute();
        header("location: dashboard.php");
}
?>

