<?php
// Configuração da conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = 'Pa$$w0rd';
$dbname = "conversu";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Função para buscar todos os artigos
function buscarTodosArtigos($conn) {
    $sql = "SELECT artigo.*, utilizador.nome AS autor_nome 
            FROM artigo 
            INNER JOIN utilizador ON artigo.autor = utilizador.id";
    $result = $conn->query($sql);
    $artigos = $result->fetch_all(MYSQLI_ASSOC);

    // Formatar a data para português
    foreach ($artigos as &$artigo) {
        $artigo['data_publicacao'] = date('d/M/Y', strtotime($artigo['data_publicacao']));
    }

    return $artigos;
}

// Função para buscar todas as categorias
function buscarCategorias($conn) {
    $sql = "SELECT c.*, COUNT(a.id) AS total_artigos 
            FROM categoria c 
            LEFT JOIN artigo a ON c.id_categoria = a.id_categoria
            GROUP BY c.id_categoria";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


// Buscar dados
$artigos = buscarTodosArtigos($conn);
$categorias = buscarCategorias($conn);

// Fechar conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="PT">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Blog - CONVERSU - Construções Verdes e Sustentáveis</title>
  <meta name="description" content="Conversu é líder em construções verdes e sustentáveis em Moçambique, oferecendo serviços de consultoria ambiental, projetos arquitetônicos, construções sustentáveis e treinamentos em sustentabilidade. Transformamos o setor da construção civil com soluções inovadoras e ecológicas.">
  <meta name="keywords" content="Conversu, construções verdes, sustentabilidade, consultoria ambiental, projetos arquitetônicos, treinamentos em sustentabilidade, construção sustentável, Moçambique, eficiência energética, certificações LEED, BREEAM, reformas verdes">
  <meta name="author" content="Conversu">
  <meta name="robots" content="index, follow">
  <meta property="og:title" content="Conversu - Construções Verdes e Sustentáveis em Moçambique">
  <meta property="og:description" content="Conversu é líder em construções verdes e sustentáveis em Moçambique, oferecendo serviços de consultoria ambiental, projetos arquitetônicos, construções sustentáveis e treinamentos em sustentabilidade. Transformamos o setor da construção civil com soluções inovadoras e ecológicas.">
  <meta property="og:image" content="https://conversu.co.mz/assets/img/logo.png">
  <meta property="og:url" content="https://conversu.co.mz/">
  <meta property="og:type" content="website">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Conversu - Construções Verdes e Sustentáveis em Moçambique">
  <meta name="twitter:description" content="Conversu é líder em construções verdes e sustentáveis em Moçambique, oferecendo serviços de consultoria ambiental, projetos arquitetônicos, construções sustentáveis e treinamentos em sustentabilidade. Transformamos o setor da construção civil com soluções inovadoras e ecológicas.">
  <meta name="twitter:image" content="https://conversu.co.mz/assets/img/logo.png">
  <link rel="canonical" href="https://conversu.co.mz/">

  <link href="/assets/img/favicon.png" rel="icon">
  <link href="/assets/img/apple-touch-icon.png" rel="apple-touch-icon">


  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">


  <link href="/assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">


  <link href="/assets/css/style.css" rel="stylesheet">
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-7RTNKFGCKS"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-7RTNKFGCKS');
  </script>


</head>
<body>
    <header id="header" class="fixed-top d-flex align-items-center">
        <div class="container d-flex align-items-center">
            <a href="/" class="logo me-auto"><img src="/assets/img/logo.png" alt="" class="img-fluid"></a>
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a href="/">Página Inicial </a></li>
                    <li class="dropdown"><a href="#"><span>Sobre</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="/sobre/">Quem Somos</a></li>
                            <li><a href="/sobre/cta">CTA</a></li>
                            <li><a href="/sobre/contacto">Contacto</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="#"><span>Serviços</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="/servicos/">Serviços</a></li>
                            <li><a href="/servicos/precos">Preços</a></li>
                        </ul>
                    </li>
                    <li><a href="/portfolio/">Portfolio</a></li>
                    <li><a href="/blog/" class="active">Blog</a></li>
                       <li><a href="/servicos/precos" class="getstarted">Solicitar Serviço</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
        </div>
    </header>

    <main id="main">
        <section id="breadcrumbs" class="breadcrumbs">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Blog</h2>
                    <ol>
                        <li><a href="/">Página Inicial</a></li>
                        <li>Blog</li>
                    </ol>
                </div>
            </div>
        </section>

        <section id="blog" class="blog">
            <div class="container" data-aos="fade-up">
                <div class="row">
                    <div class="col-lg-8 entries">
                        <?php foreach ($artigos as $artigo) : ?>
                            <article class="entry">
                                <div class="entry-img">
                                    <img src="<?php echo "/assets/img/".$artigo['imagem']; ?>" alt="<?php echo $artigo['titulo']; ?>" class="img-fluid">
                                </div>
                                <h2 class="entry-title">
                                    <a href="/blog/artigo.php?titulo=<?php echo $artigo['permanent_link']; ?>"><?php echo $artigo['titulo']; ?></a>
                                </h2>
                                <div class="entry-meta">
                                    <ul>
                                        <li class="d-flex align-items-center"><i class="bi bi-person"></i> <a href="/blog/artigos?autor=<?php echo $artigo['autor_nome']; ?>"><?php echo $artigo['autor_nome']; ?></a></li>
                                        <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="/blog/artigo/<?php echo $artigo['permanent_link']; ?>"><time datetime="<?php echo $artigo['data_publicacao']; ?>"><?php echo $artigo['data_publicacao']; ?></time></a></li>
                                        <li class="d-flex align-items-center"><i class="bi bi-chat-dots"></i> <a href="/blog/artigo/<?php echo $artigo['permanent_link']; ?>">0 Comentários</a></li>
                                    </ul>
                                </div>
                                <div class="entry-content">
                                    <p><?php echo $artigo['resumo']; ?></p>
                                    <div class="read-more">
                                        <a href="/blog/artigo.php?titulo=<?php echo $artigo['permanent_link']; ?>">Ver Tudo</a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <div class="col-lg-4">
                        <div class="sidebar">
                            <h3 class="sidebar-title">Pesquisar Artigo</h3>
                            <div class="sidebar-item search-form">
                                <form action="">
                                    <input type="text" name="search">
                                    <button type="submit"><i class="bi bi-search"></i></button>
                                </form>
                            </div>

                            <h3 class="sidebar-title">Categorias</h3>
                            <div class="sidebar-item categories">
                                <ul>
                                    <?php foreach ($categorias as $categoria) : ?>
                                        <li><a href="/blog/categoria?id=<?php echo $categoria['slug']; ?>"><?php echo $categoria['descricao']; ?> <span>(<?php echo $categoria['total_artigos']; ?>)</span></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <h3 class="sidebar-title">Artigos Recentes</h3>
                            <div class="sidebar-item recent-posts">
                                <?php foreach ($artigos as $artigo) : ?>
                                    <div class="post-item clearfix">
                                        <img src="<?php echo "/assets/img/".$artigo['imagem']; ?>" alt="">
                                        <h4><a href="/blog/artigo?titulo=<?php echo $artigo['permanent_link']; ?>"><?php echo $artigo['titulo']; ?></a></h4>
                                        <time datetime="<?php echo $artigo['data_publicacao']; ?>"><?php echo $artigo['data_publicacao']; ?></time>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6">
            <div class="footer-info">
              <h3>Conversu</h3>
              <p>
                Nacala <br>
                Nampula, Mocambique<br><br>
                <strong>Telefone:</strong> +258 86 080 2000<br>
                <strong>Email:</strong> info@conversu.co.mz<br>
              </p>
              <div class="social-links mt-3">
                <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Links Úteis</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="/">Página Inicial</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="/sobre/">Quem Somos</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="/servicos/">Serviços</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="/portfolio/">Portfolio</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="/blog/">Blog</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Nossos Serviços</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="/servicos/construcoes-sustentaveis">Construções Sustentáveis</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="/servicos/consultoria-ambiental">Consultoria Ambiental</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="/servicos/reformas-verdes">Reformas Verdes</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="/servicos/gestao-de-projetos">Gestão de Projetos</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="/servicos/certificacoes-de-sustentabilidade">Certificações de Sustentabilidade</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="/servicos/educacao-e-treinamento">Educação e Treinamento</a></li>
            </ul>
          </div>

          <div class="col-lg-4 col-md-6 footer-newsletter">
            <h4>Nossa Newsletter</h4>
            <p>Receba atualizações sobre nossos serviços e artigos sobre construção sustentável diretamente na sua caixa de entrada.</p>
            <form action="#" method="post">
              <input type="email" name="email"><input type="submit" value="Subscrever">
            </form>
          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Direitos Autorais <strong><span>Conversu</span></strong>. Todos os Direitos Reservados
      </div>
    </div>
  </footer>


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/vendor/php-email-form/validate.js"></script>
    <script src="/assets/js/main.js"></script>
</body>
</html>
