<?php
require_once "classes/Student.php";
$student = new Student();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($student->registerStudent($_POST['name'], $_POST['email'], $_POST['password'], $_POST['course_id'])) {
        echo "<script>alert('Student registered successfully!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Registration failed!');</script>";
    }
}
?>
<?php include "views/header.php"; ?>
<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-3">&larr; Back</a>

    <h2>Student Registration</h2>
    <form method="POST">
        <input type="text" name="name" class="form-control mb-2" placeholder="Full Name" required>
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
        <select name="course_id" class="form-control mb-2" required>
    <option value="">Select Course</option>
    <?php
    $stmt = $student->conn->prepare("SELECT * FROM courses");
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($courses as $row) {
        echo "<option value='{$row['id']}'>{$row['name']} (Year {$row['year_level']})</option>";
    }
    ?>
</select>

        <button class="btn btn-info">Register</button>
    </form>
</div>
<?php include "views/footer.php"; ?>
