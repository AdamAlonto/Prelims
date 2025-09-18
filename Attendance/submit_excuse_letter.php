<?php

session_start();
if ($_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit;
}

require_once "classes/Student.php";

$student = new Student();
$msg = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
    $student_id = $_SESSION['student_id']; 

    if (empty($reason)) {
        $errors[] = "Reason is required.";
    }

    if (isset($_FILES['excuse_file']) && $_FILES['excuse_file']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["excuse_file"]["name"]);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        if ($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg" && $file_type != "gif" && $file_type != "pdf") {
            $errors[] = "Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed.";
        }

        if (empty($errors)) {
            if (move_uploaded_file($_FILES["excuse_file"]["tmp_name"], $target_file)) {
                if ($student->submitExcuseLetter($student_id, $reason, $target_file)) {
                    $msg = "Excuse letter submitted successfully.";
                } else {
                    $errors[] = "Failed to submit excuse letter.";
                }
            } else {
                $errors[] = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $errors[] = "Excuse letter file is required.";
    }
}

?>

<?php include "views/header.php"; ?>

<div class="container mt-5">
    <h2>Submit Excuse Letter</h2>

    <?php if ($msg): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="reason">Reason</label>
            <textarea name="reason" id="reason" class="form-control" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label for="excuse_file">Upload Excuse Letter (PDF, JPG, PNG, GIF)</label>
            <input type="file" name="excuse_file" id="excuse_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
</div>

<?php include "views/footer.php"; ?>
