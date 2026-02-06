<?php
namespace App\Controller;
use App\Model\Usuario;

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userInput = trim($_POST['usuario']);
            $passInput = trim($_POST['senha']);

            $model = new Usuario();
            $usuario = $model->getByUsername($userInput);

            // TESTE DE EMERGÊNCIA: 
            // Primeiro tenta a criptografia, se falhar, tenta texto puro "123456"
            if ($usuario) {
                $senhaValida = password_verify($passInput, $usuario['senha']) || ($passInput === '123456');

                if ($senhaValida) {
                    $_SESSION['logado'] = true;
                    $_SESSION['usuario_nome'] = $usuario['usuario'];
                    header("Location: index.php?route=home");
                    exit;
                }
            }
            
            header("Location: index.php?route=login&erro=1");
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?route=login");
        exit;
    }
}