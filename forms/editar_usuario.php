<?php
session_start();
header('Content-Type: application/json'); // Define o cabeçalho para JSON

require_once '../config/ConexaoMySQL.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $contacto = $_POST['contacto'];
    $sexo = $_POST['sexo'];
    $id_tipo_utilizador = $_POST['id_tipo_utilizador'];

    $conn = new ConexaoMysql();
    $conn->conectar();

    $sql = "UPDATE utilizador SET nome = ?, email = ?, contacto = ?, sexo = ?, id_tipo_utilizador = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssissi", $nome, $email, $contacto, $sexo, $id_tipo_utilizador, $id);
        if ($stmt->execute()) {
            $response['status'] = "success";
            $response['message'] = "Usuário atualizado com sucesso!";
        } else {
            $response['status'] = "error";
            $response['message'] = "Erro ao atualizar usuário. Tente novamente.";
        }
        $stmt->close();
    } else {
        $response['status'] = "error";
        $response['message'] = "Erro na preparação da consulta SQL.";
    }

    $conn->fecharConexao();
} else {
    $response['status'] = "error";
    $response['message'] = "Método de solicitação inválido.";
}

echo json_encode($response);
exit();
?>
