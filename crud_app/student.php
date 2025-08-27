<?php
require_once "dbconfig.php";

class Student extends Database {
    private $table = "students";

    public function addStudent($name, $email) {
        return $this->create($this->table, ['name' => $name, 'email' => $email]);
    }
    public function getStudents() {
        return $this->read($this->table);
    }
    public function updateStudent(int $id, string $name, string $email): bool {
        return $this->update('students', ['name' => $name, 'email' => $email], 'id', $id);
    }
    public function deleteStudent(int $id): bool {
        return $this->delete('students', 'id', $id);
    }
}
?>
