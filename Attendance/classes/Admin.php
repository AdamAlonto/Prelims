<?php
require_once "User.php";

class Admin extends User {
    public function addCourse($name, $year) {
        $stmt = $this->conn->prepare("INSERT INTO courses (name, year_level) VALUES (?,?)");
        return $stmt->execute([$name, $year]);
    }

    public function editCourse($course_id, $name, $year) {
        $stmt = $this->conn->prepare("UPDATE courses SET name=?, year_level=? WHERE id=?");
        return $stmt->execute([$name, $year, $course_id]);
    }

    public function viewAttendance($course_id, $year_level) {
        $stmt = $this->conn->prepare("
            SELECT u.name, a.date, a.time_in, a.status
            FROM attendance a
            JOIN students s ON a.student_id = s.id
            JOIN users u ON s.user_id = u.id
            JOIN courses c ON s.course_id = c.id
            WHERE c.id=? AND c.year_level=? 
            ORDER BY a.date DESC
        ");
        $stmt->execute([$course_id, $year_level]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
