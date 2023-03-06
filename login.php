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
    <style media="screen">
      *,
*:before,
*:after{
    padding: 0;
    margin: 0;
    box-sizing: border-box;
}
body{
    background-color: #080710;
}
.background{
    width: 430px;
    height: 520px;
    position: absolute;
    transform: translate(-50%,-50%);
    left: 50%;
    top: 50%;
}
.background .shape{
    height: 200px;
    width: 200px;
    position: absolute;
    border-radius: 50%;
}
.shape:first-child{
    background: linear-gradient(
        #1845ad,
        #23a2f6
    );
    left: -80px;
    top: -80px;
}
.shape:last-child{
    background: linear-gradient(
        to right,
        #ff512f,
        #f09819
    );
    right: -30px;
    bottom: -80px;
}
form{
    height: 500px;
    width: 400px;
    background-color: rgba(255,255,255,0.13);
    position: absolute;
    transform: translate(-50%,-50%);
    top: 50%;
    left: 50%;
    border-radius: 10px;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255,255,255,0.1);
    box-shadow: 0 0 40px rgba(8,7,16,0.6);
    padding: 50px 35px;
}
form *{
    font-family: 'Poppins',sans-serif;
    color: #ffffff;
    letter-spacing: 0.5px;
    outline: none;
    border: none;
}
form h3{
    font-size: 32px;
    font-weight: 500;
    line-height: 42px;
    text-align: center;
}

label{
    display: block;
    margin-top: 30px;
    font-size: 16px;
    font-weight: 500;
}
input{
    display: block;
    height: 50px;
    width: 100%;
    background-color: rgba(255,255,255,0.07);
    border-radius: 3px;
    padding: 0 10px;
    margin-top: 8px;
    font-size: 14px;
    font-weight: 300;
}
::placeholder{
    color: #e5e5e5;
}
button{
    margin-top: 50px;
    width: 100%;
    background-color: #ffffff;
    color: #080710;
    padding: 15px 0;
    font-size: 18px;
    font-weight: 600;
    border-radius: 5px;
    cursor: pointer;
}
.social{
  margin-top: 30px;
  display: flex;
}
.social div{
  background: red;
  width: 150px;
  border-radius: 3px;
  padding: 5px 10px 10px 5px;
  background-color: rgba(255,255,255,0.27);
  color: #eaf0fb;
  text-align: center;
}
.social div:hover{
  background-color: rgba(255,255,255,0.47);
}

    </style>
</head>
<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
    <h3>Login Here</h3>
        <div>
            <label for="username">Id: </label>
            <input type="text" name="id" id="username" placeholder="Enter Id">
        </div>
        <div>
            <label for="password">Password: </label>
            <input type="password" name="password" id="password" placeholder="Enter password">
        </div>
        <button type="submit">Log In</button>
        <?php 
        if(!empty($login_error)){
            echo '<div class="alert alert-danger">' . $login_error . '</div>';
        }        
        ?>
    </form>
    
</body>
</html>