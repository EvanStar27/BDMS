<?php
session_start();

require_once "../includes/DbOperations.php";
$conn = new DbOperations();

$semester = $_SESSION["semester"];
$uname = $_SESSION["username"];
$fname = $_SESSION["fname"];
$rollno = $_SESSION["roll"];
$gender = $_SESSION["gender"];

$errors = array();

if (!empty($uname)) {
    if (strlen($uname) >= 4 and strlen($uname) <= 32) {
        if (preg_match("/^\w+$/", $uname)) {
            $query = "SELECT * FROM students 
                WHERE username='$uname' AND semester='$semester'";

            if ($conn->exists($query)) {
                $errors["Username"] = "Username already exists";
            }
        } else {
            $errors["Username"] = "Username must contain only letters, numbers, and underscores";
        }
    } else {
        $errors["Username"] = "Username must be between 4 to 32 characters";
    }
} else {
    $errors["Username"] = "Please input student's username";
}

if (!empty($fname)) {
    if (strlen($fname) >= 6 and strlen($fname) <= 255) {
        if (!preg_match("/^[A-Za-z ]+$/", $fname)) {
            $errors["Full name"] = "Full name must contain only alphabet characters and spaces";
        }
    } else {
        $errors["Full name"] = "Full name must be between 6 to 255 characters";
    }
} else {
    $errors["Full name"] = "Please input student's full name";
}

if (!empty($rollno)) {
    if (strlen($rollno) >= 1 and strlen($rollno) <= 32) {
        if (preg_match("/^[A-Za-z0-9\-]+$/", $rollno)) {
            $query = "SELECT * FROM students 
                WHERE rollno='$rollno' AND semester='$semester'";
                
            if ($conn->exists($query)) {
                $errors["Roll No."] = "Roll number already exists";
            }    
        } else {
            $errors["Roll No."] = "Roll number must contain only alphabet characters, numbers and hyphens";
        }
    } else {
        $errors["Roll No."] = "Roll number must be between 1 to 32 characters";
    }
} else {
    $errors["Roll No."] = "Please input student's roll number";
}

if (!empty($errors)) {
    $_SESSION["errors"] = $errors;
    header("location: ../add-students.php");
} else {
    unset($_SESSION["gender"]);
    unset($_SESSION["username"]);
    unset($_SESSION["fname"]);
    unset($_SESSION["roll"]);
    unset($_SESSION["semester"]);

    $pword = password_hash($uname, PASSWORD_DEFAULT);

    $query = "INSERT INTO students (username, password, full_name, rollno, gender, semester)
        VALUES ('$uname', '$pword', '$fname', '$rollno', '$gender', '$semester')";

    if ($conn->execute($query)) {
        $_SESSION["status"] = "$fname has been successfully added";
    } else {
        die("Oops! Something went wrong... Please try again.");
    }
    header("location: ../add-students.php");
}
?>