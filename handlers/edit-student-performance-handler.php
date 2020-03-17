<?php
session_start();

require_once "../includes/DbOperations.php";
$conn = new DbOperations();

if (!isset($_SESSION["teachers-login-status"]) and $_SESSION["teachers-login-status"] != true) {
    header("location: teachers-index.php");
}

print_r($_SESSION);
$sid = $_SESSION["sid"];
$subject_id = $_SESSION["subject_id"];
$fname = $_SESSION["fname"];
$subject = $_SESSION["subject_name"];
$semester = $_SESSION["semester"];

// Marks
if (!empty($_SESSION["test1"])) {
    if (preg_match("/^[0-9(ABS)]{0,3}$/", $_SESSION["test1"]))
        $test1 = $_SESSION["test1"];
    else
        $errors["1st Test"] = "Please input a number or ABS for absent";
} else {
    $test1 = NULL;
}

if (!empty($_SESSION["test2"])) {
    if (preg_match("/^[0-9(ABS)]{0,3}$/", $_SESSION["test2"]))
        $test2 = $_SESSION["test2"];
    else
        $errors["2nd Test"] = "Please input a number or ABS for absent";
} else {
    $test2 = NULL;
}

if (!empty($_SESSION["test3"])) {
    if (preg_match("/^[0-9(ABS)]{0,3}$/", $_SESSION["test3"]))
        $test3 = $_SESSION["test3"];
    else
        $errors["3rd Test"] = "Please input a number or ABS for absent";
} else {
    $test3 = NULL;
}

if (!empty($_SESSION["assignment"])) {
    if (preg_match("/^[0-9(ABS)]{0,3}$/", $_SESSION["assignment"]))
        $assignment = $_SESSION["assignment"];
    else
        $errors["Assignment"] = "Please input a number or ABS for absent";
} else {
    $assignment = NULL;
}

$query = "SELECT * FROM students_performance
    WHERE student_id='$sid' and subject_id='$subject_id'";

if ($conn->exists($query)) {
    $query = "UPDATE students_performance SET ";

    $query .= $test1 == NULL ? "test1=NULL," : "test1='$test1',";
    $query .= $test2 == NULL ? "test2=NULL," : "test2='$test2',";
    $query .= $test3 == NULL ? "test3=NULL," : "test3='$test3',";
    $query .= $assignment == NULL ? "assignment=NULL" : "assignment='$assignment'";

    if ($conn->execute($query)) {
        $_SESSION["status"] = "Successfully updated student performance";
    } else {
        $errors["Updating Student Performance"] = "Couldn't execute query. " . mysqli_error();
    }
} else {
    $query = "INSERT INTO students_performance
        (student_id, subject_id, test1, test2, test3, assignment)
        VALUES ('$sid', '$subject_id', ";

    $query .= $test1 == NULL ? "NULL," : "'$test1',";
    $query .= $test2 == NULL ? "NULL," : "'$test2',";
    $query .= $test3 == NULL ? "NULL," : "'$test3',";
    $query .= $assignment == NULL ? "NULL)" : "'$assignment')";

    if ($conn->execute($query)) {
        $_SESSION["status"] = "Successfully added student performance";
    } else {
        $errors["Inserting Student Performance"] = "Couldn't execute query. " . mysqli_error();
    }
}

header("location: ../edit-student-performance.php?sid=$sid&subject_id=$subject_id&fname=$fname&subject=$subject&semester=$semester");
?>