<?php
session_start();
require_once "includes/DbOperations.php";
$conn = new DbOperations();

$errors = array();
$username = $fname = $rollno = $gender = $phone = $semester = "";

if (!isset($_SESSION["teachers-login-status"]) and $_SESSION["teachers-login-status"] != true) {
    header("location: teachers-index.php");
}

if (isset($_SESSION["errors"])) {
    $errors = $_SESSION["errors"];
}

if (isset($_GET["sid"])) {
    $sid = $_GET["sid"];
    $_SESSION["sid"] = $sid;
    $query = "SELECT * FROM students
        WHERE id='$sid'";
    
    if ($res = $conn->execute($query)) {
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_array($res);
        
            $sid = $row["id"];
            $username = $row["username"];
            $fname = $row["full_name"];
            $rollno = $row["rollno"];
            $gender = $row["gender"];
            $phone = $row["phone"];
            $semester = $row["semester"];
        } else {
            $errors["fetchErr"] = "Couldn't fetch student's information";
        }
    } else {
        $errors["fetchErr"] = "Couldn't fetch student's information: " . mysqli_error();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["semester"] = $_POST["semester"];
    $_SESSION["fname"] = $_POST["fname"];
    $_SESSION["username"] = $_POST["username"];
    $_SESSION["roll"] = $_POST["roll"];
    $_SESSION["gender"] = $_POST["gender"];
    $_SESSION["phone"] = $_POST["phone"];
    $_SESSION["password"] = $_POST["password"];
    $_SESSION["cpassword"] = $_POST["cpassword"];
    header("location: handlers/edit-student-handler.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/edit-student.css">
    <title>Edit Student</title>

    <script>
        function display_status() {
            $(".status-container").css("display", "block");
            $(".status-container").css("animation", "disp-status 10s forwards");
        }
    </script>
</head>
<body>
    <!-- ********************************** HEADER ************************************  -->
    <header>
        <img id="logo" src="img/code.png" alt="">
        <nav id="nav-links">
            <ul>
                <li><a href="teachers-home.php">HOME</a></li>
                <li><a href="#">ABOUT</a></li>
                <li><a href="handlers/teachers-logout.php">LOGOUT</a></li>
            </ul>
        </nav>

        <span id="menu"></span>
    </header>

    <!-- ********************************* MAIN ************************************** -->
    <div class="welcome-container">
        <h1>Edit Students</h1>
        <p>Please correct the fields in the form below.</p>
        <p>*Note: If password and phone number is left empty the password and phone number set by the student will be used.</p>
    </div>

    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            To:<br>
            <select name="semester" id="">
                <option value="1st Semester" <?php if ($semester == "1st Semester") echo "selected"?>>1st Semester</option>
                <option value="2nd Semester" <?php if ($semester == "2nd Semester") echo "selected"?>>2nd Semester</option>
                <option value="3rd Semester" <?php if ($semester == "3rd Semester") echo "selected"?>>3rd Semester</option>
                <option value="4th Semester" <?php if ($semester == "4th Semester") echo "selected"?>>4th Semester</option>
                <option value="5th Semester" <?php if ($semester == "5th Semester") echo "selected"?>>5th Semester</option>
                <option value="6th Semester" <?php if ($semester == "6th Semester") echo "selected"?>>6th Semester</option>
            </select>
            <input type="text" name="fname" placeholder="Full Name" value="<?php echo $fname; ?>">
            <input type="text" name="username" placeholder="Username" value="<?php echo $username; ?>">
            <input type="password" name="password" placeholder="Password">
            <input type="password" name="cpassword" placeholder="Confirm Password">
            <input type="text" name="roll" placeholder="Roll Number" value="<?php echo $rollno; ?>">
            
            <?php
                if (!empty($phone))
                    echo "<input type='text' name='phone' placeholder='Phone Number' value='$phone'>";
                else
                    echo "<input type='text' name='phone' placeholder='Phone Number'>";
            ?>

            Gender:
            <input type="radio" name="gender" value="male" <?php if ($gender == "male") echo "checked"?>>Male
            <input type="radio" name="gender" value="female" <?php if ($gender == "female") echo "checked"?>>Female
            <input type="submit" name="btn-done" value="UPDATE">
        </form>
        <a class="links" href="students-info.php?semester=<?php echo $semester; ?>&btn-view=View">Go Back
    </div>

    <!-- ****************** STATUS CONTAINER **************************** -->
    <div class="status-container">
        
        <?php
            if (!empty($errors)) {
                echo "<span style='font-size: 16px; color: red; font-weight: bold;'>&times; ERROR</span>";

                foreach ($errors as $key => $value) {
                    echo "<br>" . $key . ": " . $value;
                }

                echo "<script>display_status();</script>";
                unset($_SESSION["errors"]);
            }

            if (isset($_SESSION["status"])) {
                echo "<span style='font-size: 16px; color: rgb(0, 156, 134); font-weight: bold;'>&check; SUCCESS</span>";
                $status_msg = $_SESSION["status"];
                unset($_SESSION["status"]);
                echo "<br>" . $status_msg;
                echo "<script>display_status();</script>";
            }
        ?>
    </div>

    <script src="js/common.js"></script>
</body>
</html>