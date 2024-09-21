<?php
session_start();
require_once '../config/ConexaoMySQL.php';

function gerarSlug($descricao, $conn) {
    $descricaoLimpa = removerAcentos($descricao);
    $descricaoLimpa = preg_replace('/[^A-Za-z0-9\s-]/', '', $descricaoLimpa);
    $descricaoLimpa = strtolower($descricaoLimpa);
    $slug = preg_replace('/\s+/', '-', $descricaoLimpa);

    $sql = "SELECT COUNT(*) AS total FROM artigo WHERE permanent_link = ?";
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

function removerAcentos($string) {
    $mapa = array(
        'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a',
        'é' => 'e', 'è' => 'e', 'ê' => 'e',
        'í' => 'i', 'ì' => 'i', 'î' => 'i',
        'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o',
        'ú' => 'u', 'ù' => 'u', 'û' => 'u',
        'ç' => 'c',
    );

    $string = strtr($string, $mapa);
    return preg_replace('/[^A-Za-z0-9\s]/', '', $string);
}

function logError($message) {
    error_log($message, 3, 'logfile.log');
}

$response = array('status' => '', 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Definir o diretório para upload
    $uploadDir = '../assets/img/';
    $titulo = htmlspecialchars($_POST['titulo']);
    $autor = htmlspecialchars($_POST['autor']);
    $resumo = htmlspecialchars($_POST['resumo']);
    $conteudo = $_POST['conteudo'];
    $categoria = htmlspecialchars($_POST['categoria']);
    $id_artigo = intval($_POST['id_artigo']); // Captura o ID do artigo

    // Gerar slug
    $conn = new ConexaoMysql();
    $conn->conectar();
    $slug = gerarSlug($titulo, $conn);
    
    // Buscar a imagem existente
    $sql = "SELECT imagem FROM artigo WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_artigo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imageName = $row['imagem']; // Mantém a imagem existente
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Artigo não encontrado.';
        echo json_encode($response);
        exit();
    }

    // Verificar se a imagem foi enviada
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['imagem']['tmp_name'];
        $imageExtension = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $imageName = $slug . '.' . $imageExtension;
        $destPath = $uploadDir . $imageName;

        // Mover o arquivo para o diretório
        if (!move_uploaded_file($imageTmpPath, $destPath)) {
            $response['status'] = 'error';
            $response['message'] = 'Falha ao mover a imagem. Verifique as permissões da pasta.';
            echo json_encode($response);
            exit();
        }
    }

    // Atualizar no banco de dados
    $sql = "UPDATE artigo SET titulo = ?, autor = ?, resumo = ?, conteudo = ?, id_categoria = ?, imagem = ?, permanent_link = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sississi", $titulo, $autor, $resumo, $conteudo, $categoria, $imageName, $slug, $id_artigo);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Artigo editado com sucesso!';
        } else {
            logError("Erro ao editar artigo: " . $stmt->error);
            $response['status'] = 'error';
            $response['message'] = 'Houve um problema ao editar o artigo. Tente novamente mais tarde.';
        }
        $stmt->close();
    } else {
        logError("Erro na preparação da consulta: " . $conn->connect_error);
        $response['status'] = 'error';
        $response['message'] = 'Houve um problema ao preparar a consulta. Tente novamente mais tarde.';
    }

    $conn->fecharConexao();
    echo json_encode($response);
} else {
    $response['status'] = 'error';
    $response['message'] = 'Método de requisição inválido.';
    echo json_encode($response);
}
