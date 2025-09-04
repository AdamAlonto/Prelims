<?php
require_once "classes/Admin.php";
session_start();
if ($_SESSION['role'] != 'admin') { header("Location: login.php"); exit; }

$admin = new Admin();
$records = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $records = $admin->viewAttendance($_POST['course_id'], $_POST['year_level']);
}
?>
<?php include "views/header.php"; ?>
<div class="container mt-5">
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">&larr; Back</a>

    <h2>Check Attendance</h2>
    <form method="POST">
        <select name="course_id" class="form-control mb-2" required>
            <option value="">Select Course</option>
            <?php
            $db = new Database();
            $stmt = $db->conn->query("SELECT * FROM courses");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>
        <input type="number" name="year_level" class="form-control mb-2" placeholder="Year Level" required>
        <button class="btn btn-primary">View</button>
    </form>

    <?php if (!empty($records)): ?>
    <table class="table table-bordered mt-3">
        <tr><th>Student</th><th>Date</th><th>Time In</th><th>Status</th></tr>
        <?php foreach ($records as $row): ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['date'] ?></td>
                <td><?= $row['time_in'] ? date('g:i A', strtotime($row['time_in'])) : '' ?></td>
                <td><?= ucfirst($row['status']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
<?php include "views/footer.php"; ?>
