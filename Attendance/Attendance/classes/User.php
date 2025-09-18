<?php
require_once "Database.php";

class User extends Database {
    protected $id;
    protected $name;
    protected $email;
    protected $role;

    public function register($name, $email, $password, $role) {
        $hashPass = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
        return $stmt->execute([$name, $email, $hashPass, $role]);
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $this->id = $user['id'];
            $this->role = $user['role'];
            return $user;
        }
        return false;
    }
}
