<?php
require_once "classes/Student.php";
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit;
}

$student = new Student();
$stmt = $student->conn->prepare("SELECT id FROM students WHERE user_id=? LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$student_profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student_profile) {
    die("No student profile found.");
}

$student_id = $student_profile['id'];
$attendance_history = $student->getAttendanceHistory($student_id);
?>
<?php include "views/header.php"; ?>
<div class="container mt-5">
    <h2>Attendance History</h2>
    <a href="student_dashboard.php" class="btn btn-secondary mb-3">&larr; Back</a>

    <?php if (empty($attendance_history)): ?>
        <p>No attendance records found.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendance_history as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= $row['time_in'] ? date('g:i A', strtotime($row['time_in'])) : '' ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php include "views/footer.php"; ?>
