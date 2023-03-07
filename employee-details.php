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
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
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