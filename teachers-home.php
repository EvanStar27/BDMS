<?php
session_start();

require_once "includes/DbOperations.php";

if (isset($_SESSION["teachers-login-status"]) and $_SESSION["teachers-login-status"] == true) {
    $tid = $_SESSION["session-tid"];
    $fname = $_SESSION["session-fname"];
} else {
    header("location: teachers-index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/teachers-home.css">
    <title>Home</title>
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
        <h1>Welome <?php echo explode(" ", $fname)[0]; ?></h1>
        <p>What would you like to do?</p>
    </div>

    <div class="teachers-functions">
        <a href="teachers-profile.php">
            <img src="img/icons/user.png" alt="">
            <p class="fun-name">My profile</p>
            <p class="fun-disc">Click here to view and update your profile.</p>
        </a>

        <a href="add-subject.php">
            <img src="img/icons/online-course.png" alt="">
            <p class="fun-name">Add Subject</p>
            <p class="fun-disc">Click here to add a new subject.</p>
        </a>

        <a href="add-students.php">
            <img src="img/icons/add.png" alt="">
            <p class="fun-name">Add Students</p>
            <p class="fun-disc">Click here to add students.</p>
        </a>

        <a href="students-info.php">
            <img src="img/icons/id-card.png" alt="">
            <p class="fun-name">Students Information</p>
            <p class="fun-disc">Click here to view or update students information.</p>
        </a>

        <a href="students-performance.php">
            <img src="img/icons/performance.png" alt="">
            <p class="fun-name">Students Performance</p>
            <p class="fun-disc">Click here to view, add students performance.</p>
        </a>

        <a href="attendance-manager.php">
            <img src="img/icons/register.png" alt="">
            <p class="fun-name">Take attendance</p>
            <p class="fun-disc">Click here to take your class attendance.</p>
        </a>

        <a href="attendance-sheet.php">
            <img src="img/icons/register.png" alt="">
            <p class="fun-name">View attendance sheet</p>
            <p class="fun-disc">Click here to view or update attendance records.</p>
        </a>

        <a href="#">
            <img src="img/icons/writing.png" alt="">
            <p class="fun-name">Write a notice</p>
            <p class="fun-disc">Click here to write a notice to your students, colleague.</p>
        </a>

        <a href="#">
            <img src="img/icons/noticeboard.png" alt="">
            <p class="fun-name">Noticeboard</p>
            <p class="fun-disc">Click here to view noticeboard.</p>
        </a>
    </div>

    
    
    <script src="js/common.js"></script>
</body>
</html>