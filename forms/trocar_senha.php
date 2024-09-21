<?php
session_start();
require_once '../config/ConexaoMySQL.php';

$response = array();

if (!isset($_POST['user']) || !isset($_POST['senhaAntiga']) || !isset($_POST['novaSenha']) || !isset($_POST['confirmarSenha'])) {
    $response['status'] = 'error';
    $response['message'] = 'Dados incompletos.';
    echo json_encode($response);
    exit();
}

$user_id = $_POST['user'];
$senhaAntiga = $_POST['senhaAntiga'];
$novaSenha = $_POST['novaSenha'];
$confirmarSenha = $_POST['confirmarSenha'];

if ($novaSenha !== $confirmarSenha) {
    $response['status'] = 'error';
    $response['message'] = 'As senhas não coincidem.';
    echo json_encode($response);
    exit();
}

$conn = new ConexaoMySQL();
$conexao = $conn->conectar();

// Verificar a senha antiga
$query = $conexao->prepare("SELECT senha FROM utilizador WHERE id = ?");
$query->bind_param('i', $user_id);
$query->execute();
$result = $query->get_result();
$utilizador = $result->fetch_assoc();

if (!$utilizador || !password_verify($senhaAntiga, $utilizador['senha'])) {
    $response['status'] = 'error';
    $response['message'] = 'Senha antiga incorreta.';
    echo json_encode($response);
    exit();
}

// Atualizar com a nova senha
$novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
$query = $conexao->prepare("UPDATE utilizador SET senha = ? WHERE id = ?");
$query->bind_param('si', $novaSenhaHash, $user_id);

if ($query->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'Senha alterada com sucesso.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Erro ao atualizar a senha.';
}

echo json_encode($response);
$conn->fecharConexao();
?>
