<?php
require_once "classes/Student.php";
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$student = new Student();

$stmt = $student->conn->prepare("SELECT id FROM students WHERE user_id=? LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$student_profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student_profile) {
    header("Location: login.php");
    exit;
}

$student_id = $student_profile['id'];
$_SESSION['student_id'] = $student_id;

if (isset($_POST['attendance'])) {
    $filed = $student->fileAttendance($student_id); 
    if ($filed) {
        echo "<script>alert('Attendance recorded!');</script>";
    } else {
        echo "<script>alert('Attendance already filed for today!');</script>";
    }
}
?>
<?php include "views/header.php"; ?>
<div class="container mt-5">
    <h2>Student Dashboard</h2>

    <form method="POST">
        <button name="attendance" class="btn btn-success">File Attendance</button>
    </form>

    <a href="attendance_history.php" class="btn btn-info mt-3">View Attendance History</a>
    <a href="submit_excuse_letter.php" class="btn btn-warning mt-3">Submit Excuse Letter</a>
    <a href="student_excuse_letters.php" class="btn btn-secondary mt-3">My Excuse Letters</a>
    <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
</div>
<?php include "views/footer.php"; ?>
