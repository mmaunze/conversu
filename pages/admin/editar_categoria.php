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
<?php
include 'template/header.php';
include 'template/siderbar.php';
if (isset($_POST['categoria'])) {
  require_once '../../config/ConexaoMySQL.php';
  $conn = new ConexaoMysql();
  $conn->conectar();
  $id_categoria = $_REQUEST['categoria'];
  $sql = "SELECT * from categoria 
  WHERE id_categoria = $id_categoria";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
?>
    <div class="page-body">
      <div class="container">
        <form class="content" id="categoriaFormEditar" onsubmit="return handleSubmiteEditarCategoria()" action="../../forms/editar_categoria.php" method="POST">
          <div class="form-group">
            <label class="h5" for="descricao">Nome do Artigo</label>
            <input type="text" class="form-control" name="descricao" id="descricao" value="<?php echo $row['descricao']; ?>" required>
            <input type="hidden" name="id_categoria" value="<?php echo $row['id_categoria'] ?>">
          </div>

          <div class="form-group">
            <label class="h5" for="resumo">Assunto</label>
            <textarea class="form-control" name="assunto" id="assunto" required><?php echo $row['assunto']; ?></textarea>
          </div>
          <button type="submit" class="btn btn-success left-0">Actualizar Categoria</button>
        </form>
      </div>
    </div>
<?php
  } else {
    echo "<p>Nenhuma categoria encontrada</p>";
  }
} else {
  if (isset($_SESSION['mensagem'])) {
    echo '<p class="text-center h1 text-success"><br><br><br><strong>' . $_SESSION['mensagem'] . '</strong></p><br><br><br><br><br><br><br><br>';
    unset($_SESSION['mensagem']);
  } elseif (isset($_SESSION['erro'])) {
    echo '<p class="text-center h1 text-danger"><br><br><br><strong>' . $_SESSION['erro'] . '</strong></p><br><br><br><br><br><br><br><br>';
    unset($_SESSION['erro']);
  } else {
    echo '<p class="text-center h1"><br><br><br><strong>Nenhum artigo foi seleccionado.</strong></p><br><br><br><br><br><br><br><br>';
  }
} ?>
<script>
    function handleSubmiteEditarCategoria() {
        var form = document.getElementById("categoriaFormEditar");
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../forms/editar_categoria.php", true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === "success") {
                        alert(response.message);
                        form.reset();

                    } else {
                        alert(response.message);
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
<?php include 'template/footer.php'; ?>