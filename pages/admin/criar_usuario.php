<?php
session_start();
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {

  $_SESSION = array();
  session_destroy();
  header("Location: ../login");
  exit();
}
$id_usuario = $_SESSION['id_usuario'];

?>

<?php
include 'template/header.php'; ?>
<?php include 'template/siderbar.php' ?>
<div class="page-body">

  <div class="container">
    <?php if (isset($_SESSION['mensagem'])) : ?>
      <div class="mensagem-sucesso h3 text-success"><?php echo $_SESSION['mensagem']; ?></div>
      <?php unset($_SESSION['mensagem']);
      ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['erro'])) : ?>
      <div class="mensagem-erro"><?php echo $_SESSION['erro']; ?></div>
      <?php unset($_SESSION['erro']);
      ?>
    <?php endif; ?>
    <form class="content align-content-center" id="formUsuario" onsubmit="return handleSubmitUsuario()" action="../../forms/criar_usuario" method="POST">
      <div class="form-group col-md-6">
        <label class="h5" for="nome">Nome Completo</label>
        <input type="text" class="form-control" name="nome" id="nome" required>
      </div>
      <div class="form-group col-md-6">
        <label class="h5" for="email">Email</label>
        <input type="text" class="form-control" name="email" id="email" required>
      </div>
       <div class="form-group col-md-6">
        <label class="h5" for="contacto">Contacto</label>
        <input type="number" class="form-control" name="contacto" id="contacto" required>
      </div>
        <div class="form-group col-md-6">
        <label class="h5" for="sexo">Genero</label>
        <select name="sexo" id="sexo" class="form-control">
          <option value="Masculino">Masculino</option>
          <option value="Feminino">Feminino</option>
        </select>
      </div>
      <div class="form-group col-md-6">
        <label class="h5" for="id_tipo_utilizador">Tipo de utilizador</label>
        <select name="id_tipo_utilizador" id="id_tipo_utilizador" class="form-control">
          <?php
          require_once '../../config/ConexaoMySQL.php';
          $conn = new ConexaoMysql();
          $conn->conectar();
          $sql = "SELECT * FROM tipo_utilizador where descricao <> 'Administrador'";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
          ?>
              <option value="<?php echo $row['id_tipo_utilizador']; ?>"><?php echo $row['descricao']; ?></option>
          <?php }
          } else {
            echo "<strong>Não há tipos de utilizadores disponíveis.</strong>";
          }

          $conn->fecharConexao();
          ?>
        </select>
      </div>
      <button type="submit" class="btn btn-success left-0">Cadastrar utilizador</button>
    </form>
  </div>
</div>
<script>
  function handleSubmitUsuario() {
    var form = document.getElementById("formUsuario");
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../../forms/criar_usuario", true);
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
<?php include 'template/footer.php' ?>