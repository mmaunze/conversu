<?php
session_start(); 

require_once '../config/ConexaoMySQL.php';

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


    // Instanciar a conexão com o banco de dados
    $conn = new ConexaoMysql();
    $conn->conectar();

    // Preparar a consulta SQL para atualização
    $sql = "UPDATE artigo SET titulo = ?, autor = ?, resumo = ?, conteudo = ?, id_categoria = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sisssi", $titulo, $autor, $resumo, $conteudo, $categoria, $id);

        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Artigo atualizado com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao atualizar artigo: " . $stmt->error;
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
