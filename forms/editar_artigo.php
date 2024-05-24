<?php
session_start(); 

require_once '../config/ConexaoMySQL.php';

$response = array('status' => '', 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $resumo = $_POST['resumo'];
    $conteudo = $_POST['conteudo'];
    $categoria = $_POST['categoria'];

    $titulo = htmlspecialchars($titulo);
    $autor = htmlspecialchars($autor);
    $resumo = htmlspecialchars($resumo);
    $categoria = htmlspecialchars($categoria);

    $conn = new ConexaoMysql();
    $conn->conectar();

    $sql = "UPDATE artigo SET titulo = ?, autor = ?, resumo = ?, conteudo = ?, id_categoria = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sisssi", $titulo, $autor, $resumo, $conteudo, $categoria, $id);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Artigo atualizado com sucesso!';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Erro ao atualizar artigo: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Erro na preparação da consulta: ' . $conn->$connect_error;
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
