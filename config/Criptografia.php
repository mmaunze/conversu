<?php

function cifrarSenha($senha) {
    $hash = password_hash($senha, PASSWORD_DEFAULT);
    if ($hash === false) {
        throw new Exception("Erro ao cifrar a senha.");
    }
    return $hash;
}

function verificarSenha($senha, $hash) {
    return password_verify($senha, $hash);
}

?>
