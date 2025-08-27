<?php
require_once "dbconfig.php";

class Attendance extends Database {
    private $table = "attendance";

    public function addAttendance($student_id, $status) {
        return $this->create($this->table, ['student_id' => $student_id, 'status' => $status]);
    }
    public function getAttendance() {
        return $this->read($this->table);
    }
    public function updateAttendance(int $id, int $student_id, string $status): bool {
        return $this->update('attendance', ['student_id' => $student_id, 'status' => $status], 'id', $id);
    }
    public function deleteAttendance(int $id): bool {
        return $this->delete('attendance', 'id', $id);
    }
}
?>
