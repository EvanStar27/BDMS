<?php
session_start();

require_once "includes/DbOperations.php";
$conn = new DbOperations();

if (!isset($_SESSION["teachers-login-status"]) and $_SESSION["teachers-login-status"] != true) {
    header("location: teachers-index.php");
}

$errors = array();
$teachersId = array();
$subjectsId = array();

$query = "SELECT * FROM teachers";
if ($res = $conn->execute($query)) {
    while ($row = mysqli_fetch_array($res)) {
        $teachersId[$row["id"]] = $row["full_name"];
    }
} else {
    die("Couldn't fetch teachers info: " . mysqli_error());
}

$query = "SELECT * FROM subjects";
if ($res = $conn->execute($query)) {
    while ($row = mysqli_fetch_array($res)) {
        $subjectsId[$row["id"]] = $row["subject_name"];
    }
} else {
    die("Couldn't fetch subjects info: " . mysqli_error());
}

// EDIT Attendance Sheet
if (isset($_GET["e_asid"])) {
    $e_asid = $_GET["e_asid"];
    header("location: edit-attendance.php?asid=$e_asid");
}

// DELETE Attendance Sheet
if (isset($_GET["asid"])) {
    $asid = $_GET["asid"];
    $query = "DELETE FROM attendance_sheet WHERE id='$asid'";

    if (!($conn->execute($query))) {
        $errors["deleteErr"] = "Couldn't delete attendance sheet record: " . mysqli_error();
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
    <link rel="stylesheet" type="text/css" href="css/attendance-sheet.css">
    <title>Attendance Sheet</title>
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
        <h1>Attendance Sheet</h1>
        <p>Bachelor Of Computer Application's Attendance Sheet.</p>
        <button id="enable-delete">Enable Delete</button>
    </div>

    <div class="center-table">
        <div class="table-container">
        <table>
            <tr>
                <!-- <th>ID</th> -->
                <th>Teacher</th>	
                <th>Subject</th>	
                <th>Date</th>
                <th>Update</th>
            </tr>

            <?php
                $query = "SELECT * FROM attendance_sheet ORDER BY LENGTH(id) DESC, id DESC";

                if ($res = $conn->execute($query)) {

                    while ($row = mysqli_fetch_array($res)) {
                        echo "<tr>";
                            echo "<td>" . $teachersId[$row["teachers_id"]] . "</td>";
                            echo "<td>" . $subjectsId[$row["subject_id"]] . "</td>";
                            echo "<td style='text-align: center;'>" . date("d F Y", strtotime($row["date"])) . "</td>";
                            
                            echo "<td>";
                                echo "<a class='update-links' title='Edit' href='?e_asid=" . $row["id"] . "'><img src='img/icons/edit.png' alt='Edit'></a>";
                                echo "<a class='update-links btn-del' title='Delete' href='?asid=" . $row["id"] . "'><img src='img/icons/trash.png' alt='Delete'></a>";
                            echo "</td>";
                        echo "</tr>";
                    }
                    
                } else {
                    die("Couldn't fetch attendance sheet records: " . mysqli_error());
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

    <script src="js/common.js"></script>
</body>
</html>