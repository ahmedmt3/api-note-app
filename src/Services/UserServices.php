<?php

namespace App\Services;

use App\Config\Database;
use PDO;

class UserServices
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM `users`";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function get(string $id): array|false
    {
        $sql = "SELECT * FROM `users` WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function create(array $data): string
    {

        $sql = "INSERT INTO `users` (`username`, `email`, `password`) VALUES (:username, :email, :password)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':username', $data['username']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':password', sha1($data['password']));
        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM `users` WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function login(array $data): array|false
    {
        $username = $data['username'];
        $password = $data['password'];
        if (!$username || !$password) {
            return false;
        }
        $password = sha1($password);
        $sql = "SELECT * FROM `users` WHERE username = ? AND password = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) unset($user['password']);
        return $user;
    }

    public function checkUser(string $username): bool
    {
        $sql = "SELECT * FROM `users` WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username]);

        return $stmt->rowCount() > 0;
    }
}
