<?php
namespace App\Model;

use App\Config\Database;
use PDO;

class Venda {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    /**
     * Registra a venda e calcula o valor total no servidor
     */
    public function registrarVenda($cliente_id, $produto_id, $peso, $forma_pagamento, $responsavel) {
        try {
            // Busca o preço para calcular o total com precisão
            $stmtProd = $this->conn->prepare("SELECT preco FROM produtos WHERE id = :id");
            $stmtProd->execute([':id' => $produto_id]);
            $preco = $stmtProd->fetchColumn();

            if (!$preco) return false;

            $valor_total = $preco * $peso;

            $sql = "INSERT INTO tbvendas (cliente_id, produto_id, peso, valor_total, forma_pagamento, responsavel, data_venda) 
                    VALUES (:cliente, :produto, :peso, :total, :pagamento, :resp, CURDATE())";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':cliente'   => $cliente_id,
                ':produto'   => $produto_id,
                ':peso'      => $peso,
                ':total'     => $valor_total,
                ':pagamento' => $forma_pagamento,
                ':resp'      => $responsavel
            ]);
        } catch (\PDOException $e) {
            // Em caso de erro, você pode verificar o log do servidor
            return false;
        }
    }

    /**
     * Retorna totais para os cards do Dashboard
     */
    public function getTotaisPeriodo($inicio, $fim) {
        $sql = "SELECT SUM(valor_total) as valor, COUNT(id) as quantidade 
                FROM tbvendas WHERE data_venda BETWEEN :i AND :f";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':i' => $inicio, ':f' => $fim]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return [
            'valor' => $res['valor'] ?? 0, 
            'quantidade' => $res['quantidade'] ?? 0
        ];
    }

    /**
     * Lista vendas filtradas para a tabela lateral do PDV
     */
    public function listarVendasFiltradas($inicio, $fim, $resp = '') {
        $sql = "SELECT v.*, c.nome as cliente, p.nome as produto 
                FROM tbvendas v 
                JOIN clientes c ON v.cliente_id = c.id 
                JOIN produtos p ON v.produto_id = p.id 
                WHERE v.data_venda BETWEEN :i AND :f";
        
        $params = [':i' => $inicio, ':f' => $fim];
        if (!empty($resp)) {
            $sql .= " AND v.responsavel LIKE :r";
            $params[':r'] = "%$resp%";
        }

        $stmt = $this->conn->prepare($sql . " ORDER BY v.id DESC");
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM tbvendas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function excluir($id) {
        return $this->conn->prepare("DELETE FROM tbvendas WHERE id = :id")->execute([':id' => $id]);
    }
}