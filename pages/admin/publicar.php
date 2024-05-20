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
    <form class="content" onsubmit="return handleSubmit()" action="../../forms/publicar_artigo.php" method="POST">
      <div class="form-group">
        <label class="h5" for="titulo">Titulo</label>
        <input type="text" class="form-control" name="titulo" id="titulo" required>
        <input type="hidden" name="autor" value="<?php echo $id_usuario?>" id="autor">
      </div>
      <div class="form-group">
        <label class="h5" for="titulo">Categoria</label>
        <select name="categoria" id="categoria" class="form-control">
          <?php
          require_once '../../config/ConexaoMySQL.php';
          $conn = new ConexaoMysql();
          $conn->conectar();
          $sql = "SELECT * FROM categoria";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
          ?>
              <option value="<?php echo $row['id_categoria']; ?>"><?php echo $row['descricao']; ?></option>
          <?php }
          } else {
            echo "<strong>Não há categorias disponíveis.</strong>";
          }
          // Fechar conexão com o banco de dados
          $conn->fecharConexao();
          ?>
        </select>
      </div>
      <div class="form-group">
        <label class="h5" for="resumo">Resumo</label>
        <textarea class="form-control" name="resumo" id="resumo" required></textarea>
      </div>
      <div class="form-group">
        <label class="h5" for="editor">Conteúdo</label>
        <div id="editor" class="pell"></div>
      </div>
      <input type="hidden" name="conteudo" id="conteudo">
      <button type="submit" class="btn btn-success left-0">Publicar Artigo</button>
    </form>
    <div style="display: none;">
      <div style="margin-top:20px;">
        <h3>Text output:</h3>
        <div id="text-output"></div>
      </div>
      <div style="margin-top:20px;">
        <h3>HTML output:</h3>
        <div id="html-output"></div>
      </div>
    </div>
  </div>
</div>
<?php include 'template/footer.php' ?>