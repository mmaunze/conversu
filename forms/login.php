<?php
session_start();
require_once '../config/ConexaoMySQL.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $conexao = new ConexaoMySQL();
    $conn = $conexao->conectar();

    $sql = "SELECT id, nome, senha FROM utilizador WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verificar a senha usando password_verify
        if (password_verify($senha, $row['senha'])) {
            $_SESSION['id_usuario'] = $row['id'];
            $_SESSION['nome_usuario'] = $row['nome'];
            echo json_encode(array('status' => 'success', 'redirect' => 'admin'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Credenciais inválidas'));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Credenciais inválidas'));
    }

    $stmt->close();
    $conexao->fecharConexao();
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Método de requisição inválido.'));
}
?>
