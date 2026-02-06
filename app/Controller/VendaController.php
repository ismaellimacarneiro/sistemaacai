<?php
namespace App\Controller;
use App\Model\Produto;

class VendaController {
    public function registrarVenda() {
        if ($_POST) {
            $produtoModel = new Produto();
            $dataVenda = $_POST['datacompra'];
            
            // Lógica para pegar valor do dia
            $precoRef = $produtoModel->getPrecoPorData($_POST['produto_id'], $dataVenda);
            
            if ($precoRef) {
                $valorFinal = $precoRef['valor'] * $_POST['peso'];
                // Aqui você chamaria o Model de Venda para dar o INSERT
            }
        }
    }
}