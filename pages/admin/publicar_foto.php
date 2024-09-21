<?php
session_start();
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    header("Location: ../login");
    exit();
}
$id_usuario = $_SESSION['id_usuario'];
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
        <form class="content" id="fotoForm" onsubmit="return handleSubmitFoto()" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="h5" for="imagem">Fotografia</label>
                <div class="upload-area" onclick="document.getElementById('imagem').click();">
                    <input type="file" name="imagem" id="imagem" accept="image/*" required onchange="previewImage(event)" style="display: none;">
                    <div class="upload-icon">
                        <i class="fas fa-upload"></i>
                    </div>
                    <span>Arraste ou clique para selecionar uma imagem</span>
                </div>
                <div id="image-name" style="margin-top: 10px;"></div>
                <div id="image-preview" style="margin-top: 10px;">
                    <img id="preview" src="#" alt="Pré-visualização da imagem" style="display: none; max-width: 100%;"/>
                </div>
            </div>
            <button type="submit" class="btn btn-success left-0">Publicar Foto</button>
        </form>
    </div>
</div>

<?php include 'template/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('preview');
        const imageName = document.getElementById('image-name');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
            imageName.textContent = file.name; // Exibir o nome da imagem
        } else {
            imagePreview.src = '#';
            imagePreview.style.display = 'none';
            imageName.textContent = ''; // Limpar o nome da imagem
        }
    }

    function handleSubmitFoto() {
        var form = document.getElementById("fotoForm");
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../forms/publicar_foto", true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    alert(response.message);
                    form.reset();
                    document.getElementById('preview').style.display = 'none'; // Esconder a pré-visualização
                    document.getElementById('image-name').textContent = ''; // Limpar o nome da imagem
                } else {
                    alert('Erro ao processar a solicitação. Tente novamente mais tarde.');
                }
            }
        };
        xhr.send(formData);

        return false; // Impedir o envio padrão do formulário
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
