<?php
session_start();
require_once "includes/DbOperations.php";
$conn = new DbOperations();

$errors = array();
$semester = "";

if (!isset($_SESSION["teachers-login-status"]) and $_SESSION["teachers-login-status"] != true) {
    header("location: teachers-index.php");
}

if (isset($_GET["sid"])) {
    $sid = $_GET["sid"];
    $query = "DELETE FROM students WHERE id='$sid'";

    if (!($conn->execute($query))) {
        $errors["deleteErr"] = "Couldn't delete student id $sid";
    }
}

if (isset($_GET["btn-view"])) {
    $semester = $_GET["semester"];

    $query = "SELECT * FROM students 
        WHERE semester IN ('$semester') 
        ORDER BY LENGTH(rollno), rollno ASC";

    if (!($res = $conn->execute($query))) {
        $errors["viewErr"] = "Something went wrong: " . mysqli_error();
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
    <link rel="stylesheet" type="text/css" href="css/students-info.css">
    <title>Students Information</title>

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
        <h1>Students Information</h1>
        <p>Select Semester and press View Button.</p>
    </div>

    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            View Students Of:<br>
            <select name="semester" id="">
                <option value="1st Semester">1st Semester</option>
                <option value="2nd Semester">2nd Semester</option>
                <option value="3rd Semester">3rd Semester</option>
                <option value="4th Semester">4th Semester</option>
                <option value="5th Semester">5th Semester</option>
                <option value="6th Semester">6th Semester</option>
            </select>
            <input type="submit" name="btn-view" value="VIEW">
        </form>
        <hr>
    </div>

    <div class="center-table">
        <div class="table-container">
        <button id="enable-delete">Enable Delete</button>
        <table>
            <tr>
                <!-- <th>ID</th> -->
                <th>Username</th>	
                <th>Full Name</th>	
                <th>Gender</th>	
                <th>Roll Number</th>	
                <th>Phone Number</th>
                <th>Update</th>
            </tr>

            <?php
                if (!empty($res)) {
                    while ($row = mysqli_fetch_array($res)) {
                        echo "<tr>";
                            // echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["username"] . "</td>";
                            echo "<td>" . $row["full_name"] . "</td>";
                            echo "<td>" . $row["gender"] . "</td>";
                            echo "<td>" . $row["rollno"] . "</td>";

                            if (!empty($row["phone"]))
                                echo "<td>" . $row["phone"] . "</td>";
                            else
                                echo "<td>NULL</td>";

                            echo "<td>";
                                echo "<a class='update-links' title='Edit' href='edit-student.php?sid=" . $row["id"] . "'><img src='img/icons/edit.png' alt='Edit'></a>";
                                echo "<a class='update-links btn-del' title='Delete' href='students-info.php?sid=" . $row["id"] . "&semester=$semester&btn-view=View'><img src='img/icons/trash.png' alt='Delete'></a>";
                            echo "</td>";
                        echo "</tr>";
                    }
                }
            ?>
        </table>
        </div>  <!-- Table Container -->
    </div>  <!-- Center Table -->

    <script src="js/common.js"></script>
</body>
</html>