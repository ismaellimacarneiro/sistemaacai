<?php
require_once "../app/Config/Database.php";
require_once "../app/Model/Cliente.php";
require_once "../app/Model/Produto.php";
require_once "../app/Model/Venda.php";
require_once "../app/Model/Configuracao.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$clienteModel = new \App\Model\Cliente();
$produtoModel = new \App\Model\Produto();
$vendaModel   = new \App\Model\Venda();
$configModel  = new \App\Model\Configuracao();

$route = $_GET['route'] ?? 'dashboard';

switch ($route) {
    case 'dashboard':
        $hoje = date('Y-m-d');
        $primeiroDiaMes = date('Y-m-01');
        $ultimoDiaMes   = date('Y-m-t');

        $totaisDia = $vendaModel->getTotaisPeriodo($hoje, $hoje);
        $totaisMes = $vendaModel->getTotaisPeriodo($primeiroDiaMes, $ultimoDiaMes);

        $paginaInterna = "dashboard.php";
        include "../views/home.php";
        break;

    case 'vendas':
        $dataInicio = $_GET['data_inicio'] ?? date('Y-m-d');
        $dataFim    = $_GET['data_f'] ?? date('Y-m-d');
        $buscaResp  = $_GET['busca_responsavel'] ?? '';
        
        $idEditar    = $_GET['id'] ?? null;
        $vendaEditar = $idEditar ? $vendaModel->getById($idEditar) : null;
        
        $listaClientes = $clienteModel->listarTodos();
        $listaProdutos = $produtoModel->listarTodos();
        $vendasFiltradas = $vendaModel->listarVendasFiltradas($dataInicio, $dataFim, $buscaResp);
        
        $paginaInterna = "vendas.php";
        include "../views/home.php";
        break;

    case 'salvar-venda':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id          = $_POST['id'] ?? null;
            $cliente_id  = $_POST['cliente_id'];
            $produto_id  = $_POST['produto_id'];
            // CORREÇÃO: Aceita 0,500 ou 0.500
            $peso        = str_replace(',', '.', $_POST['peso']); 
            $pagamento   = $_POST['forma_pagamento'];
            $responsavel = !empty($_POST['responsavel']) ? $_POST['responsavel'] : 'Balcão';

            if (!empty($id)) {
                $vendaModel->atualizar($id, $cliente_id, $produto_id, $peso, $pagamento, $responsavel);
            } else {
                $vendaModel->registrarVenda($cliente_id, $produto_id, $peso, $pagamento, $responsavel);
            }
        }
        header("Location: index.php?route=vendas");
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
            $id = $_POST['id'] ?? null;
            $nome = $_POST['nome'];
            $tel = $_POST['telefone'] ?? '';
            $id ? $clienteModel->atualizar($id, $nome, $tel) : $clienteModel->criar($nome, $tel);
        }
        header("Location: index.php?route=clientes");
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
            $id = $_POST['id'] ?? null;
            $nome = $_POST['nome'];
            $preco = str_replace(',', '.', $_POST['preco']);
            $id ? $produtoModel->atualizar($id, $nome, $preco) : $produtoModel->criar($nome, $preco);
        }
        header("Location: index.php?route=produtos");
        exit;

    case 'configuracoes':
        $dadosLoja = $configModel->getDados();
        $paginaInterna = "configuracoes.php";
        include "../views/home.php";
        break;

    case 'salvar-configuracao':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $configModel->atualizar($_POST['nome_loja'], $_POST['telefone'], $_POST['endereco'], $_POST['site']);
        }
        header("Location: index.php?route=configuracoes&success=1");
        exit;

    case 'excluir-venda':
    case 'excluir-cliente':
    case 'excluir-produto':
        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($route == 'excluir-venda') $vendaModel->excluir($id);
            if ($route == 'excluir-cliente') $clienteModel->excluir($id);
            if ($route == 'excluir-produto') $produtoModel->excluir($id);
        }
        $origem = str_replace('excluir-', '', $route) . 's';
        header("Location: index.php?route=$origem");
        exit;

    default:
        header("Location: index.php?route=dashboard");
        exit;
}