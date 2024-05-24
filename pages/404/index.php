<!DOCTYPE html>
<html lang="pt" class="no-js">
	<head>
		<meta charset="utf-8">
		<title>404 - Erro </title>
		<meta name="description" content="Flat able 404 Error page design">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="Conversu">
        <!-- Favicon -->
        <link rel="shortcut icon" href="assets/img/favicon.png">
		<link rel="stylesheet" type="text/css" href="<?php echo dirname($_SERVER['PHP_SELF']); ?>/css/style.css">
	</head>
	<body class="flat">
        <!-- Canvas for particles animation -->
        <div id="particles-js"></div>
        <!-- Your logo on the top left -->
        <a href="#" class="logo-link" title="back home">
            <img src="/assets/img/" class="logo" alt="conversu">
        </a>
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
