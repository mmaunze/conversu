<?php
session_start();
require_once '../config/ConexaoMySQL.php';
require_once '../config/Criptografia.php';

function logError($message)
{
    error_log($message, 3, 'logfile.log');
}

function gerarUsername($nome, $conn)
{
    // Remove caracteres especiais e divide o nome completo em palavras
    $nome = preg_replace('/[^a-zA-Z0-9\s]/', '', $nome);
    $partesNome = explode(' ', $nome);

    $possibilidades = [];

    // Adiciona as possibilidades conforme a lógica
    $primeiraLetra = substr($partesNome[0], 0, 1);
    $ultimoNome = end($partesNome);

    $possibilidades[] = strtolower($primeiraLetra . $ultimoNome);
    if (count($partesNome) > 2) {
        $penultimoNome = $partesNome[count($partesNome) - 2];
        $possibilidades[] = strtolower($primeiraLetra . $penultimoNome);
    }
    $possibilidades[] = strtolower($primeiraLetra . $partesNome[0]);

    foreach ($partesNome as $parte) {
        $possibilidades[] = strtolower($ultimoNome . $parte);
    }

    $possibilidades[] = strtolower($ultimoNome . $primeiraLetra);

    foreach ($partesNome as $parte) {
        $possibilidades[] = strtolower($parte . $ultimoNome);
    }

    // Verifica a unicidade de cada possibilidade
    foreach ($possibilidades as $username) {
        $sql = "SELECT COUNT(*) AS total FROM utilizador WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['total'] == 0) {
                return $username;
            }
        } else {
            logError("Erro na preparação da consulta de slug: " . $conn->connect_error);
        }
    }

    // Se nenhuma das possibilidades iniciais for única, começa a adicionar números ao final
    $i = 1;
    while (true) {
        foreach ($possibilidades as $base) {
            $username = $base . $i;
            $sql = "SELECT COUNT(*) AS total FROM utilizador WHERE username = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                if ($row['total'] == 0) {
                    return $username;
                }
            } else {
                logError("Erro na preparação da consulta de slug: " . $conn->connect_error);
            }
        }
        $i++;
    }
}

$response = array('status' => '', 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = htmlspecialchars($_POST['nome']);
    $email = htmlspecialchars($_POST['email']);
    $id_tipo_utilizador = htmlspecialchars($_POST['id_tipo_utilizador']);
    $sexo =htmlspecialchars($_POST['sexo']);
    $contacto =htmlspecialchars($_POST['contacto']);
    $conn = new ConexaoMysql();
    $conn->conectar();

    // Verificar se o username é único
    $sqlCheckNome = "SELECT COUNT(*) AS total FROM utilizador WHERE username = ?";
    $stmtCheckNome = $conn->prepare($sqlCheckNome);
    $stmtCheckNome->bind_param("s", $nome);
    $stmtCheckNome->execute();
    $resultCheckNome = $stmtCheckNome->get_result();
    $rowCheckNome = $resultCheckNome->fetch_assoc();

    if ($rowCheckNome['total'] > 0) {
        $response['status'] = 'error';
        $response['message'] = 'Username já existe. Escolha um nome diferente.';
    } else {
        $username = gerarUsername($nome, $conn);

        // Gerar senha usando email
        $senha = password_hash($email, PASSWORD_DEFAULT);

        $sql = "INSERT INTO utilizador (nome, id_tipo_utilizador, username, email, senha,sexo,contacto) VALUES (?, ?, ?, ?, ?,?,?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sissssi", $nome, $id_tipo_utilizador, $username, $email, $senha,$sexo,$contacto);
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Usuário cadastrado com sucesso!';
            } else {
                logError("Erro ao cadastrar usuário: " . $stmt->error);
                $response['status'] = 'error';
                $response['message'] = 'Houve um problema ao cadastrar o usuário. Tente novamente mais tarde.';
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
    exit();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Método de requisição inválido.';
    echo json_encode($response);
}
?>
