<?php
session_start();

require_once "../includes/DbOperations.php";
$conn = new DbOperations();

$errors = array();

$fname = $_SESSION["fname"];
$username = $_SESSION["username"];
$email = $_SESSION["email"];
$password = $_SESSION["password"];
$cpassword = $_SESSION["cpassword"];

if (!empty($fname)) {
    if (strlen($fname) >= 6 and strlen($fname) <= 255) {
        if (!preg_match("/^[A-Za-z ]+$/", $fname)) {
            $errors["Full name"] = "Full name must contain only alphabet characters and spaces";
        }
    } else {
        $errors["Full name"] = "Full name must be between 6 to 255 characters";
    }
} else {
    $errors["Full name"] = "Please input your full name";
}

if (!empty($username)) {
    if (strlen($username) >= 4 and strlen($username) <= 32) {
        if (preg_match("/^\w+$/", $username)) {
            $query = "SELECT * FROM teachers WHERE username='$username'";
            if ($conn->exists($query)) {
                $errors["Username"] = "Invalid Username";
            }
        } else {
            $errors["Username"] = "Username must contain only letters, numbers, and underscores";
        }
    } else {
        $errors["Username"] = "Username must be between 4 to 32 characters";
    }
} else {
    $errors["Username"] = "Please input your username";
}

if (!empty($password)) {
    if (strlen($password) >= 6 and strlen($password) <= 20) {
        if ($password == $cpassword) {
            if (!preg_match("/^\w+$/", $password)) {
                $errors["Password"] = "Password must contain only characters, numbers and underscore";
            }
        } else {
            $errors["Password"] = "Password and Confimation Password should match";
        }
    } else {
        $errors["Password"] = "Password must be between 6 to 20 characters";
    }
} else {
    $errors["Password"] = "Please input your password";
}

if (!empty($errors)) {
    $_SESSION["errors"] = $errors;
    header("location: ../teachers-index.php");
} else {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO teachers (username, password, full_name)
        VALUES ('$username', '$password', '$fname')";
    $conn->execute($query);

    session_unset();
    $_SESSION["status"] = "Sign up complete. Please log in.";
    header("location: ../teachers-index.php");
}
?>