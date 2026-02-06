<?php
namespace App\Controller;

class HomeController {
    public function index() {
        // Verifica se o usuário tem o "crachá" de logado na sessão
        if (!isset($_SESSION['logado'])) {
            header("Location: index.php?route=login");
            exit;
        }

        $nomeUsuario = $_SESSION['usuario_nome'] ?? 'Usuário';
        
        // Carrega a interface visual
        include "../views/home.php";
    }
}