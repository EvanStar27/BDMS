<?php
session_start();
date_default_timezone_set("Asia/Calcutta");

require_once "includes/DbOperations.php";
$conn = new DbOperations();

if (!isset($_SESSION["teachers-login-status"]) and $_SESSION["teachers-login-status"] != true) {
    header("location: teachers-index.php");
}

$errors = array();
$subject = "";
$for_sem = "";

$query = "SELECT * FROM subjects";
if ($res = $conn->execute($query)) {
    $subjects = array();
    $semesters = array();
    $count = 0;

    while ($row = mysqli_fetch_array($res)) {
        $subjects[$count] = $row["subject_name"];
        $semesters[$row["subject_name"]] = $row["semester"];
        $count += 1;
    }
} else {
    die("Couldn't execute query: " . mysqli_error());
}

if (isset($_GET["btn-view"])) {
    $subject = $_GET["subject"];
    $for_sem = $semesters[$subject];
    $_SESSION["subject"] = $subject;
    $_SESSION["for_sem"] = $for_sem;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentsId = array();
    $for_sem = $_SESSION["for_sem"];

    $query = "SELECT * FROM students
        WHERE semester='$for_sem'";

    if ($res = $conn->execute($query)) {
        while ($row = mysqli_fetch_array($res)) {
            $studentsId[$row["id"]] = $_POST[$row["id"]];
        }

        $_SESSION["studentsId"] = $studentsId;
        header("location: handlers/attendance-manager-handler.php");
    } else {
        die("Couldn't execute query: " . mysqli_error());
    }
}

if (isset($_SESSION["errors"])) {
    $errors = $_SESSION["errors"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/attendance-manager.css">
    <title>Attendance Manager</title>

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
        <h1>Attendance Manager</h1>
        <p>Select a subject and press Take Attendance.</p>
    </div>

    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            For:<br>
            <select name="subject" id="">
                <?php
                    foreach ($subjects as $key => $value) {
                        echo "<option value='$value'>" . $value . "</option>";
                    }
                ?>
            </select>
            <input style="margin-top: 0;" type="submit" name="btn-view" value="Take Attendance">
        </form>
        <hr>
    </div>

    <div class="span" style="text-align: center;"><?php echo "Attendance for " . $subject; ?></div>
    <div class="span" style="text-align: center;"><?php echo date("d F Y"); ?></div>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <div class="center-table">
    <div class="table-container">
    <table>
        <tr>
            <th>Name</th>	
            <th>Roll Number</th>	
            <th>Present</th>
            <th>Absent</th>
        </tr>

        <?php
            if (!empty($subject) and !empty($for_sem)) {

                $query = "SELECT * FROM students
                    WHERE semester='$for_sem'
                    ORDER BY LENGTH(rollno), rollno";
                
                if ($res = $conn->execute($query)) {

                    while ($row = mysqli_fetch_array($res)) {
                        echo "<tr>";
                            echo "<td style='text-align: left;'>" . $row["full_name"] . "</td>";
                            echo "<td style='text-align: center;'>" . $row["rollno"] . "</td>";
                            echo "<td style='text-align: center;'><input type='radio' name='" . $row["id"] . "' value='Present' checked></td>";
                            echo "<td style='text-align: center;'><input type='radio' name='" . $row["id"] . "' value='Absent'></td>";
                        echo "</tr>";
                    }
                }
            }
        ?>
    </table>
    </div>  <!-- Table Container -->
    </div>  <!-- Center Table -->
    
        <input id="btn-update" type="submit" name="btn-update" value="UPDATE">
    </form>
    
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