<?php
session_start();
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: ../login");
    exit();
}
$id_usuario = $_SESSION['id_usuario'];

// Incluindo arquivo de conexão
require_once '../../config/ConexaoMySQL.php';
$connection = new ConexaoMysql();
$connection->conectar();

$id_artigo = $_POST['artigo']; // Captura o ID do artigo via POST

// Busca os dados do artigo
$sql_artigo = "SELECT * FROM artigo WHERE id = ?";
$stmt_artigo = $connection->prepare($sql_artigo);
$stmt_artigo->bind_param("i", $id_artigo);
$stmt_artigo->execute();
$result_artigo = $stmt_artigo->get_result();

if ($result_artigo->num_rows === 0) {
    echo "<p>Nenhum artigo encontrado.</p>";
    exit();
}

$artigo = $result_artigo->fetch_assoc();
$connection->fecharConexao();
?>

<?php include 'template/header.php'; ?>
<?php include 'template/siderbar.php'; ?>
<div class="page-body">
    <div class="container">
        <?php if (isset($_SESSION['mensagem'])) : ?>
            <div class="mensagem-sucesso h3 text-success"><?php echo $_SESSION['mensagem']; ?></div>
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['erro'])) : ?>
            <div class="mensagem-erro"><?php echo $_SESSION['erro']; ?></div>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>
        <form class="content" id="artigoForm" onsubmit="return handleSubmitArtigo()" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_artigo" value="<?php echo htmlspecialchars($artigo['id']); ?>"> <!-- Campo oculto para o ID do artigo -->
            <div class="form-group">
                <label class="h5" for="titulo">Título</label>
                <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo htmlspecialchars($artigo['titulo']); ?>" required>
                <input type="hidden" name="autor" value="<?php echo $id_usuario; ?>" id="autor">
            </div>
            <div class="form-group">
                <label class="h5" for="imagem">Fotografia</label>
                <div class="upload-area" onclick="document.getElementById('imagem').click();">
                    <input type="file" name="imagem" id="imagem" accept="image/*" required onchange="previewImage(event)" style="display: none;">
                    <div class="upload-icon">
                        <i class="fas fa-upload"></i>
                    </div>
                    <span>Arraste ou clique para selecionar uma imagem</span>
                </div>
                <div id="image-preview" style="margin-top: 10px;">
                    <img id="preview" src="<?php echo htmlspecialchars($artigo['imagem']); ?>" alt="Pré-visualização da imagem" style="max-width: 100%;"/>
                </div>
            </div>
            <div class="form-group">
                <label class="h5" for="categoria">Categoria</label>
                <select name="categoria" id="categoria" class="form-control">
                    <?php
                    $conn = new ConexaoMysql();
                    $conn->conectar();
                    $sql = "SELECT * FROM categoria";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $selected = ($row['id_categoria'] == $artigo['id_categoria']) ? 'selected' : '';
                            echo "<option value=\"{$row['id_categoria']}\" $selected>{$row['descricao']}</option>";
                        }
                    } else {
                        echo "<strong>Não há categorias disponíveis.</strong>";
                    }
                    $conn->fecharConexao();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label class="h5" for="resumo">Resumo</label>
                <textarea class="form-control" name="resumo" id="resumo" required><?php echo htmlspecialchars($artigo['resumo']); ?></textarea>
            </div>
            <div class="form-group">
                <label class="h5" for="editor">Conteúdo</label>
                <div id="editor" class="pell"></div>
            </div>
            <input type="hidden" name="conteudo" id="conteudo" value="<?php echo htmlspecialchars($artigo['conteudo']); ?>">
            <button type="submit" class="btn btn-success left-0">Publicar Artigo</button>
        </form>
    </div>
</div>
<?php include 'template/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script>
    var editor = window.pell.init({
        element: document.getElementById('editor'),
        defaultParagraphSeparator: 'p',
        onChange: function(html) {
            document.getElementById('conteudo').value = html; // Atualiza o campo escondido
        }
    });
    editor.content.innerHTML = <?php echo json_encode($artigo['conteudo']); ?>; // Preencher o editor com o conteúdo do artigo

    function handleSubmitArtigo() {
        var form = document.getElementById("artigoForm");
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../forms/editar_artigo", true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === "success") {
                        alert(response.message);
                        form.reset();
                        document.getElementById('preview').style.display = 'none'; // Esconder a pré-visualização
                    } else {
                        alert(response.message);
                    }
                } else {
                    alert('Erro ao processar a solicitação. Tente novamente mais tarde.');
                }
            }
        };
        xhr.send(formData);

        return false; // Impedir o envio padrão do formulário
    }

    function previewImage(event) {
        const imagePreview = document.getElementById('preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = '#';
            imagePreview.style.display = 'none';
        }
    }
</script>

<style>
    .upload-area {
        border: 2px dashed #007bff;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        background-color: #f8f9fa;
        transition: background-color 0.3s;
    }

    .upload-area:hover {
        background-color: #e9ecef;
    }

    .upload-icon {
        font-size: 50px;
        color: #007bff;
        margin-bottom: 10px;
    }

    .upload-area span {
        display: block;
        color: #6c757d;
        font-weight: bold;
    }
</style>
