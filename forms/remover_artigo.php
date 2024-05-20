<?php
session_start(); 

require_once '../config/ConexaoMySQL.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
  
    $conn = new ConexaoMysql();
    $conn->conectar();

    $sql = "DELETE FROM artigo WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Artigo removido com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao remover o artigo: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['erro'] = "Erro na preparação da consulta: " . $conn->$connect_error;
    }

    $conn->fecharConexao();
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
} else {
    $_SESSION['erro'] = "Método de requisição inválido.";
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}
?>
