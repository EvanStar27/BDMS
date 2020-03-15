<?php
session_start();
require_once "../includes/DbOperations.php";
$conn = new DbOperations();

$asid = $_SESSION["asid"];
$studentsIdStatus = $_SESSION["studentsIdStatus"];
$studentsIdStatusUpdate = $_SESSION["studentsIdStatusUpdate"];

$errors = array();

foreach ($studentsIdStatusUpdate as $sid => $status) {

    if ($studentsIdStatus[$sid] == $status)
        continue;

    $query = "UPDATE attendance_records 
        SET status='$status'
        WHERE asheet_id='$asid' and student_id='$sid'";

    if (!($conn->execute($query))) {
        $errors[$sid] = "Couldn't update status for student id $sid: " . mysqli_error();
    }
}

if (!empty($errors)) {
    $_SESSION["errors"] = $errors;
} else {
    unset($_SESSION["asid"]);
    unset($_SESSION["studentsIdStatus"]);
    unset($_SESSION["studentsIdStatusUpdate"]);
    $_SESSION["status"] = "Update Successful";
}

header("location: ../edit-attendance.php?asid=$asid");
?>