<?php
require_once __DIR__ . "/../../config/Database.php";

class User {

    private $conn;
    private $table = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // REGISTER
    public function register($name, $email, $password) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO " . $this->table . " (name, email, password) 
                VALUES (:name, :email, :password)";

        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                ':name' => htmlspecialchars($name),
                ':email' => htmlspecialchars($email),
                ':password' => $hashedPassword
            ]);
            return true;

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "email_exists";
            }
            return false;
        }
    }

    // LOGIN
    public function login($email, $password) {

        $sql = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    // GET USER BY ID
    public function getUserById($id) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE PROFILE
    public function updateProfile($id, $name, $email) {

        $sql = "UPDATE " . $this->table . " 
                SET name = :name, email = :email 
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':name' => htmlspecialchars($name),
            ':email' => htmlspecialchars($email),
            ':id' => $id
        ]);
    }

    // CHANGE PASSWORD (WITH OLD PASSWORD CHECK)
    public function changePassword($id, $oldPassword, $newPassword) {

        $user = $this->getUserById($id);

        if (!$user || !password_verify($oldPassword, $user['password'])) {
            return "wrong_old_password";
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

        $sql = "UPDATE " . $this->table . " 
                SET password = :password 
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':password' => $hashed,
            ':id' => $id
        ]);

        return true;
    }

    // DELETE ACCOUNT
    public function deleteAccount($id) {

        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}