<?php
session_start();

require_once "../includes/DbOperations.php";
$conn = new DbOperations();

$sid = $_SESSION["sid"];
$password = $_SESSION["password"];
$cpassword = $_SESSION["cpassword"];
$semester = $_SESSION["semester"];
$uname = $_SESSION["username"];
$fname = $_SESSION["fname"];
$rollno = $_SESSION["roll"];
$phone = $_SESSION["phone"];
$gender = $_SESSION["gender"];

$errors = array();
$passwordSet = false;
$phoneSet = false;

if (!empty($uname)) {
    if (strlen($uname) >= 4 and strlen($uname) <= 32) {
        if (preg_match("/^\w+$/", $uname)) {
            $query = "SELECT * FROM students 
                WHERE username='$uname' AND semester='$semester' AND id!='$sid'";

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
    $passwordSet = true;
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
                WHERE rollno='$rollno' AND semester='$semester' AND id!='$sid'";
                
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

if (!empty($phone)) {
    if (strlen($phone) == 10) {
        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            $errors["Phone No."] = "Phone number must contain only numbers";
        }
    } else {
        $errors["Phone No."] = "Please input a valid phone number.";
    }
    $phoneSet = true;
}

if (!empty($errors)) {
    $_SESSION["errors"] = $errors;
    header("location: ../edit-student.php?sid=$sid");
} else {
    unset($_SESSION["sid"]);
    unset($_SESSION["password"]);
    unset($_SESSION["cpassword"]);
    unset($_SESSION["gender"]);
    unset($_SESSION["username"]);
    unset($_SESSION["fname"]);
    unset($_SESSION["roll"]);
    unset($_SESSION["semester"]);
    unset($_SESSION["phone"]);

    if ($passwordSet) {
        $pword = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE students SET 
            username='$uname', 
            password='$pword',
            full_name='$fname', 
            rollno='$rollno', 
            gender='$gender', 
            semester='$semester'";
    } else {
        $query = "UPDATE students SET 
            username='$uname', 
            full_name='$fname', 
            rollno='$rollno', 
            gender='$gender', 
            semester='$semester'";
    }

    if ($phoneSet)
        $query .= ",phone='$phone' WHERE id=$sid";
    else
        $query .= "WHERE id=$sid";

    if ($conn->execute($query)) {
        $_SESSION["status"] = "$fname information updated.";
    } else {
        die("Oops! Something went wrong... Please try again.");
    }
    header("location: ../edit-student.php?sid=$sid");
}
?>