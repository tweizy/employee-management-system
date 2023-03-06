<?php 
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: dashboard.php");
    exit;
}

require_once("dbconnect.php");

$id = $password = "";
$id_error = $password_error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    //check for empty id or password
    if(empty(trim($_POST["id"]))){
        $id_error = "Please enter your Id";
    }
    else{
        $id = trim($_POST["id"]);
    }
    if(empty(trim($_POST["password"]))){
        $password_error = "Please enter your password";
    }
    else{
        $password = trim($_POST["password"]);
    }

    //validate credentials entered
    if(empty($id_error) && empty($password_error)){
        $query = "SELECT emp_no, password FROM dept_manager WHERE emp_no = ?";
        if($stmt = $db-> prepare($query)){
            $stmt-> bind_param("s", $id);
            if($stmt-> execute()){
                $result = $stmt-> get_result();
                if($result-> num_rows == 1){
                    $row = $result->fetch_assoc();
                    $real_password = $row["password"];
                    if($password === $real_password){
                        session_start();

                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;

                        header("location: dashboard.php");
                    }
                    else{
                        $login_error = "Invalid username or password";
                    }
                }
            }
            else{
                $login_error = "Invalid username or password";
            }
        }
        else{
            echo "Oops, something went wrong";
        }
        $stmt-> close();
    }
    $db-> close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Log In</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="d-flex flex-column p-2 m-auto w-25 my-auto align-middle">
        <div class="form-group">
            <label for="exampleInputEmail1">Id: </label>
            <input type="text" name="id" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Id">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password: </label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Enter password">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <?php 
    if(!empty($login_error)){
        echo '<div class="alert alert-danger">' . $login_error . '</div>';
    }        
    ?>
</body>
</html>