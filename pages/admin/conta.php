<?php
session_start();
require_once '../../config/ConexaoMySQL.php';

// Verificar se o atributo de sessão está presente
if (!isset($_POST['utilizador'])) {
    session_destroy();
    header("Location: index.php"); // Redirecionar para a página inicial ou de login
    exit();
}

// Conectar ao banco de dados
$conn = new ConexaoMySQL();
$conexao = $conn->conectar();

// Consultar os dados do utilizador
$utilizador_id = $_POST['utilizador'];
$query = $conn->prepare("SELECT u.*, t.descricao AS tipo_permissao
                         FROM utilizador u 
                         JOIN tipo_utilizador t ON u.id_tipo_utilizador = t.id_tipo_utilizador
                         WHERE u.id = ?");
$query->bind_param('i', $utilizador_id);
$query->execute();
$result = $query->get_result();
$utilizador = $result->fetch_assoc();

include 'template/header.php';
include 'template/siderbar.php';
?>

<div class="main-body user-profile">
    <div class="page-wrapper">
        <div class="page-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="cover-profile">
                        <div class="profile-bg-img">
                            <div class="card-block user-info">
                                <div class="col-md-12">
                                    <div class="media-left">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="tab-header">
                        <ul class="nav nav-tabs md-tabs tab-timeline text-success" role="tablist" id="mytab">
                            <li class="nav-item">
                                <a class="nav-link active text-success" data-toggle="tab" href="#personal" role="tab">Informações Pessoais</a>
                                <div class="slide"></div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-success" data-toggle="tab" href="#binfo" role="tab">Dados de Acesso</a>
                                <div class="slide text-success"></div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="personal" role="tabpanel">
                            <div class="card user-card">
                                <div class="card-header">
                                    <h5 class="card-header-text">Minhas Informações</h5>
                                </div>
                                <div class="card-block">
                                    <div class="view-info">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="general-info">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-xl-6">
                                                            <div class="table-responsive">
                                                                <table class="table m-0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th scope="row">Nome Completo</th>
                                                                            <td><?php echo htmlspecialchars($utilizador['nome']); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row">Sexo</th>
                                                                            <td><?php echo htmlspecialchars($utilizador['sexo']); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row">Tipo de Permissão</th>
                                                                            <td><?php echo htmlspecialchars($utilizador['tipo_permissao']); ?></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 col-xl-6">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th scope="row">Email</th>
                                                                            <td><a href="mailto:<?php echo htmlspecialchars($utilizador['email']); ?>"><?php echo htmlspecialchars($utilizador['email']); ?></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row">Contacto</th>
                                                                            <td><?php echo htmlspecialchars($utilizador['contacto']); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row">Username</th>
                                                                            <td><?php echo htmlspecialchars($utilizador['username']); ?></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="binfo" role="tabpanel">
                            <div class="card user-card">
                                <div class="card-header">
                                    <h5 class="card-header-text">Alterar Dados</h5>
                                </div>
                                <div class="card-block">
                                    <form id="formTrocarSenha" method="POST"  action="../../forms/trocar_senha.php" onsubmit="return handleTrocarSenha()">
                                        <div class="form-group">
                                            <label for="senhaAntiga">Senha Antiga:</label>
                                            <input type="password" class="form-control" id="senhaAntiga" name="senhaAntiga" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="novaSenha">Nova Senha:</label>
                                            <input type="password" class="form-control" id="novaSenha" name="novaSenha" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="confirmarSenha">Confirmar Nova Senha:</label>
                                            <input type="password" class="form-control" id="confirmarSenha" name="confirmarSenha" required>
                                            <input type="hidden" name="user" value="<?php echo htmlspecialchars($utilizador['id']); ?>" />
                                        </div>
                                        <button type="submit" class="btn btn-outline-success">Alterar Senha</button>
                                    </form>
                                    <div id="mensagem" class="mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function handleTrocarSenha() {
    var form = document.getElementById("formTrocarSenha");
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../../forms/trocar_senha.php", true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            var mensagem = document.getElementById("mensagem");
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                mensagem.textContent = response.message;
                if (response.status === "success") {
                    mensagem.className = "alert alert-success";
                    form.reset();
                } else {
                    mensagem.className = "alert alert-danger";
                }
            } else {
                mensagem.textContent = 'Erro no servidor.';
                mensagem.className = "alert alert-danger";
            }
        }
    };
    xhr.send(formData);
    return false;
}
</script>

<?php
include 'template/footer.php';
?>
