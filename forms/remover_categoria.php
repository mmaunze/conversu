<?php
session_start();
require_once '../config/ConexaoMySQL.php';

$response = array('status' => '', 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_categoria'])) {
        $id_categoria = $_POST['id_categoria'];

        $conn = new ConexaoMysql();
        $conn->conectar();

        $sql = "DELETE FROM categoria WHERE id_categoria = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id_categoria);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'A Categoria foi removida com sucesso!';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Erro ao remover a categoria: ' . $stmt->$error;
            }
            $stmt->close();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Erro na preparação da consulta: ' . $conn->$connect_error();
        }

        $conn->fecharConexao();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'ID da categoria não foi fornecido.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Método de requisição inválido.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>