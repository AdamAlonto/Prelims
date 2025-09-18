<?php
require_once "classes/User.php";
$user = new User();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user->register($_POST['name'], $_POST['email'], $_POST['password'], 'admin')) {
        echo "<script>alert('Admin registered successfully!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Registration failed!');</script>";
    }
}
?>
<?php include "views/header.php"; ?>
<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-3">&larr; Back</a>

    <h2>Admin Registration</h2>
    <form method="POST">
        <input type="text" name="name" class="form-control mb-2" placeholder="Full Name" required>
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
        <button class="btn btn-warning">Register</button>
    </form>
</div>
<?php include "views/footer.php"; ?>
