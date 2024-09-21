<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: ../login");
    exit();
}

// Verificar se todos os campos necessários foram recebidos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_categoria'], $_POST['descricao'], $_POST['assunto'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $id_categoria = $_POST['id_categoria'];
    $nova_descricao = $_POST['descricao'];
    $novo_assunto = $_POST['assunto'];

    // Incluir arquivo de conexão
    require_once '../config/ConexaoMySQL.php';
    $conn = new ConexaoMysql();
    $conn->conectar();

    // Verificar se o ID do usuário existe no banco de dados
    $sqlVerificaUsuario = "SELECT id FROM utilizador WHERE id = ?";
    $stmt = $conn->prepare($sqlVerificaUsuario);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultVerificaUsuario = $stmt->get_result();

    if ($resultVerificaUsuario->num_rows === 0) {
        $_SESSION = array();
        session_destroy();
        header("Location: ../login");
        exit();
    }

    // Atualizar a categoria no banco de dados
    $sql = "UPDATE categoria SET descricao = ?, assunto = ? WHERE id_categoria = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nova_descricao, $novo_assunto, $id_categoria);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Categoria atualizada com sucesso.";
        $response = array("status" => "success", "message" => "Categoria atualizada com sucesso.");
    } else {
        $_SESSION['erro'] = "Erro ao atualizar categoria.";
        $response = array("status" => "error", "message" => "Erro ao atualizar categoria.");
    }

    // Fechar conexão com o banco de dados
    $stmt->close();
    $conn->fecharConexao();

    // Enviar resposta JSON para o frontend
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Se os campos não foram recebidos corretamente, enviar mensagem de erro
    $response = array("status" => "error", "message" => "Campos necessários não foram recebidos.");
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
