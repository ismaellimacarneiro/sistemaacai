<?php
namespace App\Model;
use App\Config\Database;

class Configuracao {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function getDados() {
        return $this->conn->query("SELECT * FROM tbconfig WHERE id = 1")->fetch(\PDO::FETCH_ASSOC);
    }

    public function atualizar($nome, $end, $tel, $site) {
        $sql = "UPDATE tbconfig SET nome_loja = :n, endereco = :e, telefone = :t, site = :s WHERE id = 1";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':n'=>$nome, ':e'=>$end, ':t'=>$tel, ':s'=>$site]);
    }
}