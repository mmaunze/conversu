<?php
session_start();
require_once '../config/ConexaoMySQL.php';

function logError($message)
{
    error_log($message, 3, 'logfile.log');
}

$response = array('status' => '', 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $conn = new ConexaoMysql();
        $conn->conectar();

        $sql = "DELETE FROM utilizador WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Usuario removido com sucesso!';
            } else {
                logError("Erro ao remover categoria: " . $stmt->error);
                $response['status'] = 'error';
                $response['message'] = 'Erro ao remover o usuario: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            logError("Erro na preparação da consulta: " . $conn->$connect_error);
            $response['status'] = 'error';
            $response['message'] = 'Erro na preparação da consulta: ' . $conn->$connect_error;
        }

        $conn->fecharConexao();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Utilizador não foi fornecido.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Método de requisição inválido.';
}

header('Content-Type: application/json');
echo json_encode($response);
