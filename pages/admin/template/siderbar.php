<?php
require_once '../../config/ConexaoMySQL.php';

// Verificar se o usuário está logado e buscar o tipo de usuário na base de dados
if(isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    $conn = new ConexaoMysql();
    $conexao = $conn->conectar();

    // Consultar o tipo de usuário na base de dados
    $query = "SELECT tu.descricao 
    FROM tipo_utilizador tu, utilizador u 
    WHERE u.id_tipo_utilizador = tu.id_tipo_utilizador AND u.id = ?";

    $stmt = $conexao->prepare($query);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->bind_result($tipo_usuario);
    $stmt->fetch();
    $stmt->close();

    // Verificar o tipo de usuário e exibir os itens de menu correspondentes
    switch($tipo_usuario) {
        case 'Editor':
            $menu_items = array(
                array('url' => './', 'icon' => 'fas fa-home', 'text' => 'Home'),
                 array('url' => './publicacoes', 'icon' => 'fas fa-home', 'text' => 'Gerir Artigos'),
                array('url' => './publicar_artigo', 'icon' => 'fas fa-home', 'text' => 'Publicar Artigo'),
                array('url' => 'sair?sair', 'icon' => 'fas fa-home', 'text' => 'Terminar Sessão')

            );
            break;
        case 'Subscritor':
            $menu_items = array(
                array('url' => './', 'icon' => 'fas fa-home', 'text' => 'Home'),
                array('url' => 'sair?sair', 'icon' => 'fas fa-home', 'text' => 'Terminar Sessão')

            );
            break;
        case 'Moderador':
            $menu_items = array(
                array('url' => './', 'icon' => 'fas fa-home', 'text' => 'Home'),
                array('url' => './publicacoes', 'icon' => 'fas fa-home', 'text' => 'Gerir Artigos'),
                array('url' => './categorias', 'icon' => 'fas fa-home', 'text' => 'Gerir Categorias'),
                array('url' => './usuarios', 'icon' => 'fas fa-home', 'text' => 'Gerir Utilizadores'),
                  array('url' => './fotos', 'icon' => 'fas fa-home', 'text' => 'Gerir Fotos'),
                array('url' => './portfolio', 'icon' => 'fas fa-home', 'text' => 'Gerir Portfolio'),
                array('url' => 'sair?sair', 'icon' => 'fas fa-home', 'text' => 'Terminar Sessão')

            );
            break;
        case 'Administrador':
            $menu_items = array(
                array('url' => './', 'icon' => 'fas fa-home', 'text' => 'Home'),
                array('url' => './publicacoes', 'icon' => 'fas fa-home', 'text' => 'Gerir Artigos'),
                array('url' => './categorias', 'icon' => 'fas fa-home', 'text' => 'Gerir Categorias'),
                array('url' => './usuarios', 'icon' => 'fas fa-home', 'text' => 'Gerir Utilizadores'),
                array('url' => './portfolio', 'icon' => 'fas fa-home', 'text' => 'Gerir Portfolio'),
                 array('url' => './fotos', 'icon' => 'fas fa-home', 'text' => 'Gerir Fotos'),
                array('url' => 'sair?sair', 'icon' => 'fas fa-home', 'text' => 'Terminar Sessão')
            );
            break;
        default:
            header('Location: 404');
            exit();
    }
} else {
    header('Location: ../login');
    exit();
}
?>

<!-- Parte HTML com os itens de menu -->
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <nav class="pcoded-navbar">
            <div class="pcoded-inner-navbar main-menu">
                <div class="pcoded-navigatio-lavel">Navigation</div>
                <ul class="pcoded-item pcoded-left-item">
                    <?php foreach($menu_items as $item): ?>
                        <li class="pcoded-trigger">
                            <a href="<?php echo $item['url']; ?>">
                                <span class="pcoded-micon"><i class="<?php echo $item['icon']; ?>"></i></span>
                                <span class="pcoded-mtext"><?php echo $item['text']; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </nav>
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">
                