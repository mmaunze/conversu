<?php
session_start();
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: ../login");
    exit();
}

// Caminho para a pasta de imagens
$diretorio = '../../assets/img/portfolio/';
$imagens = array();

// Verificar se o diretório existe
if (is_dir($diretorio)) {
    // Abrir o diretório
    if ($dh = opendir($diretorio)) {
        // Ler os arquivos do diretório
        while (($arquivo = readdir($dh)) !== false) {
            // Verificar se é um arquivo de imagem
            if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $arquivo)) {
                $imagens[] = $arquivo;
            }
        }
        closedir($dh);
    }
} else {
    echo "Diretório não encontrado.";
}

?>

<?php include 'template/header.php'; ?>
<?php include 'template/siderbar.php'; ?>
<div class="main-body">
    <div class="page-wrapper">
        <div class="page-header">
            <div class="page-header-title">
                <h4>Imagens do Portfólio</h4>
            </div>
        </div>
        <div class="page-body">
            <div class="row">
                   <div class="text-center justify-content-between align-center center card user-card col-md-12 mt-5 mb-5">
                <a href="publicar_foto" class="btn btn-outline-success">Publicar uma nova Foto</a>
            </div>
            <div class="row">
                <div class="card-block product-table p-t-35">
                    <div class="row">
                        <?php if (!empty($imagens)) : ?>
                            <?php foreach ($imagens as $imagem) : ?>
                                <div class="col-md-4 mb-4">
                                    <div class="card user-card">
                                        <div class="card-body text-center">
                                            <img class="card-img-top" src="<?php echo 'https://www.conversu.co.mz/assets/img/portfolio/' . $imagem; ?>" alt="<?php echo $imagem; ?>" style="width: 100%; height: auto;">
                                            <p class="card-text mt-2">
                                                <?php echo 'https://www.conversu.co.mz/assets/img/portfolio/' . $imagem; ?>
                                            </p>
                                            <button class="btn btn-outline-primary" onclick="copiarTexto('<?php echo 'https://www.conversu.co.mz/assets/img/portfolio/' . $imagem; ?>')">Copiar URL</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="col-12">
                                <p class="text-center"><strong>Não há imagens disponíveis.</strong></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copiarTexto(texto) {
        const el = document.createElement('textarea');
        el.value = texto;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        alert("URL copiada: " + texto);
    }
</script>

<?php include 'template/footer.php'; ?>
