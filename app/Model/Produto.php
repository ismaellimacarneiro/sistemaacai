<?php
namespace App\Model;

use App\Config\Database;
use PDO;

class Produto {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    /**
     * Lista todos os produtos
     */
    public function listarTodos() {
        $sql = "SELECT * FROM tbprodutos ORDER BY nome ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um produto pelo ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM tbprodutos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * CRIA um novo produto
     * Esse é o método que o seu index.php está procurando
     */
    public function criar($nome, $preco) {
        $sql = "INSERT INTO tbprodutos (nome, preco) VALUES (:nome, :preco)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nome'  => $nome,
            ':preco' => $preco
        ]);
    }

    /**
     * ATUALIZA um produto existente
     */
    public function atualizar($id, $nome, $preco) {
        $sql = "UPDATE tbprodutos SET nome = :nome, preco = :preco WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'    => $id,
            ':nome'  => $nome,
            ':preco' => $preco
        ]);
    }

    /**
     * Exclui um produto
     */
    public function excluir($id) {
        $sql = "DELETE FROM tbprodutos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}