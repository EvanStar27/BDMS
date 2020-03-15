<?php
session_start();

if (!isset($_SESSION["teachers-login-status"]) and $_SESSION["teachers-login-status"] != true) {
    header("location: teachers-space.php");
}

require_once "../includes/DbOperations.php";
$conn = new DbOperations();

$errors = array();

$semester = $_SESSION["semester"];
$subject = $_SESSION["subject-name"];

if (!empty($subject)) {
    if (strlen($subject) >= 1 and strlen($subject) <= 255) {
        if (!preg_match("/^[A-Za-z0-9\-\+\' ]+$/", $subject)) {
            $errors["Subject name"] = "Subject name must contain only alphabet characters, numbers, spaces and (+,-,') symbols";
        }
    } else {
        $errors["Subject name"] = "Subject name must be between 1 to 255 characters";
    }
} else {
    $errors["Subject name"] = "Please input subject name";
}

if (!empty($errors)) {
    $_SESSION["errors"] = $errors;
    header("location: ../add-subject.php");
} else {
    unset($_SESSION["semester"]);
    unset($_SESSION["subject-name"]);
    

    $subject = mysqli_real_escape_string($conn->getConnObj(), $subject);

    $query = "INSERT INTO subjects (subject_name, semester)
        VALUES ('$subject', '$semester')";

    if ($conn->execute($query)) {
        $_SESSION["status"] = "$subject has been added.";
        header("location: ../add-subject.php");
    } else {
        $_SESSION["status"] = "$subject has been added.";
        header("location: ../add-subject.php");
    }
}
?>