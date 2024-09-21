<?php
session_start();

require_once '../config/ConexaoMySQL.php';

function logError($message)
{
    error_log($message, 3, 'logfile.log');
}

function gerarSlug($descricao, $conn)
{
    // Função para remover acentos e caracteres especiais
    $descricaoLimpa = removerAcentos($descricao);

    // Remover caracteres especiais e espaços
    $descricaoLimpa = preg_replace('/[^A-Za-z0-9\s-]/', '', $descricaoLimpa);
    $descricaoLimpa = strtolower($descricaoLimpa);

    // Substituir espaços por hífens
    $slug = preg_replace('/\s+/', '-', $descricaoLimpa);

    // Verificar se o slug gerado é único
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

// Função para remover acentos e caracteres especiais
function removerAcentos($string)
{
    // Mapa de substituição de caracteres especiais
    $mapa = array(
        'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a',
        'é' => 'e', 'è' => 'e', 'ê' => 'e',
        'í' => 'i', 'ì' => 'i', 'î' => 'i',
        'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o',
        'ú' => 'u', 'ù' => 'u', 'û' => 'u',
        'ç' => 'c',
        // Adicionar mais caracteres conforme necessário
    );

    // Substituir caracteres especiais pelo mapa definido
    $string = strtr($string, $mapa);

    // Remover outros caracteres especiais que não foram mapeados
    $string = preg_replace('/[^A-Za-z0-9\s]/', '', $string);

    return $string;
}


$response = array('status' => '', 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = htmlspecialchars($_POST['descricao']);
    $assunto = htmlspecialchars($_POST['assunto']);
    $autor = htmlspecialchars($_POST['autor']);
    $conn = new ConexaoMysql();
    $conn->conectar();

    // Verificar se o nome da categoria é único
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

        $sql = "INSERT INTO categoria (descricao, assunto, slug, autor) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssi", $descricao, $assunto, $slug, $autor);
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Categoria cadastrada com sucesso!';
            } else {
                logError("Erro ao cadastrar categoria: " . $stmt->error);
                $response['status'] = 'error';
                $response['message'] = 'Houve um problema ao cadastrar a categoria. Tente novamente mais tarde.';
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
