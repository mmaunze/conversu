<?php
session_start();
if (isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario'])) {
  // Sessão do usuário encontrada, redirecionar para o painel
  header("Location: admin");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container">
    <div class="row justify-content-center mt-5">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            Autenticação
          </div>
          <div class="card-body">
            <form id="loginForm">
              <div class="mb-3">
                <label for="email" class="form-label">Endereço de e-mail</label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">Nós nunca compartilharemos seu e-mail com ninguém.</div>
              </div>
              <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" name="senha" class="form-control" id="senha">
              </div>
              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="lembrarSenha">
                <label class="form-check-label" for="lembrarSenha">Lembrar Senha</label>
              </div>
              <button type="button" class="btn btn-primary" onclick="login()">Login</button>
            </form>
            <div id="resultado"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/admin/assets/plugins/jquery/dist/jquery.min.js" charset="utf-8"></script>
  <script>
    function login() {
      var email = $("#email").val();
      var senha = $("#senha").val();
      $.ajax({
        type: "POST",
        url: "../forms/login",
        data: {
          email: email,
          senha: senha
        },
        success: function(response) {
          var data = JSON.parse(response);
          if (data.status === 'success') {
            window.location.href = data.redirect;
          } else {
            $("#resultado").html(data.message).addClass("text-danger");
          }
        }
      });
    }
  </script>
</body>

</html>