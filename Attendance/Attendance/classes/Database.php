<?php
class Database {
    private $host = "localhost";
    private $dbname = "attendance";
    private $username = "root";
    private $password = "";
    public $conn;

    public function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("DB Connection failed: " . $e->getMessage());
        }
    }
}
