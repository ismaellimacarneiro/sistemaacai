<?php
namespace App\Model;
use App\Config\Database;

class Usuario {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function getByUsername($username) {
        $query = "SELECT * FROM tbusuarios WHERE usuario = :usuario LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario', $username);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}