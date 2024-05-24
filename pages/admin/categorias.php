<?php
session_start();
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: ../login.php");
    exit();
}
$id_usuario = $_SESSION['id_usuario'];

// Incluindo arquivo de conexão
require_once '../../config/ConexaoMySQL.php';
$conn = new ConexaoMysql();
$conn->conectar();

// Consulta para contar o número de artigos por categoria
$sqlCategoriasArtigos = "
    SELECT c.descricao, COUNT(a.id) AS total_artigos
    FROM categoria c
    LEFT JOIN artigo a ON c.id_categoria = a.id_categoria
    GROUP BY c.descricao";
$resultCategoriasArtigos = $conn->query($sqlCategoriasArtigos);

// Fechar conexão com o banco de dados
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
                <!-- Counter Cards Dinâmicos para Categorias e Artigos -->
                <?php
                if ($resultCategoriasArtigos->num_rows > 0) {
                    while ($row = $resultCategoriasArtigos->fetch_assoc()) {
                ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="card counter-card-2 user-card mb-4">
                                <div class="card-block-big">
                                    <div class="row">
                                        <div class="col-6 counter-card-icon">
                                            <i class="icofont icofont-chart-bar-graph"></i>
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
                    echo "<p class=\"text-center h1\"><strong>Não há categorias disponíveis.</strong></p>";
                }
                ?>
                <!-- Counter Cards Dinâmicos End -->
            </div>
            <div class="row">
                <a href="criar_categoria.php" class="btn btn-outline-success">Adicionar uma nova categoria</a>
            </div>
            <div class="row">
                <div class="card user-card">
                    <div class="card-block">
                        <h5>Lista de Categorias de Conteudo</h5>
                    </div>
                    <div class="card-block product-table p-t-35">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-uppercase">
                                        <th>Nome</th>
                                        <th>Slug</th>
                                        <th>Artigos</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once '../../config/ConexaoMySQL.php';
                                    $conn = new ConexaoMysql();
                                    $conn->conectar();
                                    $sql = "SELECT * FROM categoria";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                    ?>
                                            <tr>
                                                <td><?php echo $row['descricao']; ?></td>
                                                <td><?php echo $row['slug']; ?></td>
                                                <td><?php echo $row['id_categoria']; ?></td>
                                                <td>
                                                    <form action="editar.php" method="POST">
                                                        <input type="hidden" name="artigo" value="<?php echo $row['id_categoria']; ?>">
                                                        <input type="hidden" name="accao" value="editar">
                                                        <input class="btn btn-outline-primary" type="submit" value="Editar Categoria">
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action="../../forms/remover_categoria.php" method="POST">
                                                        <input type="hidden" name="id" value="<?php echo $row['id_categoria']; ?>">
                                                        <input type="hidden" name="accao" value="remover">
                                                        <input class="btn btn-outline-danger" type="submit" value="Remover Categoria">
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action="/conversu/artigo.php" method="POST">
                                                        <input type="hidden" name="artigo" value="<?php echo $row['id_categoria']; ?>">
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
<?php include 'template/footer.php' ?>