<?php
require_once "User.php";

class Student extends User {
    private $course_id;

    public function registerStudent($name, $email, $password, $course_id) {
        if ($this->register($name, $email, $password, 'student')) {
            $userId = $this->conn->lastInsertId();
            $stmt = $this->conn->prepare("INSERT INTO students (user_id, course_id) VALUES (?,?)");
            return $stmt->execute([$userId, $course_id]);
        }
        return false;
    }

    public function fileAttendance($student_id)
    {
        $student_id = (int)$student_id;
        if ($student_id <= 0) {
            throw new \InvalidArgumentException('Invalid student id');
        }

        try {
            $stmt = $this->conn->prepare("SELECT id FROM attendance WHERE student_id = ? AND date = CURDATE() LIMIT 1");
            $stmt->execute([$student_id]);
            if ($stmt->fetch()) {
                return false;
            }
            $time_in = date('H:i:s');
            $cutoff = '08:00:00';
            $status = ($time_in <= $cutoff) ? 'on-time' : 'late';
            $stmt = $this->conn->prepare("INSERT INTO attendance (student_id, date, time_in, status) VALUES (?, CURDATE(), ?, ?)");
            $stmt->execute([$student_id, $time_in, $status]);

            return true;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function getAttendanceHistory($student_id) {
        $stmt = $this->conn->prepare("SELECT * FROM attendance WHERE student_id=? ORDER BY date DESC");
        $stmt->execute([$student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function submitExcuseLetter($student_id, $reason, $file_path) {
        $stmt = $this->conn->prepare("INSERT INTO excuse_letters (student_id, date_submitted, reason, file_path) VALUES (?, CURDATE(), ?, ?)");
        return $stmt->execute([$student_id, $reason, $file_path]);
    }

    public function getExcuseLetters($student_id) {
        $stmt = $this->conn->prepare("SELECT * FROM excuse_letters WHERE student_id = ? ORDER BY date_submitted DESC");
        $stmt->execute([$student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
