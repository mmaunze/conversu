<?php
session_start();
require_once '../config/ConexaoMySQL.php';

function logError($message) {
    error_log($message, 3, 'logfile.log');
}

$response = array('status' => '', 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = htmlspecialchars($_POST['titulo']);
    $autor = htmlspecialchars($_POST['autor']);
    $resumo = htmlspecialchars($_POST['resumo']);
    $conteudo = $_POST['conteudo'];
    $categoria = htmlspecialchars($_POST['categoria']);

    $conn = new ConexaoMysql();
    $conn->conectar();
    $sql = "INSERT INTO artigo (titulo, autor, resumo, conteudo, id_categoria) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sissi", $titulo, $autor, $resumo, $conteudo, $categoria);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Artigo submetido com sucesso!';
        } else {
            logError("Erro ao submeter artigo: " . $stmt->error);
            $response['status'] = 'error';
            $response['message'] = 'Houve um problema ao submeter o artigo. Tente novamente mais tarde.';
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