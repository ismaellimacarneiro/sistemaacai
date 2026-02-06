<?php
namespace App\Model;

use App\Config\Database;
use PDO;

class Cliente {
    private $conn;

    public function __construct() {
        // Certifique-se de que a classe Database está correta em app/Config/Database.php
        $this->conn = (new Database())->getConnection();
    }

    /**
     * Lista todos os clientes
     */
    public function listarTodos() {
        $sql = "SELECT * FROM tbclientes ORDER BY nome ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um cliente pelo ID para edição
     */
    public function getById($id) {
        $sql = "SELECT * FROM tbclientes WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cria um novo cliente
     * Removi o $id do parâmetro para bater com o seu index.php (Linha 114)
     */
    public function criar($nome, $telefone) {
        $sql = "INSERT INTO tbclientes (nome, telefone) VALUES (:nome, :telefone)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nome'     => $nome,
            ':telefone' => $telefone
        ]);
    }

    /**
     * Atualiza um cliente existente
     */
    public function atualizar($id, $nome, $telefone) {
        $sql = "UPDATE tbclientes SET nome = :nome, telefone = :telefone WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'       => $id,
            ':nome'     => $nome,
            ':telefone' => $telefone
        ]);
    }

    /**
     * Exclui um cliente
     */
    public function excluir($id) {
        $sql = "DELETE FROM tbclientes WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}