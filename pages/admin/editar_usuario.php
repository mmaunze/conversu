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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id_editar = $_POST['id'];

    $sql = "SELECT * FROM utilizador WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_editar);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if (!$usuario) {
        $_SESSION['erro'] = "Usuário não encontrado.";
        header("Location: usuarios");
        exit();
    }
} else {
    $_SESSION['erro'] = "ID de usuário inválido.";
    header("Location: usuarios");
    exit();
}

$conn->fecharConexao();
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
    <form class="content align-content-center" id="formUsuario" onsubmit="return handleEditarUsuario()" action="../../forms/editar_usuario.php" method="POST">
      <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
      <div class="form-group col-md-6">
        <label class="h5" for="nome">Nome Completo</label>
        <input type="text" class="form-control" name="nome" id="nome" value="<?php echo $usuario['nome']; ?>" required>
      </div>
      <div class="form-group col-md-6">
        <label class="h5" for="email">Email</label>
        <input type="text" class="form-control" name="email" id="email" value="<?php echo $usuario['email']; ?>" required>
      </div>
      <div class="form-group col-md-6">
        <label class="h5" for="contacto">Contacto</label>
        <input type="number" class="form-control" name="contacto" id="contacto" value="<?php echo $usuario['contacto']; ?>" required>
      </div>
      <div class="form-group col-md-6">
        <label class="h5" for="sexo">Genero</label>
        <select name="sexo" id="sexo" class="form-control">
          <option value="Masculino" <?php if ($usuario['sexo'] === 'Masculino') echo 'selected'; ?>>Masculino</option>
          <option value="Feminino" <?php if ($usuario['sexo'] === 'Feminino') echo 'selected'; ?>>Feminino</option>
        </select>
      </div>
      <div class="form-group col-md-6">
        <label class="h5" for="id_tipo_utilizador">Tipo de utilizador</label>
        <select name="id_tipo_utilizador" id="id_tipo_utilizador" class="form-control">
          <?php
          $conn->conectar();
          $sql = "SELECT * FROM tipo_utilizador WHERE descricao <> 'Administrador'";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
          ?>
              <option value="<?php echo $row['id_tipo_utilizador']; ?>" <?php if ($usuario['id_tipo_utilizador'] === $row['id_tipo_utilizador']) echo 'selected'; ?>><?php echo $row['descricao']; ?></option>
          <?php
            }
          } else {
            echo "<strong>Não há tipos de utilizadores disponíveis.</strong>";
          }
          $conn->fecharConexao();
          ?>
        </select>
      </div>
      <button type="submit" class="btn btn-success left-0">Salvar alterações</button>
    </form>
  </div>
</div>
<script>
  function handleEditarUsuario() {
    var form = document.getElementById("formUsuario");
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../../forms/editar_usuario.php", true);
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
