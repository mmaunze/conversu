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
    <form class="content" id="formCategoria" onsubmit="return handleSubmitCategoria()" action="../../forms/criar_categoria" method="POST">
      <div class="form-group">
        <label class="h5" for="titulo">Nome da Categia</label>
        <input type="text" class="form-control" name="descricao" id="descricao" required>
        <input type="hidden" name="autor" value="<?php echo $id_usuario?>" id="autor">
      </div>
     
      <div class="form-group">
        <label class="h5" for="resumo">Assuntos Da Categoria</label>
        <textarea class="form-control" name="assunto" id="assunto" required></textarea>
      </div>
      <input type="hidden" name="conteudo" id="conteudo">
      <button type="submit" class="btn btn-success left-0">Criar Categoia</button>
    </form>
  </div>
</div>
<script>
      function handleSubmitCategoria() {
        var form = document.getElementById("formCategoria");
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../forms/criar_categoria", true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === "success") {
                        alert(response.message); // Exibir mensagem de sucesso
                        form.reset(); // Recarregar a página após o sucesso
                    } else {
                        alert(response.message); // Exibir mensagem de erro
                    }
                } else {
                    alert('Erro ao processar a solicitação. Tente novamente mais tarde.');
                }
            }
        };
        xhr.send(formData);

        return false; // Evitar o envio normal do formulário
    }
</script>
<?php include 'template/footer.php' ?>