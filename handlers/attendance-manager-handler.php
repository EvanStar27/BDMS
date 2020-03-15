<?php
session_start();
date_default_timezone_set("Asia/Calcutta");

require_once "../includes/DbOperations.php";
$conn = new DbOperations();

$for_sem        = $_SESSION["for_sem"];
$subject        = $_SESSION["subject"];
$studentsId     = $_SESSION["studentsId"];
$tid            = $_SESSION["session-tid"];
$errors = array();

$query = "SELECT * FROM subjects 
    WHERE subject_name='$subject'";

if ($res = $conn->execute($query)) {
    $subjectId = mysqli_fetch_array($res)["id"];
} else {
    die("Couldn't fetch subject id (attendance-manager-handler.php): " . mysqli_error());
}

$date = date("Y-m-d");

$query = "INSERT INTO attendance_sheet (teachers_id, subject_id, date)
    VALUES ('$tid', '$subjectId', '$date')";

if ($conn->execute($query))
    $lastId = mysqli_insert_id($conn->getConnObj());
else
    die("Couldn't inster record to Attendance Sheet: " . mysqli_error());

foreach ($studentsId as $sid => $status) {
    $query = "INSERT INTO attendance_records (asheet_id, student_id, status)
        VALUES ('$lastId', '$sid', '$status')";

    if (!$conn->execute($query)) {
        $errors["recordInsErr"] = "Couldn't insert records for attendance sheet id $lastId";
        $errors["info"] = "Attendance Sheet for id $lastId is deleted. Please create a new sheet.";

        $query = "DELETE FROM attendance_sheet WHERE id='$lastId'";
        
    }
}

if (!empty($errors))
    $_SESSION["errors"] = $errors;
else
    $_SESSION["status"] = "Attendance record for $subject Date: $date has been added.";

unset($_SESSION["for_sem"]);
unset($_SESSION["subject"]);
unset($_SESSION["studentsId"]);
header("location: ../attendance-manager.php");
?>