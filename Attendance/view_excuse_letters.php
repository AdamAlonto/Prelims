<?php
session_start();
if ($_SESSION['role'] != 'admin') { header("Location: login.php"); exit; }

require_once "classes/Admin.php";

$admin = new Admin();

$courses = $admin->conn->query("SELECT * FROM courses ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

$selected_course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $excuse_letter_id = (int)$_GET['id'];

    if ($action == 'approve') {
        $admin->updateExcuseLetterStatus($excuse_letter_id, 'approved');
    } elseif ($action == 'reject') {
        $admin->updateExcuseLetterStatus($excuse_letter_id, 'rejected');
    }
    header("Location: view_excuse_letters.php?course_id=" . $selected_course_id);
    exit;
}

$excuse_letters = [];
if ($selected_course_id > 0) {
    $excuse_letters = $admin->getExcuseLettersByCourse($selected_course_id);
}

?>
<?php include "views/header.php"; ?>
<div class="container mt-5">
    <h2>View Excuse Letters</h2>

    <form action="" method="get" class="mb-3">
        <div class="form-group">
            <label for="course_id">Filter by Course:</label>
            <select name="course_id" id="course_id" class="form-control" onchange="this.form.submit()">
                <option value="0">Select a course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= $course['id'] ?>" <?= $selected_course_id == $course['id'] ? 'selected' : '' ?>><?= htmlspecialchars($course['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if ($selected_course_id > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Date Submitted</th>
                    <th>Reason</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($excuse_letters)): ?>
                    <tr>
                        <td colspan="6">No excuse letters found for this course.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($excuse_letters as $letter): ?>
                        <tr>
                            <td><?= htmlspecialchars($letter['student_name']) ?></td>
                            <td><?= htmlspecialchars($letter['date_submitted']) ?></td>
                            <td><?= htmlspecialchars($letter['reason']) ?></td>
                            <td><a href="<?= htmlspecialchars($letter['file_path']) ?>" target="_blank">View File</a></td>
                            <td><?= htmlspecialchars($letter['status']) ?></td>
                            <td>
                                <?php if ($letter['status'] == 'pending'): ?>
                                    <a href="?action=approve&id=<?= $letter['id'] ?>&course_id=<?= $selected_course_id ?>" class="btn btn-success btn-sm">Approve</a>
                                    <a href="?action=reject&id=<?= $letter['id'] ?>&course_id=<?= $selected_course_id ?>" class="btn btn-danger btn-sm">Reject</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>
<?php include "views/footer.php"; ?>
