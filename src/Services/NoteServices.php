<?php

namespace App\Services;

use PDO;
use App\Config\Database;

class NoteServices
{

    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {
        $sql  = "SELECT * FROM `notes`";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function create(array $data): string
    {

        $sql = "INSERT INTO `notes` (title, content, color) VALUES (:title, :content, :color)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':title', $data['title'] ?? 'New note');
        $stmt->bindValue(':content', $data['content']);
        $stmt->bindValue(':color', $data['color'] ?? 'FFFFFF');
        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function get(string $id): array|false
    {
        $sql = "SELECT * FROM `notes` WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function update(array $current, array $new): int
    {
        $sql = "UPDATE `notes` SET title = :title, content = :content,
                color = :color, last_modified = DEFAULT
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":title", $new['title'] ?? $current['title']);
        $stmt->bindValue(":content", $new['content'] ?? $current['content']);
        $stmt->bindValue(":color", $new['color'] ?? $current['color']);
        $stmt->bindValue(":id", $current['id'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM `notes` WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }
}
