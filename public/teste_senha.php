<?php
require_once "../app/autoload.php";
$db = new \App\Config\Database();
$conn = $db->getConnection();

$user = 'admin';
$senha_digitada = '123456';

$sql = "SELECT * FROM tbusuarios WHERE usuario = :u";
$stmt = $conn->prepare($sql);
$stmt->execute(['u' => $user]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h3>Teste de Login Manual</h3>";
if (!$dados) {
    die("❌ Erro: Usuário '$user' não encontrado no banco.");
}

echo "Hash no banco: " . $dados['senha'] . "<br>";
echo "Tamanho do hash: " . strlen($dados['senha']) . " caracteres<br>";

if (password_verify($senha_digitada, $dados['senha'])) {
    echo "✅ <b>SUCESSO!</b> A senha 123456 é válida para este hash.";
} else {
    echo "❌ <b>FALHA!</b> A senha não bate com o hash do banco.";
}