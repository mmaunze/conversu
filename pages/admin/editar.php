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
if (isset($_POST['artigo'])) {
  require_once '../../config/ConexaoMySQL.php';
  $conn = new ConexaoMysql();
  $conn->conectar();
  $id_artigo = $_REQUEST['artigo'];
  $sql = "SELECT artigo.*, categoria.descricao AS categoria_descricao 
          FROM artigo 
          INNER JOIN categoria ON artigo.id_categoria = categoria.id_categoria 
          WHERE artigo.id = $id_artigo";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $categoria = $row['categoria_descricao'];
?>
    <div class="page-body">
      <div class="container">
        <form class="content" onsubmit="return handleSubmit()" action="../../forms/editar_artigo.php" method="POST">
          <div class="form-group">
            <label class="h5" for="titulo">Titulo</label>
            <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $row['titulo']; ?>" required>
            <input type="hidden" name="autor" value="<?php echo $id_usuario ?>" id="autor">
            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
          </div>
          <div class="form-group">
            <label class="h5" for="categoria">Categoria</label>
            <select name="categoria" id="categoria" class="form-control">
              <?php
              $sql = "SELECT * FROM categoria";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                while ($categoria_row = $result->fetch_assoc()) {
                  $selected = $categoria_row['id_categoria'] == $row['id_categoria'] ? 'selected' : '';
                  echo "<option value=\"{$categoria_row['id_categoria']}\" $selected>{$categoria_row['descricao']}</option>";
                }
              } else {
                echo "<strong>Não há categorias disponíveis.</strong>";
              }
              $conn->fecharConexao();
              ?>
            </select>
          </div>
          <div class="form-group">
            <label class="h5" for="resumo">Resumo</label>
            <textarea class="form-control" name="resumo" id="resumo" required><?php echo $row['resumo']; ?></textarea>
          </div>
          <div class="form-group">
            <label class="h5" for="editor">Conteúdo</label>
            <div id="editor" class="pell"></div>
          </div>
          <input type="hidden" name="conteudo" id="conteudo">
          <button type="submit" class="btn btn-success left-0">Publicar Artigo</button>
        </form>
        <script src="https://cdn.jsdelivr.net/npm/pell"></script>
        <script>
          const editor = pell.init({
            element: document.getElementById('editor'),
            onChange: (html) => {
              document.getElementById('conteudo').value = html;
            },
            defaultParagraphSeparator: 'p',
            styleWithCSS: true,
            actions: ['bold', 'italic', 'underline', 'strikethrough', 'heading1', 'heading2', 'olist', 'ulist', 'paragraph', 'quote', 'code', 'line', 'link', 'image']
         
          });
          editor.content.innerHTML = `<?php echo addslashes($row['conteudo']); ?>`;
          function handleSubmit() {
            document.getElementById('conteudo').value = editor.content.innerHTML;
            return true;
          }
        </script>
      </div>
    </div>
<?php
  } else {
    echo "<p>Nenhum artigo encontrado</p>";
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
<?php include 'template/footer.php';
?>