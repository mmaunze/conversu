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

// Configuração de paginação
$artigos_por_pagina = 5; // Número de artigos por página
$pagina_atual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($pagina_atual - 1) * $artigos_por_pagina;

// Consulta para buscar os artigos do banco de dados
$sql = "SELECT artigo.*, categoria.descricao AS categoria_descricao, utilizador.nome AS nome_autor
              FROM artigo
              INNER JOIN categoria ON artigo.id_categoria = categoria.id_categoria
              INNER JOIN utilizador ON artigo.autor = utilizador.id
              LIMIT $offset, $artigos_por_pagina ";
$result = $conn->query($sql);

// Consulta para contar o número total de artigos
$sqlCount = "SELECT COUNT(*) AS total_artigos FROM artigo";
$resultCount = $conn->query($sqlCount);
$total_artigos = $resultCount->fetch_assoc()['total_artigos'];
$total_paginas = ceil($total_artigos / $artigos_por_pagina);

// Fechar conexão com o banco de dados
$conn->fecharConexao();
?>

<?php include 'template/header.php'; ?>
<?php include 'template/siderbar.php'; ?>
<div class="main-body">
    <div class="page-wrapper">
        <div class="page-header">
            <div class="page-header-title">
                <h4>Artigos</h4>
            </div>
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="#!">
                            <i class="icofont icofont-home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><span>Artigos</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="page-body">
            <div class="row">
                   <div class="text-center justify-content-between align-center center card user-card col-md-12 mt-5 mb-5">
                <a href="publicar_artigo" class="btn btn-outline-success">Publicar um novo artigo</a>
            </div>
                <div class="card-block product-table p-t-35">
                    <div class="row">
                        <?php if ($result->num_rows > 0) : ?>
                            <?php while ($row = $result->fetch_assoc()) : ?>
                                <div class="col-md-6 mb-6">
                                    <div class="card user-card">
                                        <div class="card-body p-5">
                                            <h5 class="card-title"><?php echo $row['titulo']; ?></h5>
                                            <h6 class="mt-3 card-subtitle mb-2 text-muted"><?php echo $row['nome_autor']; ?></h6>
                                            <h6 class="text-muted"><?php echo $row['categoria_descricao']; ?></h6>
                                            <p class="card-text"><img class="card-img" src="" alt="<?php echo $row['titulo']; ?>" srcset=""></p>
                                            <p class="card-text"><?php echo substr($row['resumo'], 0, 160); ?>...</p>
                                        </div>
                                        <div class="card-footer text-center">
                                            <form action="editar_artigo.php" method="POST" class="d-inline">
                                                <input type="hidden" name="artigo" value="<?php echo $row['id']; ?>">
                                                <button class="btn btn-outline-primary" type="submit">Editar Artigo</button>
                                            </form>
                                            <?php if ($tipo_usuario == 'Administrador') { ?>
                                                <form action="../../forms/remover_artigo.php" method="POST" class="d-inline" onsubmit="return handleRemoverArtigo(this);">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <button class="btn btn-outline-danger" type="submit">Remover Artigo</button>
                                                </form>
                                            <?php } ?>
                                            <form action="/artigo.php" method="POST" class="d-inline">
                                                <input type="hidden" name="artigo" value="<?php echo $row['id']; ?>">
                                                <button class="btn btn-outline-success" type="submit">Ver Artigo</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <div class="col-12">
                                <p class="text-center"><strong>Não há artigos disponíveis.</strong></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
            <!-- Paginação -->
            <nav aria-label="Navegação de página">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_paginas; $i++) : ?>
                        <li class="page-item <?php echo ($i == $pagina_atual) ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
    function handleRemoverArtigo(form) {
        if (confirm('Tem certeza que deseja remover este artigo?')) {
            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", form.action, true);
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.status === "success") {
                                alert(response.message);
                                window.location.reload();
                            } else {
                                alert(response.message);
                            }
                        } catch (e) {
                            console.error("Erro ao processar a resposta JSON: ", e);
                            alert('Erro ao processar a resposta do servidor.');
                        }
                    } else {
                        alert('Erro ao processar a solicitação. Tente novamente mais tarde.');
                    }
                }
            };
            xhr.send(formData);
            return false;
        }
        return false;
    }
</script>

<?php include 'template/footer.php'; ?>