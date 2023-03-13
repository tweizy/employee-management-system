<?php 
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
if(isset($_GET['page']) && !empty($_GET['page'])){
    $currentPage = (int) strip_tags($_GET['page']);
}else{
    $currentPage = 1;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/d644c28068.js" crossorigin="anonymous"></script>
    <style>
        .wrapper{
            width: 900px;
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

        .table-head{
            display: flex;
            justify-content: space-between;
            width: 1160px;
            align-items: center;
        }

        .table-head2{
            display: flex;
            justify-content: space-between;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="header sticky-top bg-primary">
        <h2 class="title">Employee Management System</h2>
        <div>
            <a href="logout.php" class="bg-danger"><i class="fa-solid fa-right-from-bracket fa-lg"></i>Logout</a>
            <a href="changepassword.php" class="bg-success"><i class="fa-solid fa-lock fa-lg"></i>Change Password</a>
        </div>
    </div>

    <div class="manager-info">
        <?php
        echo "<h6 style='margin-top: 30px; margin-left: 30px'>Hello ".$_SESSION["manager_fname"]." ".$_SESSION["manager_lname"]."</h6>";
        echo "<h6 style='margin-left: 30px'>ID: ".$_SESSION["id"]."</h6>";
        echo "<h6 style='margin-left: 30px'>Department: ".$_SESSION["department_name"]."</h6>";
        
        ?>
    </div>

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-15">
                    <div class="mt-5 mb-3 clearfix table-head">
                        <h2 class="pull-left">Employees Details</h2>
                        <div class="table-head2">
                            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                                <select name="emp_per_page" class="emp_det1">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <button style="width: 70px" type="submit" class="btn bg-primary text-light">Search</button>
                            </form>
                            <a href="employee-create.php" class="btn bg-success pull-right" style="color:#eeeee4; margin-left: 40px"><i class="fa fa-plus"></i> Add New Employee</a>
                        </div>
                    </div>
                </div>
                    <?php
                    
                    // Include config file
                    require_once "dbconnect.php";
                    $number = "SELECT * FROM employees INNER JOIN titles INNER JOIN dept_emp  on employees.emp_no = titles.emp_no AND employees.emp_no = dept_emp.emp_no WHERE titles.to_date = ? AND dept_emp.dept_no = ?";

                    // On prépare la requête
                    $query = $db->prepare($number);

                    $dt = "9999-01-01";
                    $query->bind_param('ss', $dt, $_SESSION["department"]);

                    // On exécute
                    $query->execute();

                    // On récupère le nombre d'articles
                    $result = $query->get_result();

                    $nbEmp= $result->num_rows;

                    $limit = isset($_GET["limit"]) ? $_GET["limit"] : 10;
                    // On détermine le nombre d'articles par page
                    $parPage = isset($_POST["emp_per_page"]) ? $_POST["emp_per_page"] : $limit;

                    // On calcule le nombre de pages total
                    $pages = ceil($nbEmp / $parPage);

                    // Calcul du 1er article de la page
                    $premier = ($currentPage * $parPage) - $parPage;
                    // Attempt select query execution
                    $sql = "SELECT * FROM employees INNER JOIN titles INNER JOIN dept_emp on employees.emp_no = titles.emp_no AND employees.emp_no = dept_emp.emp_no  WHERE titles.to_date = ? AND dept_emp.dept_no = ? LIMIT ?, ?";
                    // $result->num_rows;

                    if ($stmt = $db->prepare($sql)){
                        $dt = "9999-01-01";
                        $stmt->bind_param('ssii', $dt, $_SESSION["department"], $premier, $parPage);
                        if($stmt->execute()){
                            $result = $stmt->get_result();
                            if($result->num_rows > 0){
                                echo '<table class="table table-bordered table-striped">';
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th>Emp No</th>";
                                            echo "<th>Birth date</th>";
                                            echo "<th>First Name</th>";
                                            echo "<th>Last Name</th>";
                                            echo "<th>Gender</th>";
                                            echo "<th>Hire Date</th>";
                                            echo "<th>Update</th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($row = $result->fetch_array()){
                                        echo "<tr>";
                                            echo "<td>" . $row['emp_no'] . "</td>";
                                            echo "<td>" . $row['birth_date'] . "</td>";
                                            echo "<td>" . $row['first_name'] . "</td>";
                                            echo "<td>" . $row['last_name'] . "</td>";
                                            echo "<td>" . $row['gender'] . "</td>";
                                            echo "<td>" . $row['hire_date'] . "</td>";
                                            echo "<td>";
                                                echo '<a href="employee-details.php?emp_no='. $row['emp_no'] .'" class="mr-3" title="View Employee Details" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                                echo '<a href="employee-update.php?emp_no='. $row['emp_no'] .'" class="mr-3" title="Update Employee" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                                echo '<a href="" data-bs-toggle="modal" data-bs-target="#exampleModal" title="Delete Employee" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#exampleModal"><span class="fa fa-trash"></span></a>';
                                                ?>
                                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Deletion Confirmation</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Do you really want to delete this employee ? <br> This action is irreversible
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <a href="employee-delete.php?emp_no=<?php echo $row['emp_no']?>"><button type="button" class="btn btn-primary">Confirm</button></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
    
                                            <?php
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";                            
                                echo "</table>";
                                // Free result set
                                $result->free();
                            } else{
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }

                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    
                    // Close connection
                    $db->close();
                    ?>
                </div>
                <nav>
                    <ul class="pagination">
                        <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                            <a href="dashboard.php?page=<?= $currentPage - 1 ?>&limit=<?= $parPage ?>" class="page-link">Previous</a>
                        </li>
                        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                            <a href="" class="page-link">Page : <?php echo $currentPage ?></a>
                        </li>
                          <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                          <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                            <a href="dashboard.php?page=<?= $currentPage + 1 ?>&limit=<?= $parPage ?>" class="page-link">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>        
        </div>
    </div>
</body>
</html>