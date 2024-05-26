<?php
session_start();
// Verificar se o usuário solicitou o logout
if (isset($_GET['sair'])) {
    // Limpar todas as variáveis de sessão
    $_SESSION = array();
    // Destruir a sessão
    session_destroy();
    // Redirecionar para a página de login ou outra página após o logout
    header("Location: ../login");
    exit();
}
