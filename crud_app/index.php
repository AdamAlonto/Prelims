<?php
require_once "student.php";
require_once "attendance.php";

$studentObj = new Student();
$attendanceObj = new Attendance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addStudent'])) {
        $studentObj->addStudent(trim($_POST['name']), trim($_POST['email']));
        header('Location: index.php'); exit;
    }
    if (isset($_POST['editStudent'])) {
        $studentObj->updateStudent((int)$_POST['id'], trim($_POST['name']), trim($_POST['email']));
        header('Location: index.php'); exit;
    }
    if (isset($_POST['deleteStudent'])) {
        $studentObj->deleteStudent((int)$_POST['id']);
        header('Location: index.php'); exit;
    }
    if (isset($_POST['addAttendance'])) {
        $attendanceObj->addAttendance((int)$_POST['student_id'], $_POST['status']);
        header('Location: index.php'); exit;
    }
    if (isset($_POST['deleteAttendance'])) {
        $attendanceObj->deleteAttendance((int)$_POST['id']);
        header('Location: index.php'); exit;
    }
}

$students = $studentObj->getStudents();
$attendance = $attendanceObj->getAttendance();
$editStudent = null;
if (isset($_GET['edit_student'])) {
    $id = (int)$_GET['edit_student'];
    foreach ($students as $s) {
        if ($s['id'] == $id) { $editStudent = $s; break; }
    }
}
?>

  
<!DOCTYPE html>  
<html>
<head>
</head>
<body style="font-family:'Comic Sans MS', Arial; background:#D3D3D3 ; padding:18px; color:#222;"> <!-- https://www.w3schools.com/colors/colors_picker.asp para di ko makalituan -->
    <div style="max-width:700px; margin:0 auto;">                                                 <!-- https://www.w3schools.com/html/html_css.asp margin guide....-->
    <h1 style="margin:0 0 10px;">My Class</h1>
    <h3 style="margin:14px 0 6px;">Add Student</h3>
    <form method="POST" style="margin-bottom:12px;">
        <input type="hidden" name="id" value="<?= htmlspecialchars($editStudent['id'] ?? '') ?>">
        <input name="name" type="text" placeholder="Name" required value="<?= htmlspecialchars($editStudent['name'] ?? '') ?>">
        <input name="email" type="email" placeholder="Email" required value="<?= htmlspecialchars($editStudent['email'] ?? '') ?>">
        <?php if ($editStudent): ?>
            <button type="submit" name="editStudent">Save</button>
            <a href="index.php" style="margin-left:8px;">Cancel</a>
        <?php else: ?>
            <button type="submit" name="addStudent">Add</button>
        <?php endif; ?>
        </form>
    <h3 style="margin:14px 0 6px;">Students</h3>
    <table border="10" cellpadding="6" cellspacing="0" style="width:100%; background:#fff ;">
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr>
        <?php foreach ($students as $stu): ?>
            <tr>
                <td><?= htmlspecialchars($stu['id']) ?></td>
                <td><?= htmlspecialchars($stu['name']) ?></td>
                <td><?= htmlspecialchars($stu['email']) ?></td>
                <td>
                    <a href="?edit_student=<?= $stu['id'] ?>">edit</a>
                     <form method="POST" style="display:inline">
                         <input type="hidden" name="id" value="<?= $stu['id'] ?>">
                        <button type="submit" name="deleteStudent" onclick="return confirm('Delete this student?')">delete</button>
                    </form>
                </td>
             </tr>
        <?php endforeach; ?>
    </table>
    <h3 style="margin:14px 0 6px;">Add Attendance</h3>
    <form method="POST" style="margin-bottom:8px;">
         <select name="student_id" required>
            <?php foreach ($students as $stu): ?>
                <option value="<?= htmlspecialchars($stu['id']) ?>"><?= htmlspecialchars($stu['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="status" required>
            <option>Present</option>
            <option>Absent</option>
        </select>
        <button type="submit" name="addAttendance">Add</button>
    </form>
    <h3 style="margin:14px 0 6px;">Attendance</h3>
    <table border="10" cellpadding="6" cellspacing="0" style="width:100%; background:#fff ;">
        <tr><th>ID</th><th>Student</th><th>Status</th><th>Action</th></tr>
        <?php foreach ($attendance as $att): ?>
            <tr>
                <td><?= htmlspecialchars($att['id']) ?></td>
                <td>
                    <?php
                        $sid = $att['student_id'];
                        $sname = null;
                        foreach ($students as $s) if ($s['id'] == $sid) { $sname = $s['name']; break; }
                        echo htmlspecialchars($sname ?? $sid);
                    ?>
                </td>
                <td><?= htmlspecialchars($att['status']) ?></td>
                <td>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="id" value="<?= $att['id'] ?>">
                        <button type="submit" name="deleteAttendance" onclick="return confirm('Delete this record?')">delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
