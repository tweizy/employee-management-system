<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/d644c28068.js" crossorigin="anonymous"></script>
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
        .header{
            height:80px;
            /* background-color: #2596be; */
            display: flex;
            align-items: center;
            color: #eeeee4;
            justify-content: space-between;
            padding-left: 20px;
            padding-right: 20px;
        }
        i{
            margin-right: 10px;
            margin-left: 20px;
        }
        .header > div > a{
            background-color: black;
            padding: 12px;
            padding-left: 0px;
            color: #eeeee4;
            border-radius: 10px;
            border: 0;
            margin-left: 20px;
            text-decoration: none;
        }

    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
<div class="header bg-primary">
        <h2 class="title">Employee Management System</h2>
        <div>
            <a href="logout.php" class="bg-danger"><i class="fa-solid fa-right-from-bracket fa-lg"></i>Logout</a>
            <a href="changepassword.php" class="bg-success"><i class="fa-solid fa-lock fa-lg"></i>Change Password</a>
            <a href="dashboard.php" class = "bg-success"><i class ="fa-solid fa-home fa-lg"></i>Dashboard</a>
        </div>
    </div>

    <div class="manager-info">
        <?php
        echo "<h6 style='margin-top: 30px; margin-left: 30px'>Hello ".$_SESSION["manager_fname"]." ".$_SESSION["manager_lname"]."</h6>";
        echo "<h6 style='margin-left: 30px'>ID: ".$_SESSION["id"]."</h6>";
        echo "<h6 style='margin-left: 30px'>Department: ".$_SESSION["department"]."</h6>";

        ?>
    </div>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Employee Details</h2>
                    </div>
                    <?php
                    // Include config file
                    require_once "dbconnect.php";
                    
                    // Attempt select query execution
                    $dt = "9999-01-01"; 
                    $sql = "SELECT * FROM employees INNER JOIN salaries INNER JOIN titles on employees.emp_no = titles.emp_no AND employees.emp_no = salaries.emp_no WHERE employees.emp_no = ? AND titles.to_date = ?";
                    if($stmt = $db->prepare($sql)){
                        
                        $stmt->bind_param("ss", $_GET["emp_no"], $dt);
                        if($stmt->execute()){
                            
                            $result = $stmt->get_result();
                            if($result->num_rows > 0){
                                $row = $result->fetch_assoc();
                                echo '<table class="table table-bordered table-striped">';
                                echo "<tbody>";
                                                    
                                                        echo "<tr>";
                                                            echo "<th>Emp No</th>";
                                                            echo '<td>'.$row['emp_no'].'</td>';
                                                        echo "</tr>";
                                                        echo "<tr>";
                                                            echo "<th>First Name</th>";
                                                            echo '<td>'.$row['first_name'].'</td>';
                                                        echo "</tr>";                                                   
                                                        echo "<tr>";
                                                            echo "<th>Last name</th>";
                                                            echo '<td>'.$row['last_name'].'</td>';
                                                        echo "</tr>";
                                                        echo "<tr>";
                                                            echo "<th>gender</th>";
                                                            echo '<td>'.$row['gender'].'</td>';
                                                        echo "</tr>";                                           
                                                        echo "<tr>";
                                                            echo "<th>Birth date</th>";
                                                            echo '<td>'.$row['birth_date'].'</td>';
                                                        echo "</tr>";
                                                        echo "<tr>";
                                                            echo "<th>Hire Date</th>";
                                                            echo '<td>'.$row['hire_date'].'</td>';
                                                        echo "</tr>";
                                                        echo "<tr>";
                                                            echo "<th>Title</th>";
                                                            echo '<td>'.$row['title'].'</td>';
                                                        echo "</tr>";
                                                        echo "<tr>";
                                                            echo "<th>Salary</th>";
                                                            echo '<td>'.$row['salary'].' $'.'</td>';
                                                        echo "</tr>";
                                                        echo "<tr>";
                                                            echo "<th>Department Number</th>";
                                                            echo '<td>'.$_SESSION["department"].'</td>';
                                                        echo "</tr>";                                              
                                echo "</tbody>";
                                                       
                                                    
                            }
                        }
                    }
                    $db->close();
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>