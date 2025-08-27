<?php
class Database {
    private $host = "localhost";
    private $db   = "School Testing";
    private $user = "root";
    private $pass = "";
    protected $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    public function create($table, $data) {
        $fields = implode(",", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
    public function read($table) {
        $sql = "SELECT * FROM $table";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update($table, $data, $idField, $id) {
        $set = "";
        foreach($data as $key => $value) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ", ");
        $sql = "UPDATE $table SET $set WHERE $idField = :id";
        $stmt = $this->conn->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }
    public function delete($table, $idField, $id) {
        $sql = "DELETE FROM $table WHERE $idField = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>
