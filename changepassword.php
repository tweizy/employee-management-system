<?php
require_once("dbconnect.php");
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$password1 = $password2 = "";
$password_error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    //check for empty field
    if(empty(trim($_POST["password1"]))){
        $password_error = "Please enter your password";
    }
    else{
        $password1 = trim($_POST["password1"]);
    }
    if(empty(trim($_POST["password2"]))){
        $password_error = "Please enter your password";
    }
    else{
        $password2 = trim($_POST["password2"]);
    }

    if(empty($password_error)){
        if($password1 === $password2){
            $query = "UPDATE dept_manager SET password = ? WHERE emp_no = ?";
            if($stmt = $db-> prepare($query)){
                $stmt-> bind_param("ss", $password1, $_SESSION["id"]);
                $stmt->execute();
                header("location: dashboard.php");
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <title>Change Passowrd</title>
</head>
<body>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="d-flex flex-column p-2 m-auto w-25 my-auto align-middle">
        <div class="form-group">
            <label for="exampleInputEmail1">New Password: </label>
            <input type="password" name="password1" class="form-control" placeholder="Enter Password">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Confirm Password: </label>
            <input type="password" name="password2" class="form-control" placeholder="Confirm Password">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <?php 
    if(!empty($password_error)){
        echo '<div class="alert alert-danger">' . $password_error . '</div>';
    }  
    ?>
</body>
</html>