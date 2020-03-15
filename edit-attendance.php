<?php
session_start();
require_once "includes/DbOperations.php";
$conn = new DbOperations();

if (!isset($_SESSION["teachers-login-status"]) and $_SESSION["teachers-login-status"] != true) {
    header("location: teachers-index.php");
}

$errors = array();
$studentsIdStatus = array();
$studentsIdName = array();
$studentsIdRoll = array();
$studentsIdStatusUpdate = array();

$asid = "";
// $new_student_ins = false;

if (isset($_GET["asid"])) {
    $asid = $_GET["asid"];
    $_SESSION["asid"] = $asid;
    
    // get subject id and date
    $query = "SELECT * FROM attendance_sheet
        WHERE id='$asid'";

    if ($res = $conn->execute($query)) {
        $row = mysqli_fetch_array($res);

        $subject_id = $row["subject_id"];
        $date = $row["date"];
    } else {
        die("Couldn't fetch attendance sheet record: " . mysqli_error());
    }

    // get subject name and semester
    $query = "SELECT * FROM subjects
        WHERE id='$subject_id'";

    if ($res = $conn->execute($query)) {
        $row = mysqli_fetch_array($res);

        $subject = $row["subject_name"];
        $semester = $row["semester"];
    } else {
        die("Couldn't fetch subject name: " . mysqli_error());
    }

    // get student id and status from attendance_records
    $query = "SELECT * FROM attendance_records
        WHERE asheet_id='$asid'";

    if ($res = $conn->execute($query)) {
        while($row = mysqli_fetch_array($res)) {
            $student_id = $row["student_id"];
            $studentsIdStatus[$student_id] = $row["status"];
        }
    } else {
        die("Couldn't fetch subject name: " . mysqli_error());
    }

    // get student name and rollno
    $query = "SELECT * FROM students 
        WHERE semester='$semester' ORDER BY LENGTH(rollno), rollno ASC";

    if ($res = $conn->execute($query)) {
        while($row = mysqli_fetch_array($res)) {
            $student_id = $row["id"];
            $studentsIdName[$student_id] = $row["full_name"];
            $studentsIdRoll[$student_id] = $row["rollno"];
        }
    } else {
        die("Couldn't fetch subject name: " . mysqli_error());
    }

    $_SESSION["studentsIdStatus"] = $studentsIdStatus;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentsIdStatus = $_SESSION["studentsIdStatus"];
    foreach ($studentsIdStatus as $sid => $status) {
        $studentsIdStatusUpdate[$sid] = $_POST[$sid];
    }

    $_SESSION["studentsIdStatusUpdate"] = $studentsIdStatusUpdate;
    header("location: handlers/edit-attendance-handler.php");
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
    <title>Edit Attendance Records</title>

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
        <h1>Edit Attendance</h1>
        <p>Edit students attendance record.</p>
    </div>

    <div class="span" style="text-align: center;"><?php echo "Update Attendance for " . $subject; ?></div>
    <div class="span" style="text-align: center;"><?php echo "of " . $date; ?></div>

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
                foreach ($studentsIdName as $sid => $fname) {
                    echo "<tr>";
                        echo "<td style='text-align: left;'>" . $fname . "</td>";
                        echo "<td style='text-align: center;'>" . $studentsIdRoll[$sid] . "</td>";

                        if (isset($studentsIdStatus[$sid]) and isset($studentsIdName[$sid])) {
                            if ($studentsIdStatus[$sid] == "Present") {
                                echo "<td style='text-align: center;'><input type='radio' name='" . $sid . "' value='Present' checked></td>";
                                echo "<td style='text-align: center;'><input type='radio' name='" . $sid . "' value='Absent'></td>";
                            } else {
                                echo "<td style='text-align: center;'><input type='radio' name='" . $sid . "' value='Present'></td>";
                                echo "<td style='text-align: center;'><input type='radio' name='" . $sid . "' value='Absent' checked></td>";
                            }
                        } else {
                            $query = "INSERT INTO attendance_records 
                            (asheet_id, student_id, status)
                            VALUES ('$asid', '$sid', 'Absent')";

                            if (!($conn->execute($query))) {
                                die("Couldn't insert record of a new student id $sid: " . mysqli_error());
                            }

                            // $new_student_ins = true;

                            echo "<td style='text-align: center;'><input type='radio' name='" . $sid . "' value='Present'></td>";
                            echo "<td style='text-align: center;'><input type='radio' name='" . $sid . "' value='Absent' checked></td>";
                        }
                    echo "</tr>";
                }
            ?>
        </table>
        </div>  <!-- Table Container -->
        </div>  <!-- Center Table -->

        <div style="text-align: center; margin-bottom: 50px;">
            <input style="position: relative; display: block;" id="btn-update" type="submit" name="btn-update" value="UPDATE">
            <a style="position: relative;" class="links" href="attendance-sheet.php">Go Back</a>
        </div>
        <!-- <input id="btn-update" type="submit" name="btn-update" value="UPDATE">
        <a style="text-align: center;" class="links" href="attendance-sheet.php">Go Back</a> -->
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