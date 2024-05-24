<?php
session_start();
require_once '../config/ConexaoMySQL.php';

function logError($message) {
    error_log($message, 3, 'logfile.log');
}

function gerarSlug ($nome){
    
}

$response = array('status' => '', 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = htmlspecialchars($_POST['nome']);
    $assunto = htmlspecialchars($_POST['assunto']);
    $slug = gerarSlug($titulo);
    $conn = new ConexaoMysql();
    $conn->conectar();
    $sql = "INSERT INTO categoria (nome, assunto, slug) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $nome, $assunto, $slug);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Categoria Cadastrada com sucesso!';
        } else {
            logError("Erro ao submeter artigo: " . $stmt->error);
            $response['status'] = 'error';
            $response['message'] = 'Houve um problema ao cadastrar a categoria. Tente novamente mais tarde.';
        }
        $stmt->close();
    } else {
        logError("Erro na preparação da consulta: " . $conn->$connect_error);
        $response['status'] = 'error';
        $response['message'] = 'Houve um problema ao preparar a consulta. Tente novamente mais tarde.';
    }

    $conn->fecharConexao();
    echo json_encode($response);
    exit();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Método de requisição inválido.';
    echo json_encode($response);
    exit();
}
?>