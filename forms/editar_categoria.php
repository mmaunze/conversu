<?php
session_start();
require_once '../config/ConexaoMySQL.php';

function logError($message)
{
    error_log($message, 3, 'logfile.log');
}

function gerarSlug($descricao, $conn)
{
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($descricao));

    $sql = "SELECT COUNT(*) AS total FROM categoria WHERE slug = ?";
    $stmt = $conn->prepare($sql);
    $uniqueSlug = $slug;
    $i = 1;

    if ($stmt) {
        while (true) {
            $stmt->bind_param("s", $uniqueSlug);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['total'] == 0) {
                break;
            }

            $uniqueSlug = $slug . '-' . $i;
            $i++;
        }

        $stmt->close();
    } else {
        logError("Erro na preparação da consulta de slug: " . $conn->connect_error);
    }

    return $uniqueSlug;
}

$response = array('status' => '', 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_categoria = $_POST['id_categoria'];
    $descricao = htmlspecialchars($_POST['descricao']);
    $assunto = htmlspecialchars($_POST['assunto']);

    $conn = new ConexaoMysql();
    $conn->conectar();

    $sqlCheckNome = "SELECT COUNT(*) AS total FROM categoria WHERE descricao = ?";
    $stmtCheckNome = $conn->prepare($sqlCheckNome);
    $stmtCheckNome->bind_param("s", $descricao);
    $stmtCheckNome->execute();
    $resultCheckNome = $stmtCheckNome->get_result();
    $rowCheckNome = $resultCheckNome->fetch_assoc();

    if ($rowCheckNome['total'] > 0) {
        $response['status'] = 'error';
        $response['message'] = 'Nome da categoria já existe. Escolha um nome diferente.';
    } else {
        $slug = gerarSlug($descricao, $conn);

        $sql = "UPDATE categoria 
        SET descricao = ? , assunto = ?, slug = ? WHERE id_categoria = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssi", $descricao, $assunto, $slug, $id_categoria);
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Categoria actualizada com sucesso!';
            } else {
                logError("Erro ao actulizar categoria: " . $stmt->error);
                $response['status'] = 'error';
                $response['message'] = 'Houve um problema ao actualizar a categoria. Tente novamente mais tarde.';
            }
            $stmt->close();
        } else {
            logError("Erro na preparação da consulta: " . $conn->$connect_error);
            $response['status'] = 'error';
            $response['message'] = 'Houve um problema ao preparar a consulta. Tente novamente mais tarde.';
        }
    }

    $stmtCheckNome->close();
    $conn->fecharConexao();
    echo json_encode($response);
} else {
    $response['status'] = 'error';
    $response['message'] = 'Método de requisição inválido.';
    echo json_encode($response);
}
