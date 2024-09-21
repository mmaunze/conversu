<?php
session_start();
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    http_response_code(403);
    echo json_encode(['message' => 'Usuário não autenticado.']);
    exit();
}

// Verificar se o formulário foi enviado via AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imagem'])) {
    // Caminho para a pasta de upload
    $diretorio = '../assets/img/portfolio/';
    
    // Verificar se o diretório existe, se não, cria o diretório
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0755, true);
    }

    // Nome do arquivo submetido
    $imagem = $_FILES['imagem']['name'];
    $caminhoImagem = $diretorio . basename($imagem);

    // Verificar o tipo de arquivo
    $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif'];
    $extensaoArquivo = strtolower(pathinfo($caminhoImagem, PATHINFO_EXTENSION));

    if (in_array($extensaoArquivo, $tiposPermitidos)) {
        // Verificar se o arquivo foi enviado sem erros
        if ($_FILES['imagem']['error'] == 0) {
            // Tamanho máximo permitido (em bytes) — Exemplo: 2MB
            if ($_FILES['imagem']['size'] < 20 * 1024 * 1024) {
                // Mover o arquivo para o diretório de destino
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagem)) {
                    http_response_code(200);
                    echo json_encode(['message' => 'Imagem publicada com sucesso!']);
                } else {
                    http_response_code(500);
                    echo json_encode(['message' => 'Erro ao mover o arquivo.']);
                }
            } else {
                http_response_code(413);
                echo json_encode(['message' => 'O arquivo é muito grande. O tamanho máximo é 2MB.']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Erro ao enviar o arquivo.']);
        }
    } else {
        http_response_code(415);
        echo json_encode(['message' => 'Tipo de arquivo não permitido. Use JPG, JPEG, PNG ou GIF.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Nenhuma imagem enviada.']);
}
?>
