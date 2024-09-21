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

// Verificar se foi enviado o ID da categoria
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_categoria'])) {
    $id_categoria = $_POST['id_categoria'];

    // Buscar categoria no banco de dados
    $sql = "SELECT * FROM categoria WHERE id_categoria = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $categoria = $result->fetch_assoc();
    } else {
        $_SESSION['erro'] = "Categoria não encontrada.";
        header("Location: categorias"); // Redirecionar para página de listagem de categorias
        exit();
    }
} else {
    $_SESSION['erro'] = "ID de categoria inválido.";
    header("Location: categorias"); // Redirecionar para página de listagem de categorias
    exit();
}

$conn->fecharConexao();
?>

<?php include 'template/header.php'; ?>
<?php include 'template/sidebar.php'; ?>
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
        <form class="content" id="formEditarCategoria" onsubmit="return handleSubmitEditarCategoria()" action="../../forms/editar_categoria" method="POST">
            <input type="hidden" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">
            <div class="mb-3">
                <label for="descricao" class="form-label h5">Nome da Categoria</label>
                <input type="text" class="form-control" name="descricao" id="descricao" value="<?php echo $categoria['descricao']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="assunto" class="form-label h5">Assuntos da Categoria</label>
                <textarea class="form-control" name="assunto" id="assunto" rows="5" required><?php echo $categoria['assunto']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Atualizar Categoria</button>
        </form>
    </div>
</div>
<script>
    function handleSubmitEditarCategoria() {
        var form = document.getElementById("formEditarCategoria");
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
                        // Redirecionar para página de lista de categorias após sucesso
                        window.location.href = "categorias";
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
