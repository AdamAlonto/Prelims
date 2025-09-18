<?php
session_start();
if ($_SESSION['role'] != 'student') { header("Location: login.php"); exit; }

require_once "classes/Student.php";

$student = new Student();
$student_id = $_SESSION['student_id']; 

$excuse_letters = $student->getExcuseLetters($student_id);

?>
<?php include "views/header.php"; ?>
<div class="container mt-5">
    <h2>My Excuse Letters</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date Submitted</th>
                <th>Reason</th>
                <th>File</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($excuse_letters)): ?>
                <tr>
                    <td colspan="4">No excuse letters found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($excuse_letters as $letter): ?>
                    <tr>
                        <td><?= htmlspecialchars($letter['date_submitted']) ?></td>
                        <td><?= htmlspecialchars($letter['reason']) ?></td>
                        <td><a href="<?= htmlspecialchars($letter['file_path']) ?>" target="_blank">View File</a></td>
                        <td><?= htmlspecialchars($letter['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include "views/footer.php"; ?>
