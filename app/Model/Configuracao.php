<?php
namespace App\Model;

use App\Config\Database;
use PDO;

class Configuracao {
    private $conn;

    public function __construct() {
        // Inicializa a conexão com o banco de dados
        $this->conn = (new Database())->getConnection();
    }

    /**
     * Obtém os dados da loja. 
     * Como o sistema possui apenas uma configuração, filtramos pelo ID 1.
     */
    public function getDados() {
        $sql = "SELECT * FROM tbconfiguracao WHERE id = 1 LIMIT 1";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Atualiza as informações da empresa.
     * A ordem dos parâmetros (:nome, :telefone, :endereco, :site) deve
     * ser rigorosamente seguida para evitar troca de valores no banco.
     */
    public function atualizar($nome, $telefone, $endereco, $site) {
        $sql = "UPDATE tbconfiguracao SET 
                nome_loja = :nome, 
                telefone  = :telefone, 
                endereco  = :endereco, 
                site      = :site 
                WHERE id = 1";
        
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute([
            ':nome'     => $nome,
            ':telefone' => $telefone,
            ':endereco' => $endereco,
            ':site'     => $site
        ]);
    }
}