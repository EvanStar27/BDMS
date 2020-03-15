<?php
session_start();

require_once "../includes/DbOperations.php";
$conn = new DbOperations();

$errors = array();

$username = $_SESSION["username"];
$password = $_SESSION["password"];

if (!empty($username)) {
    if (strlen($username) >= 4 and strlen($username) <= 32) {
        if (preg_match("/^\w+$/", $username)) {
            $query = "SELECT * FROM teachers WHERE username='$username'";
            if (!($conn->exists($query))) {
                $errors["Username"] = "Invalid Username";
            }
        } else {
            $errors["Username"] = "Username must contain only letters, numbers, and underscores";
        }
    } else {
        $errors["Username"] = "Username must be between 4 to 31 characters";
    }
} else {
    $errors["Username"] = "Please input your username";
}

if (!empty($password)) {
    if (strlen($password) >= 6 and strlen($password) <= 32) {
        $query = "SELECT password FROM teachers WHERE username='$username'";
        if ($res = $conn->execute($query)) {
            $hash_password = mysqli_fetch_array($res)["password"];
            if (!password_verify($password, $hash_password)) {
                $errors["Password"] = "Invalid Password";
            }
        } else {
            $errors["Password"] = "Invalid Username or Password";
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
    session_unset();
    $_SESSION["teachers-login-status"] = true;

    $query = "SELECT * from teachers WHERE username='$username'";
    if ($res = $conn->execute($query)) {
        $row = mysqli_fetch_array($res);
        $_SESSION["session-tid"] = $row["id"];
        $_SESSION["session-fname"] = $row["full_name"];
    } else {
        die("Oops! something went wrong: " . mysqli_error());
    }

    header("location: ../teachers-home.php");
}
?>