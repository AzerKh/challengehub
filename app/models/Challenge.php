<?php
require_once __DIR__ . "/../../config/Database.php";

class Challenge {

    private $conn;
    private $table = "challenges";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function create($user_id, $title, $description, $category, $deadline, $image) {

        $sql = "INSERT INTO challenges 
                (user_id, title, description, category, deadline, image)
                VALUES (:user_id, :title, :description, :category, :deadline, :image)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':user_id' => $user_id,
            ':title' => htmlspecialchars($title),
            ':description' => htmlspecialchars($description),
            ':category' => htmlspecialchars($category),
            ':deadline' => $deadline,
            ':image' => $image
        ]);
    }

    public function getAll() {
        $sql = "SELECT challenges.*, users.name 
                FROM challenges
                JOIN users ON challenges.user_id = users.id
                ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id, $user_id) {
        $sql = "DELETE FROM challenges 
                WHERE id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':user_id' => $user_id
        ]);
    }
    // GET ONE (for edit)
public function getById($id) {
    $sql = "SELECT * FROM challenges WHERE id = :id LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// UPDATE
public function update($id, $user_id, $title, $description, $category, $deadline, $image) {

    $sql = "UPDATE challenges 
            SET title = :title,
                description = :description,
                category = :category,
                deadline = :deadline,
                image = :image
            WHERE id = :id AND user_id = :user_id";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        ':title' => htmlspecialchars($title),
        ':description' => htmlspecialchars($description),
        ':category' => htmlspecialchars($category),
        ':deadline' => $deadline,
        ':image' => $image,
        ':id' => $id,
        ':user_id' => $user_id
    ]);
}
}