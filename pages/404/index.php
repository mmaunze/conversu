<!DOCTYPE html>
<html lang="pt" class="no-js">
<head>
    <meta charset="utf-8">
    <title>Não foi possivel encontrar a página soliciada</title>
    <meta name="description" content="Conversu ERROR">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Conversu">
    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/img/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo dirname($_SERVER['PHP_SELF']); ?>/css/style.css">
    <style>
        .logo {
            max-height: 50px; 
            width: auto; 
        }
        .logo-link {
            display: flex;
            align-items: center;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #00AF94;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px;
        }
        .navbar a:hover {
            background-color: #555; 
        }
    </style>
</head>
<body class="flat">
    <div class="navbar">
       
        <div>
             <a href="/" class="logo-link" title="voltar ao inicio">
            <img src="/assets/img/logo.png" class="logo" alt="conversu">
        </a>
            <a href="/">Home</a>
            <a href="https://facebook.com/conversu"><i class="fa fa-facebook"></i></a>
        </div>
    </div>
    <!-- Canvas for particles animation -->
    <div id="particles-js"></div>
    <div class="content">
        <div class="content-box">
            <div class="big-content">
                <!-- Main squares for the content logo in the background -->
                <div class="list-square">
                    <span class="square"></span>
                    <span class="square"></span>
                    <span class="square"></span>
                </div>
                <!-- Main lines for the content logo in the background -->
                <div class="list-line">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </div>
                <!-- The animated searching tool -->
                <i class="fa fa-search" aria-hidden="true"></i>
                <!-- div clearing the float -->
                <div class="clear"></div>
            </div>
            <!-- Your text -->
            <h1>Oops! Erro 404 nada encontrado.</h1>
            <p>Não foi possivel encontrar a página  solicitada.<br>
                Pode ter sido movida para outro endereço.</p>
        </div>
    </div>
    <footer class="light">
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="https://facebook.com/conversu"><i class="fa fa-facebook"></i></a></li>
        </ul>
    </footer>
    <script src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/js/jquery.min.js"></script>
    <script src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/js/bootstrap.min.js"></script>
    <!-- Particles plugin -->
    <script src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/js/particles.js"></script>
</body>
</html>
