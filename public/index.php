<?php
/**
 * SISTEMA MAIS AÇAÍ - CONTROLLER PRINCIPAL
 * Versão Final com correção do campo Responsável
 */

// 1. Inclusão dos arquivos necessários
require_once "../app/Config/Database.php";
require_once "../app/Model/Cliente.php";
require_once "../app/Model/Produto.php";
require_once "../app/Model/Venda.php";
require_once "../app/Model/Configuracao.php";

// 2. Iniciar Sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Instanciar Modelos
$clienteModel = new \App\Model\Cliente();
$produtoModel = new \App\Model\Produto();
$vendaModel   = new \App\Model\Venda();
$configModel  = new \App\Model\Configuracao();

// 4. Capturar Rota
$route = $_GET['route'] ?? 'dashboard';

switch ($route) {
    case 'dashboard':
        $totalVendasMes = $vendaModel->listarVendasMesAtual();
        $totalPedidos   = $vendaModel->contarPedidosMesAtual();
        $dadosGrafico   = $vendaModel->listarAnuaisGrafico();
        $paginaInterna  = "dashboard.php";
        include "../views/home.php";
        break;

    case 'vendas':
        $dataBusca = $_GET['data_venda'] ?? date('Y-m-d');
        $idEditar  = $_GET['id'] ?? null;
        $vendaEditar = $idEditar ? $vendaModel->getById($idEditar) : null;
        
        $listaClientes = $clienteModel->listarTodos();
        $listaProdutos = $produtoModel->listarTodos();
        $vendasFiltradas = $vendaModel->listarVendasPorData($dataBusca);
        
        $paginaInterna = "vendas.php";
        include "../views/home.php";
        break;

    case 'salvar-venda':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id              = $_POST['id'] ?? null;
            $cliente_id      = $_POST['cliente_id'];
            $produto_id      = $_POST['produto_id'];
            $peso            = str_replace(',', '.', $_POST['peso']); 
            $forma_pagamento = $_POST['forma_pagamento'];
            
            // CORREÇÃO: Pega o nome digitado no formulário. Se vazio, usa o login.
            $responsavel     = !empty($_POST['responsavel']) ? $_POST['responsavel'] : ($_SESSION['usuario_nome'] ?? 'Balcão');

            if (!empty($id)) {
                $vendaModel->atualizar($id, $cliente_id, $produto_id, $peso, $forma_pagamento, $responsavel);
            } else {
                $vendaModel->registrarVenda($cliente_id, $produto_id, $peso, $forma_pagamento, $responsavel);
            }
        }
        header("Location: index.php?route=vendas");
        exit;

    case 'produtos':
        $idEditar = $_GET['id'] ?? null;
        $produtoEditar = $idEditar ? $produtoModel->getById($idEditar) : null;
        $listaProdutos = $produtoModel->listarTodos();
        $paginaInterna = "produtos.php";
        include "../views/home.php";
        break;

    case 'salvar-produto':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id    = $_POST['id'] ?? null;
            $nome  = $_POST['nome'];
            $preco = str_replace(',', '.', $_POST['preco']);

            if (!empty($id)) {
                $produtoModel->atualizar($id, $nome, $preco);
            } else {
                $produtoModel->criar($nome, $preco);
            }
        }
        header("Location: index.php?route=produtos");
        exit;

    case 'clientes':
        $idEditar = $_GET['id'] ?? null;
        $clienteEditar = $idEditar ? $clienteModel->getById($idEditar) : null;
        $listaClientes = $clienteModel->listarTodos();
        $paginaInterna = "clientes.php";
        include "../views/home.php";
        break;

    case 'salvar-cliente':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id       = $_POST['id'] ?? null;
            $nome     = $_POST['nome'];
            $telefone = $_POST['telefone'] ?? '';

            if (!empty($id)) {
                $clienteModel->atualizar($id, $nome, $telefone);
            } else {
                $clienteModel->criar($nome, $telefone);
            }
        }
        header("Location: index.php?route=clientes");
        exit;

    case 'excluir-venda':
    case 'excluir-produto':
    case 'excluir-cliente':
        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($route == 'excluir-venda') $vendaModel->excluir($id);
            if ($route == 'excluir-produto') $produtoModel->excluir($id);
            if ($route == 'excluir-cliente') $clienteModel->excluir($id);
        }
        $redirect = str_replace('excluir-', '', $route) . 's';
        header("Location: index.php?route=$redirect");
        exit;

    case 'configuracoes':
        $dadosLoja    = $configModel->getDados();
        $paginaInterna = "configuracoes.php";
        include "../views/home.php";
        break;

    default:
        header("Location: index.php?route=dashboard");
        exit;
}