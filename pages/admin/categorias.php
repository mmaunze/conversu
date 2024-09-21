<?php
session_start();
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: ../login");
    exit();
}
$id_usuario = $_SESSION['id_usuario'];

require_once '../../config/ConexaoMySQL.php';
$conn = new ConexaoMysql();
$conn->conectar();

$sqlCategoriasArtigos = "
    SELECT c.descricao, c.slug, COUNT(a.id) AS total_artigos
    FROM categoria c
    LEFT JOIN artigo a ON c.id_categoria = a.id_categoria
    GROUP BY c.descricao, c.slug";
$resultCategoriasArtigos = $conn->query($sqlCategoriasArtigos);

$query_tu = "SELECT tu.descricao 
FROM tipo_utilizador tu, utilizador u 
WHERE u.id_tipo_utilizador = tu.id_tipo_utilizador AND u.id = ?";

$stmt_tu = $conn->prepare($query_tu);
$stmt_tu->bind_param("i", $id_usuario);
$stmt_tu->execute();
$stmt_tu->bind_result($tipo_usuario);
$stmt_tu->fetch();
$stmt_tu->close();

if (!(($tipo_usuario == "Administrador") || ($tipo_usuario == "Moderador"))) {
    $_SESSION = array();
    session_destroy();
    header("Location: ../login");
    exit();
}

$conn->fecharConexao();
?>

<?php include 'template/header.php' ?>
<?php include 'template/siderbar.php' ?>
<div class="main-body">
    <div class="page-wrapper">
        <div class="page-header">
            <div class="page-header-title">
                <h4>Categorias</h4>
            </div>
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="#!">
                            <i class="icofont icofont-home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><span>Home/Artigos/Categorias</span></li>
                </ul>
            </div>
        </div>
        <div class="page-body">
            <div class="row">
                <?php
                if ($resultCategoriasArtigos->num_rows > 0) {
                    while ($row = $resultCategoriasArtigos->fetch_assoc()) {
                ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="card counter-card-<?php echo rand(1, 3) ?> user-card mb-4">
                                <div class="card-block-big">
                                    <div class="row">
                                        <div class="col-6 counter-card-icon">
                                            <i class="icofont <?php echo ["icofont-chart-bar-graph", "icofont-chart", "icofont-chart-radar-graph", "icofont-chart-flow-1"][array_rand(["icofont-chart-bar-graph", "icofont-chart", "icofont-chart-radar-graph", "icofont-chart-flow-1"])]; ?>"></i>
                                        </div>
                                        <div class="col-6 text-right">
                                            <div class="counter-card-text">
                                                <h3><?php echo $row['total_artigos']; ?></h3>
                                                <p><?php echo $row['descricao']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p class=\"text-center h3\"><strong>Não há categorias cadastradas.</strong></p>";
                }
                ?>
            </div>
            <div class="text-center justify-content-between align-center center card user-card col-md-12 mt-3">
                <a href="criar_categoria" class="btn btn-outline-success">Adicionar uma nova categoria</a>
            </div>
            <div class="row mt-5">
                <div class="card user-card col-md-12">
                    <div class="card-block">
                        <h5>Lista de Categorias de Conteúdo</h5>
                    </div>
                    <div class="card-block product-table p-t-35">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-uppercase">
                                        <th>Nome</th>
                                        <th>Artigos</th>
                                        <th></th>
                                        <?php if ($tipo_usuario == 'Administrador') { ?>
                                            <th></th>
                                        <?php } ?>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $conn = new ConexaoMysql();
                                    $conn->conectar();
                                    $sql = "SELECT c.descricao, c.slug, COUNT(a.id) AS total_artigos 
                                            FROM categoria c 
                                            LEFT JOIN artigo a ON c.id_categoria = a.id_categoria 
                                            GROUP BY c.descricao, c.slug";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                    ?>
                                            <tr>
                                                <td><?php echo $row['descricao']; ?></td>
                                                <td><?php echo $row['total_artigos']; ?></td>
                                                <td>
                                                   <form action="editar_categoria" method="POST">
    <input type="hidden" name="id_categoria" value="<?php echo $row['id_categoria']; ?>">
    <input class="btn btn-outline-primary" type="submit" value="Editar Categoria">
</form>

                                                </td>
                                                <?php if ($tipo_usuario == 'Administrador') { ?>
                                                    <td>
                                                        <form action="../../forms/remover_categoria.php" id="categoriaFormRemover" method="POST" onsubmit="return handleRemoverCategoria()">
                                                            <input type="hidden" name="id_categoria" value="<?php echo $row['id_categoria']; ?>">
                                                            <input class="btn btn-outline-danger" type="submit" value="Remover Categoria">
                                                        </form>

                                                    </td>
                                                <?php } ?>
                                                <td>
                                                    <form action="/blog" method="GET">
                                                        <input type="hidden" name="categoria" value="<?php echo $row['slug']; ?>">
                                                        <input class="btn btn-outline-success" type="submit" value="Ver Artigos da Categoria">
                                                    </form>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'><strong>Não há categorias disponíveis.</strong></td></tr>";
                                    }
                                    $conn->fecharConexao();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function handleRemoverCategoria() {
        var form = document.getElementById("categoriaFormRemover");
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../forms/remover_categoria", true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    try {
                        console.log(xhr.responseText);
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
</script>

<?php include 'template/footer.php' ?>
