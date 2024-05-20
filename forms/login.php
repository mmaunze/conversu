<?php
session_start();
require_once '../config/ConexaoMySQL.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $conexao = new ConexaoMysql();

    $conn = $conexao->conectar();
    $sql = "SELECT id, nome FROM utilizador WHERE email = ? AND senha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['id_usuario'] = $row['id'];
        $_SESSION['nome_usuario'] = $row['nome'];
        $stmt->close();
        $conexao->fecharConexao();
        echo json_encode(array('status' => 'success', 'redirect' => 'admin'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Credenciais inválidas'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Método de requisição inválido.'));
}
