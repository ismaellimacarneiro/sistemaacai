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
     * Registra uma nova venda
     */
    public function registrarVenda($cliente_id, $produto_id, $peso, $forma_pagamento, $responsavel) {
        $sql = "INSERT INTO tbvendas (cliente_id, produto_id, peso, forma_pagamento, responsavel, data_venda) 
                VALUES (:cliente_id, :produto_id, :peso, :forma_pagamento, :responsavel, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':cliente_id'      => $cliente_id,
            ':produto_id'      => $produto_id,
            ':peso'            => $peso,
            ':forma_pagamento' => $forma_pagamento,
            ':responsavel'     => $responsavel
        ]);
    }

    /**
     * Lista as vendas com cálculo dinâmico de valor (Peso vs Unidade)
     */
    public function listarVendasPorData($data) {
        $sql = "SELECT v.*, c.nome as cliente, p.nome as produto, p.preco as preco_unitario
                FROM tbvendas v
                INNER JOIN tbclientes c ON v.cliente_id = c.id
                INNER JOIN tbprodutos p ON v.produto_id = p.id
                WHERE DATE(v.data_venda) = :data
                ORDER BY v.data_venda DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':data' => $data]);
        $vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($vendas as &$v) {
            $nomeProd = strtoupper($v['produto']);
            $preco = (float)$v['preco_unitario'];
            $peso  = (float)$v['peso'];

            // Se for Açaí, multiplica pelo peso
            if (str_contains($nomeProd, 'AÇAÍ') || str_contains($nomeProd, 'ACAI')) {
                $v['valor_total'] = $preco * $peso;
            } else {
                // Se for unidade (água, refri), se o peso for 0 ou vazio, assume 1 unidade
                $v['valor_total'] = ($peso > 0) ? ($preco * $peso) : $preco;
            }
        }
        return $vendas;
    }

    public function getById($id) {
        $sql = "SELECT * FROM tbvendas WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $cliente_id, $produto_id, $peso, $forma_pagamento, $responsavel) {
        $sql = "UPDATE tbvendas SET 
                cliente_id = :cliente_id, 
                produto_id = :produto_id, 
                peso = :peso, 
                forma_pagamento = :forma_pagamento, 
                responsavel = :responsavel 
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'              => $id,
            ':cliente_id'      => $cliente_id,
            ':produto_id'      => $produto_id,
            ':peso'            => $peso,
            ':forma_pagamento' => $forma_pagamento,
            ':responsavel'     => $responsavel
        ]);
    }

    public function excluir($id) {
        $sql = "DELETE FROM tbvendas WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // --- MÉTODOS DO DASHBOARD ---

    public function listarVendasMesAtual() {
        $sql = "SELECT v.peso, p.nome as produto, p.preco 
                FROM tbvendas v 
                INNER JOIN tbprodutos p ON v.produto_id = p.id 
                WHERE MONTH(v.data_venda) = MONTH(CURRENT_DATE()) 
                AND YEAR(v.data_venda) = YEAR(CURRENT_DATE())";
        
        $vendas = $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $totalGeral = 0;

        foreach ($vendas as $v) {
            $nome = strtoupper($v['produto']);
            if (str_contains($nome, 'AÇAÍ') || str_contains($nome, 'ACAI')) {
                $totalGeral += ($v['preco'] * $v['peso']);
            } else {
                $totalGeral += ($v['peso'] > 0) ? ($v['preco'] * $v['peso']) : $v['preco'];
            }
        }
        return $totalGeral;
    }

    public function contarPedidosMesAtual() {
        $sql = "SELECT COUNT(*) as total FROM tbvendas 
                WHERE MONTH(data_venda) = MONTH(CURRENT_DATE()) 
                AND YEAR(data_venda) = YEAR(CURRENT_DATE())";
        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function listarAnuaisGrafico() {
        $sql = "SELECT MONTH(v.data_venda) as mes, v.peso, p.nome as produto, p.preco 
                FROM tbvendas v 
                INNER JOIN tbprodutos p ON v.produto_id = p.id 
                WHERE YEAR(v.data_venda) = YEAR(CURRENT_DATE())";
        
        $vendas = $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $meses = array_fill(1, 12, 0);

        foreach ($vendas as $v) {
            $m = (int)$v['mes'];
            $nome = strtoupper($v['produto']);
            $valor = (str_contains($nome, 'AÇAÍ') || str_contains($nome, 'ACAI')) 
                     ? ($v['preco'] * $v['peso']) 
                     : (($v['peso'] > 0) ? ($v['preco'] * $v['peso']) : $v['preco']);
            $meses[$m] += $valor;
        }

        $resultado = [];
        foreach ($meses as $mes => $total) {
            if ($total > 0) $resultado[] = ['mes' => $mes, 'total' => $total];
        }
        return $resultado;
    }
}