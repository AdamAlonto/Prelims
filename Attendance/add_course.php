<?php
require_once "classes/Admin.php";
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: login.php"); exit; }

$admin = new Admin();
$msg = '';
$errors = [];
$editing = false;
$course_id = 0;
$name = '';
$year_level = '';

if (isset($_GET['id'])) {
    $course_id = (int)$_GET['id'];
    if ($course_id > 0) {
        $editing = true;
        $stmt = $admin->conn->prepare("SELECT * FROM courses WHERE id = ? LIMIT 1");
        $stmt->execute([$course_id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($course) {
            $name = $course['name'];
            $year_level = $course['year_level'];
        } else {
            header("Location: add_course.php?msg=" . urlencode("Course not found."));
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && (int)$_POST['id'] > 0) {
        $editing = true;
        $course_id = (int)$_POST['id'];
    }

    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $year_level = isset($_POST['year_level']) ? (int)$_POST['year_level'] : 0;

    if ($name === '') { $errors[] = "Course name is required."; }
    if ($year_level <= 0) { $errors[] = "Year level must be a positive number."; }

    if (empty($errors)) {
        if ($editing) {
            $stmt = $admin->conn->prepare("UPDATE courses SET name = ?, year_level = ? WHERE id = ?");
            $stmt->execute([$name, $year_level, $course_id]);
            $msg = "Course updated successfully.";
            header("Location: add_course.php?msg=" . urlencode($msg));
            exit;
        } else {
            if (method_exists($admin, 'addCourse')) {
                $admin->addCourse($name, $year_level);
            } else {
                $stmt = $admin->conn->prepare("INSERT INTO courses (name, year_level) VALUES (?, ?)");
                $stmt->execute([$name, $year_level]);
            }
            $msg = "Course added!";
            $name = '';
            $year_level = '';
            header("Location: add_course.php?msg=" . urlencode($msg));
            exit;
        }
    }
}

$stmt = $admin->conn->query("SELECT * FROM courses ORDER BY name ASC");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<?php include "views/header.php"; ?>
<div class="container mt-5">
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">&larr; Back</a>

    <?php if (!empty($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <h2><?= $editing ? 'Edit Course/Program' : 'Add Course/Program' ?></h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="mb-4">
        <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?= (int)$course_id ?>">
        <?php endif; ?>
        <input type="text" name="name" class="form-control mb-2" placeholder="Course Name" required value="<?= htmlspecialchars($name) ?>">
        <input type="number" name="year_level" class="form-control mb-2" placeholder="Year Level" required value="<?= htmlspecialchars($year_level) ?>">
        <button class="btn btn-success"><?= $editing ? 'Save Changes' : 'Add Course' ?></button>
        <?php if ($editing): ?>
            <a href="add_course.php" class="btn btn-secondary ms-2">Add New</a>
        <?php endif; ?>
    </form>

    <h3>Existing Courses</h3>
    <table class="table table-bordered">
        <thead><tr><th>ID</th><th>Name</th><th>Year Level</th><th>Actions</th></tr></thead>
        <tbody>
            <?php if (empty($courses)): ?>
                <tr><td colspan="4">No courses found.</td></tr>
            <?php else: ?>
                <?php foreach ($courses as $c): ?>
                    <tr>
                        <td><?= (int)$c['id'] ?></td>
                        <td><?= htmlspecialchars($c['name']) ?></td>
                        <td><?= (int)$c['year_level'] ?></td>
                        <td>
                            <a href="add_course.php?id=<?= (int)$c['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include "views/footer.php"; ?>
