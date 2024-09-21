<?php session_start(); ?>
<?php
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

$query_tu = "SELECT tu.descricao 
FROM tipo_utilizador tu, utilizador u 
WHERE u.id_tipo_utilizador = tu.id_tipo_utilizador AND u.id = ?";

$stmt_tu = $conn->prepare($query_tu);
$stmt_tu->bind_param("i", $id_usuario);
$stmt_tu->execute();
$stmt_tu->bind_result($tipo_usuario);
$stmt_tu->fetch();
$stmt_tu->close();

if (!(($tipo_usuario == "Administrador") || ($tipo_usuario == "Moderador"))) {
    $_SESSION = array();
    session_destroy();
    header("Location: ../login");
    exit();
}

$conn->fecharConexao();
?>
<?php include 'template/header.php'; ?>
<?php include 'template/siderbar.php' ?>
<div class="page-body">
    <div class="container">
        <?php if (isset($_SESSION['mensagem'])) : ?>
            <div class="alert alert-success"><?php echo $_SESSION['mensagem']; ?></div>
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['erro'])) : ?>
            <div class="alert alert-danger"><?php echo $_SESSION['erro']; ?></div>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>
        <form class="content" id="formCategoria" onsubmit="return handleSubmitCategoria()" action="../../forms/criar_categoria" method="POST">
            <div class="mb-3">
                <label for="descricao" class="form-label h5">Nome da Categoria</label>
                <input type="text" class="form-control" name="descricao" id="descricao" required>
                <input type="hidden" name="autor" value="<?php echo $id_usuario ?>" id="autor">
            </div>
            <div class="mb-3">
                <label for="assunto" class="form-label h5">Assuntos da Categoria</label>
                <textarea class="form-control" name="assunto" id="assunto" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Criar Categoria</button>
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
