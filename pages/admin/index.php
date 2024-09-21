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
                    <li class="breadcrumb-item"><span>Admin Page</span>
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
              
            </div>
 
            
            <form class="text-center justify-content-between align-center center card user-card col-md-12 mt-3" action="conta" method="POST" class="d-inline">
                <input type="hidden" name="utilizador" value="<?php echo $id_usuario; ?>">
                <button class="btn btn-outline-success" type="submit">Minha Conta </button>
            </form>
            
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