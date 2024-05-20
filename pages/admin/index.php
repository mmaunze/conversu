<?php
session_start();
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
  
    $_SESSION = array();
    session_destroy();
    header("Location: ../login.php");
    exit(); 
}
$id_usuario = $_SESSION['id_usuario'];
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
                                        <h3>23</h3>
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
                                        <h3>15</h3>
                                        <p>Usuarios Cadastrados</p>
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
                                        <h3>35</h3>
                                        <p>Categoria de Conteudo</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                            <th>Titulo</th>
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
                                                    <td>
                                                        <?php echo $row['titulo']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['autor']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['resumo']; ?>
                                                    </td>
                                                    <td>
                                                        <form action="editar.php" method="POST">
                                                            <input type="hidden" name="artigo" value="<?php echo $row['id']; ?>">
                                                            <input class="btn btn-outline-primary" type="submit" value="Editar Artigo">
                                                        </form>
                                                    </td>
                                                    <td>
                                                    <form action="../../forms/remover_artigo.php" method="POST">
                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                            <input class="btn btn-outline-danger" type="submit" value="Remover Artigo">
                                                        </form>  
                                                    </td>
                                                    <td>
                                                        <form action="/conversu/artigo.php" method="POST">
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
<?php include 'template/footer.php' ?>