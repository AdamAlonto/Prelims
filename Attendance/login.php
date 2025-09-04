<?php
require_once "classes/User.php";
session_start();

$user = new User();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = $user->login($_POST['email'], $_POST['password']);
    if ($result) {
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['role'] = $result['role'];

        if ($result['role'] == 'student') {
            header("Location: student_dashboard.php");
        } else {
            header("Location: admin_dashboard.php");
        }
        exit;
    } else {
        echo "<script>alert('Invalid credentials!');</script>";
    }
}
?>
<?php include "views/header.php"; ?>
<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-3">&larr; Back</a>

    <h2>Login</h2>
    <form method="POST">
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
        <button class="btn btn-primary">Login</button>
    </form>
</div>
<?php include "views/footer.php"; ?>
