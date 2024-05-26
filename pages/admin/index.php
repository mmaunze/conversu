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

// Consulta para contar o número de artigos
$sqlArtigos = "SELECT COUNT(*) AS total_artigos FROM artigo";
$resultArtigos = $conn->query($sqlArtigos);
$totalArtigos = $resultArtigos->fetch_assoc()['total_artigos'];

// Consulta para contar o número de categorias de conteúdo
$sqlCategorias = "SELECT COUNT(*) AS total_categorias FROM categoria";
$resultCategorias = $conn->query($sqlCategorias);
$totalCategorias = $resultCategorias->fetch_assoc()['total_categorias'];

// Consulta para contar o número de usuários cadastrados
$sqlUsuarios = "SELECT COUNT(*) AS total_usuarios FROM utilizador";
$resultUsuarios = $conn->query($sqlUsuarios);
$totalUsuarios = $resultUsuarios->fetch_assoc()['total_usuarios'];

// Fechar conexão com o banco de dados
$conn->fecharConexao();
?>

<?php include 'template/header.php' ?>
<?php include 'template/siderbar.php' ?>
<div class="main-body">
    <div class="page-wrapper">
        <div class="page-header">
            <div class="page-header-title">
                <h4>Dashboard</h4>
            </div>
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="#!">
                            <i class="icofont icofont-home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><span>Dashboard</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="page-body">
            <div class="row">
                <!-- counter-card-1 start-->
                <div class="col-md-12 col-xl-4 mb-5">
                    <div class="card counter-card-1 user-card">
                        <div class="card-block-big">
                            <div class="row">
                                <div class="col-6 counter-card-icon ">
                                    <i class="icofont icofont-chart-histogram"></i>
                                </div>
                                <div class="col-6  text-right">
                                    <div class="counter-card-text">
                                        <h3><?php echo $totalArtigos; ?></h3>
                                        <p>Artigos Publicados</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- counter-card-1 end-->
                <!-- counter-card-2 start -->
                <div class="col-md-6 col-xl-4">
                    <div class="card counter-card-2 user-card">
                        <div class="card-block-big">
                            <div class="row">
                                <div class="col-6 counter-card-icon">
                                    <i class="icofont icofont-chart-line-alt"></i>
                                </div>
                                <div class="col-6 text-right">
                                    <div class="counter-card-text">
                                        <h3><?php echo $totalUsuarios; ?></h3>
                                        <p>Usuários Cadastrados</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- counter-card-2 end -->
                <!-- counter-card-3 start -->
                <div class="col-md-6 col-xl-4">
                    <div class="card counter-card-3 user-card">
                        <div class="card-block-big">
                            <div class="row">
                                <div class="col-6 counter-card-icon">
                                    <i class="icofont icofont-chart-line"></i>
                                </div>
                                <div class="col-6 text-right">
                                    <div class="counter-card-text">
                                        <h3><?php echo $totalCategorias; ?></h3>
                                        <p>Categorias de Conteúdo</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- counter-card-3 end -->

                <!-- counter-card-3 end -->
                <div class="col-md-12">
                    <div class="card user-card">
                        <div class="card-block">
                            <h5>Lista de Artigos</h5>
                        </div>
                        <div class="card-block product-table p-t-35">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr class="text-uppercase">
                                            <th>Título</th>
                                            <th>Autor</th>
                                            <th>Resumo</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        require_once '../../config/ConexaoMySQL.php';
                                        $conn = new ConexaoMysql();
                                        $conn->conectar();
                                        // Consulta para buscar os artigos do banco de dados
                                        $sql = "SELECT * FROM artigo";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $row['titulo']; ?></td>
                                                    <td><?php echo $row['autor']; ?></td>
                                                    <td><?php echo $row['resumo']; ?></td>
                                                    <td>
                                                        <form action="editar" method="POST">
                                                            <input type="hidden" name="artigo" value="<?php echo $row['id']; ?>">
                                                            <input class="btn btn-outline-primary" type="submit" value="Editar Artigo">
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form action="../../forms/remover_artigo" id="artigoFormRemover" onsubmit="return handleRemoverArtigo()" method="POST">
                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                            <input class="btn btn-outline-danger" type="submit" value="Remover Artigo">
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form action="/conversu/artigo" method="POST">
                                                            <input type="hidden" name="artigo" value="<?php echo $row['id']; ?>">
                                                            <input class="btn btn-outline-success" type="submit" value="Ver Artigo">
                                                        </form>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'><strong>Não há artigos disponíveis.</strong></td></tr>";
                                        }
                                        // Fechar conexão com o banco de dados
                                        $conn->fecharConexao();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function handleRemoverArtigo() {
        var form = document.getElementById("artigoFormRemover");
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../forms/remover_artigo", true);
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