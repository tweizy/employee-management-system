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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Employee</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/d644c28068.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style_test.css">

    <style>
        .hidden_text{
            display : none
        }
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
    <?php
    require_once("dbconnect.php");

    $sql = "SELECT * FROM employees INNER JOIN salaries INNER JOIN titles on employees.emp_no = titles.emp_no AND employees.emp_no = salaries.emp_no WHERE employees.emp_no = ? AND titles.to_date = '9999-01-01'";
                        if($stmt = $db->prepare($sql)){
                            
                            $stmt->bind_param("s", $_GET["emp_no"]);
                            if($stmt->execute()){
                               
                                $result = $stmt->get_result();
                                if($result->num_rows > 0){
                                    $row = $result->fetch_assoc();
                                    $title = $row["title"];
                                    $salary = $row["salary"];
                                    $first_name = $row["first_name"];
                                    $last_name = $row['last_name'];
                                    $gender = $row['gender'];
                                                                }
                            }
                        }
    ?>
    <form action="update.php?emp_no=<?php echo $_GET["emp_no"]?>" method="POST" style="display: flex; flex-direction: column; width: 500px; margin: auto">
        <h3 style="margin-top: 100px">Update employee: <?php echo $_GET["emp_no"]?></h3>

        <div class="form-group">
            <label for="first_name">First Name: </label>
            <input class="form-control"type="text" name="fisrt_name" value="<?php echo $first_name ?>" disabled>
        </div>

        <div class="form-group">
            <label for="salary">Last Name: </label>
            <input class="form-control"type="text" name="last_name" value="<?php echo $last_name ?>" disabled>
        </div>

        <div class="form-group">
            <label for="salary">Gender: </label>
            <input class="form-control"type="text" name="gender" value="<?php echo $gender ?>" disabled>
        </div>

        <div class="form-group">
            <label for="exampleInputEmail1">Title:</label>
            <select name="title" class="form-control" required>
                <option value="<?php echo $title ?>" default><?php echo $title ?></option>
                <option value="Engineer" class = " <?= ("Engineer" == $title)? "hidden_text" : "" ?>">Engineer</option>
                <option value="Senior-Engineer" class = "<?= ("Senior Engineer" == $title)?"hidden_text" : "" ?>">Senior Engineer</option>
                <option value="Senior-Staff" class = "<?= ("Senior Staff" == $title)?"hidden_text" : "" ?>">Senior Staff</option>
                <option value="Staff" class = "<?= ("Staff" == $title)?"hidden_text" : "" ?>">Staff</option>
                <option value="Assistant-Engineer" class = "<?= ("Assistant Engineer" == $title)?"hidden_text" : "" ?>">Assistant Engineer</option>
                <option value="Technique-Leader" class = "<?= ("Technique Leader" == $title)?"hidden_text" : "" ?>">Technique Leader</option>
                <option value="Manager" class = "<?= ("Manager" == $title)?"hidden_text" : "" ?>">Manager</option>
            </select>
        </div>

        <div class="form-group">
            <label for="salary">Salary: </label>
            <input class="form-control"type="number" name="salary" value="<?php echo $salary ?>" required>
        </div>

        <div class="form-group">
            <label for="dept">Department:</label>
            <select class = "form-control"name="dept" required>
                <option value="<?php echo $_SESSION['department']?>" default><?php echo $_SESSION['department_name'] ?></option>
                <option value="d009" class = " <?= ("d009" == $_SESSION['department'])? "hidden_text" : "" ?>">Customer Service</option>
                <option value="d005" class = "<?= ("d005" == $_SESSION['department'])?"hidden_text" : "" ?>">Development</option>
                <option value="d002" class = "<?= ("d002" == $_SESSION['department'])?"hidden_text" : "" ?>">Finance</option>
                <option value="d003" class = "<?= ("d003" == $_SESSION['department'])?"hidden_text" : "" ?>">Human Resources</option>
                <option value="d001" class = "<?= ("d001" == $_SESSION['department'])?"hidden_text" : "" ?>">Marketing</option>
                <option value="d004" class = "<?= ("d004" == $_SESSION['department'])?"hidden_text" : "" ?>">Production</option>
                <option value="d006" class = "<?= ("d006" == $_SESSION['department'])?"hidden_text" : "" ?>">Quality Managmeent</option>
                <option value="d008" class = "<?= ("d008" == $_SESSION['department'])?"hidden_text" : "" ?>">Research</option>
                <option value="d007" class = "<?= ("d007" == $_SESSION['department'])?"hidden_text" : "" ?>">Sales</option>
            </select>
        </div>



        <button class="form-control bg-primary text-light" type="submit">Update</button>
    </form>
</body>
</html>