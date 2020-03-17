<?php
session_start();

require_once "includes/DbOperations.php";
$conn = new DbOperations();
$errors = array();
$subject = "";
$semester = "";
$subId = "";
$studentIdName = array();
$studentIdRoll = array();
$studentIdTest1 = array();
$studentIdTest2 = array();
$studentIdTest3 = array();
$studentIdAssignment = array();
$studentIdIA = array();

if (!isset($_SESSION["teachers-login-status"]) and $_SESSION["teachers-login-status"] != true) {
    header("location: teachers-index.php");
}

if (isset($_GET["btn-view"])) {
    $subjectSemId = explode("|", $_GET["subject"]);
    $subject = $subjectSemId[0];
    $semester = $subjectSemId[1];
    $subId = $subjectSemId[2];
    
    // GET Students info
    $query = "SELECT * FROM students
        WHERE semester='$semester'
        ORDER BY LENGTH(rollno) ASC, rollno ASC";

    if ($res = $conn->execute($query)) {
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_array($res)) {
                $studentIdName[$row["id"]] = $row["full_name"];
                $studentIdRoll[$row["id"]] = $row["rollno"];
            }
        } else {
            $errors["Students"] = "$semester student's information is empty.";
        }
    } else {
        $errors["MySQL Error"] = "Couldn't fetch student's information. " . mysqli_error();
    }

    // GET Students Performance
    $query = "SELECT * FROM students_performance WHERE subject_id='$subId'";
    if ($res = $conn->execute($query)) {
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_array($res)) {
                $studentIdTest1[$row["student_id"]] = $row["test1"];
                $studentIdTest2[$row["student_id"]] = $row["test2"];
                $studentIdTest3[$row["student_id"]] = $row["test3"];
                $studentIdAssignment[$row["student_id"]] = $row["assignment"];
                $studentIdIA[$row["student_id"]] = $row["subject_ia"];
            }
        }
    } else {
        $errors["Students Performance"] = "Couldn't fetch students performance data. " . mysqli_error();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/students-performance.css">
    <title>Students Performance</title>

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
        <h1>Students Performance</h1>
        <p>Select Subject and press View Button.</p>
    </div>

    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            View For Subject:<br>
            <select name="subject" id="">
                <?php
                    $query = "SELECT * FROM subjects";
                    if ($res = $conn->execute($query)) {
                        if (mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_array($res)) {
                                echo "<option value='" . $row["subject_name"] . "|" . $row["semester"] . "|" . $row["id"] . "'>" . $row["subject_name"] . "</option>";
                            }
                        } else {
                            $errors["Subject"] = "Table is empty.";
                        }
                    } else {
                        $errors["MySQL Error"] = "Couldn't execute query. " . mysqli_error();
                    }
                ?>
            </select>
            <input type="submit" name="btn-view" value="VIEW">
        </form>
        <hr>
    </div>

    <div style="text-align: center;">For Subject: <?php echo $subject; ?></div>
    <div style="text-align: center; margin-bottom: 10px;">Of <?php echo $semester; ?></div>

    <div class="center-table">
        <div class="table-container">
        <table>
            <tr>
                <th>Full Name</th>	
                <th>Roll No.</th>	
                <th>Test 1</th>
                <th>Test 2</th>
                <th>Test 3</th>
                <th>Assignments</th>
                <th>IA</th>
                <th>Update</th>
            </tr>

            <?php
                foreach ($studentIdName as $sid => $fname) {
                    echo "<tr>";
                        echo "<td>" . $fname . "</td>";
                        echo "<td style='text-align: center;'>" . $studentIdRoll[$sid] . "</td>";

                        $test1Val = isset($studentIdTest1[$sid]) ? $studentIdTest1[$sid] : "NULL";
                        $test2Val = isset($studentIdTest2[$sid]) ? $studentIdTest2[$sid] : "NULL";
                        $test3Val = isset($studentIdTest3[$sid]) ? $studentIdTest3[$sid] : "NULL";
                        $assignmentVal = isset($studentIdAssignment[$sid]) ? $studentIdAssignment[$sid] : "NULL";
                        $iaVal = isset($studentIdIA[$sid]) ? $studentIdIA[$sid] : "NULL";

                        echo "<td style='text-align: center;'>" . $test1Val . "</td>";
                        echo "<td style='text-align: center;'>" . $test2Val . "</td>";
                        echo "<td style='text-align: center;'>" . $test3Val . "</td>";
                        echo "<td style='text-align: center;'>" . $assignmentVal . "</td>";
                        echo "<td style='text-align: center;'>" . $iaVal . "</td>";

                        echo "<td style='text-align: center;'>";
                                echo "<a class='update-links' title='Edit' href='edit-student-performance.php?sid=$sid&subject_id=$subId&fname=$fname&subject=$subject&semester=$semester'><img src='img/icons/edit.png' alt='Edit'></a>";
                                echo "<a class='update-links' title='View Report' href='student-performance-stats.php?sid=" . $sid . "&semester=$semester&btn-view=View'><img src='img/icons/graphic.png' alt='View Report'></a>";
                        echo "</td>";
                        
                    echo "</tr>";
                }
            ?>
        </table>
        </div>  <!-- Table Container -->
    </div>  <!-- Center Table -->

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

    <?php 
        // Clear session
        if (isset($_SESSION["sid"]))
            unset($_SESSION["sid"]);

        if (isset($_SESSION["subject_id"]))
            unset($_SESSION["subject_id"]);

        if (isset($_SESSION["fname"]))
            unset($_SESSION["fname"]);

        if (isset($_SESSION["subject_name"]))
            unset($_SESSION["subject_name"]);

        if (isset($_SESSION["semester"]))
            unset($_SESSION["semester"]);

        if (isset($_SESSION["test1"]))
            unset($_SESSION["test1"]);

        if (isset($_SESSION["test2"]))
            unset($_SESSION["test2"]);

        if (isset($_SESSION["test3"]))
            unset($_SESSION["test3"]);

        if (isset($_SESSION["assignment"]))
            unset($_SESSION["assignment"]);
    ?>

    <script src="js/common.js"></script>
</body>
</html>