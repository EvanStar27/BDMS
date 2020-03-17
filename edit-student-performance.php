<?php
session_start();

require_once "includes/DbOperations.php";
$conn = new DbOperations();

if (!isset($_SESSION["teachers-login-status"]) and $_SESSION["teachers-login-status"] != true) {
    header("location: teachers-index.php");
}

$errors = array();
$sid = "";
$fname = "";
$subject_id = "";
$subject = "";
$semester = "";

if (isset($_GET["sid"])) {
    $sid = $_GET["sid"];
    $_SESSION["sid"] = $sid;
}
    
if (isset($_GET["subject_id"])) {
    $subject_id = $_GET["subject_id"];
    $_SESSION["subject_id"] = $subject_id;
}
    
if (isset($_GET["fname"])) {
    $fname = $_GET["fname"];
    $_SESSION["fname"] = $fname;
}

if (isset($_GET["subject"])) {
    $subject = $_GET["subject"];
    $_SESSION["subject_name"] = $subject;
}

if (isset($_GET["semester"])) {
    $semester = $_GET["semester"];
    $_SESSION["semester"] = $semester;
}

// GET Student Performance
$test1 = "";
$test2 = "";
$test3 = "";
$assignment = "";

$query = "SELECT * FROM students_performance
    WHERE student_id='$sid' and subject_id='$subject_id'";

if ($res = $conn->execute($query)) {
    if (mysqli_num_rows($res) <= 1) {
        $row = mysqli_fetch_array($res);

        $test1 = $row["test1"];
        $test2 = $row["test2"];
        $test3 = $row["test3"];
        $assignment = $row["assignment"];
    } else {
        $errors["Student Performance"] = "Number of rows fetched for a particular subject with student id is more than one.";
    }
} else {
    $errors["MySQL Error"] = "Couldn't fetch student performance. " . mysqli_error();
}

// GET UPDATED VALUES
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["test1"] = $_POST["test1"];
    $_SESSION["test2"] = $_POST["test2"];
    $_SESSION["test3"] = $_POST["test3"];
    $_SESSION["assignment"] = $_POST["assignment"];
    header("location: handlers/edit-student-performance-handler.php");
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
    <title>Edit Student Performance</title>

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
        <h1>Edit Students Performance</h1>
        <p>Please fill in the marks of <?php echo $fname; ?> for <?php echo $subject; ?> subject.</p>
        <p>*Note: Input 'ABS' if student was absent for a test or late submission of assignment.</p>
    </div>

    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            
            
            <?php
                if (!empty($test1))
                    echo "<input type='text' name='test1' placeholder='1st Test Marks' value='$test1'>";
                else
                    echo "<input type='text' name='test1' placeholder='1st Test Marks'>";

                if (!empty($test2))
                    echo "<input type='text' name='test2' placeholder='2nd Test Marks' value='$test2'>";
                else
                    echo "<input type='text' name='test2' placeholder='2nd Test Marks'>";

                if (!empty($test3))
                    echo "<input type='text' name='test3' placeholder='3rd Test Marks' value='$test3'>";
                else
                    echo "<input type='text' name='test3' placeholder='3rd Test Marks'>";

                if (!empty($assignment))
                    echo "<input type='text' name='assignment' placeholder='Assignment Marks' value='$assignment'>";
                else
                    echo "<input type='text' name='assignment' placeholder='Assignment Marks'>";
            ?>

            <input type="submit" name="btn-done" value="UPDATE">
        </form>
        <a class="links" href="<?php echo 'students-performance.php?subject=' . $subject . '|' . $semester . '|' . $subject_id . '&btn-view=VIEW'; ?>">Go Back</a>
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