<?php
session_start();
if ($_SESSION['role'] != 'admin') { header("Location: login.php"); exit; }
?>
<?php include "views/header.php"; ?>
<div class="container mt-5">
    <h2>Admin Dashboard</h2>
    <a href="add_course.php" class="btn btn-primary">Add/Edit Courses</a>
    <a href="attendance_check.php" class="btn btn-info">Check Attendance</a>
    <a href="view_excuse_letters.php" class="btn btn-warning">View Excuse Letters</a>
    <a href="logout.php" class="btn btn-danger mt-3">Logout</a>

</div>
<?php include "views/footer.php"; ?>
