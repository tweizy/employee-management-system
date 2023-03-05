<?php 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db = new mysqli(
    "localhost",
    "root",
    "azerty",
    "employees",
    3306);
if($db-> connect_errno){
    echo 'Error connecting to database';
    echo 'error N: '.$db-> connect_errno.'<br>';
    echo 'message: '.$db-> connect_error;
    return false;
}
else{
    return $db;
}
?>