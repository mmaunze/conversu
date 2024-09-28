<?php
// Conectar à base de dados
$servername = "localhost";
$username = "u555788673_super_admin";
$password = "Maunze@Conversu@Meld0";
$dbname = "u555788673_conversu";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Função para buscar todos os projetos do portfólio
function buscarPortfolios($conn) {
    $sql = "SELECT portfolio.*, utilizador.nome AS autor_nome 
            FROM portfolio 
            INNER JOIN utilizador ON portfolio.autor = utilizador.id
            ORDER BY portfolio.data_entrega DESC"; // Ordena por data de entrega mais recente
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $portfolios = $result->fetch_all(MYSQLI_ASSOC);

    return $portfolios;
}

// Buscar os projetos do portfólio
$portfolios = buscarPortfolios($conn);

// Fechar conexão com a base de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="PT">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Portfolio || CONVERSU - Construções Verdes e Sustentáveis</title>
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
          <li><a href="/">Página Inicial</a></li>
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
          <li><a href="/portfolio/" class="active">Portfolio</a></li>
          <li><a href="/blog/">Blog</a></li>
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
          <h2></h2>
          <ol>
            <li><a href="/">Página Inicial</a></li>
            <li>Portfólio</li>
          </ol>
        </div>
      </div>
    </section>

    <!-- ======= Portfolio Section ======= -->
    <section id="portfolio" class="portfolio">
      <div class="container">

        <div class="row">
          <div class="col-lg-12 d-flex justify-content-center">
            <ul id="portfolio-flters">
              <li data-filter="*" class="filter-active">Todos Projetos</li>
              <li data-filter=".filter-TIPO1">Tipo 1</li>
              <li data-filter=".filter-TIPO2">Tipo 2</li>
              <li data-filter=".filter-TIPO3">Tipo 3</li>
              <li data-filter=".filter-TIPO4">Tipo 4</li>
            </ul>
          </div>
        </div>

        <div class="row portfolio-container">
          <?php foreach ($portfolios as $portfolio) : ?>
            <div class="col-lg-4 col-md-6 portfolio-item filter-<?php echo htmlspecialchars($portfolio['categoria']); ?>">
              <div class="portfolio-wrap">
                <img src="<?php echo htmlspecialchars($portfolio['capa']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($portfolio['titulo']); ?>">
                <div class="portfolio-info">
                  <h4><?php echo htmlspecialchars($portfolio['titulo']); ?></h4>
                  <p><?php echo htmlspecialchars($portfolio['descricao']); ?></p>
                  <div class="portfolio-links d-flex">
                    <a href="<?php echo htmlspecialchars($portfolio['capa']); ?>" data-gallery="portfolioGallery" class="portfolio-lightbox" title="<?php echo htmlspecialchars($portfolio['titulo']); ?>"><i class="bx bx-zoom-in"></i></a>
                    <a href="detalhes.php?portfolio=<?php echo htmlspecialchars($portfolio['id']); ?>" class="portfolio-details-lightbox" data-glightbox="type: external" title="<?php echo "Detalhes de " . htmlspecialchars($portfolio['titulo']); ?>"><i class="bx bi-eye"></i></a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      </div>
    </section><!-- End Portfolio Section -->

  </main>

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6">
            <div class="footer-info">
              <h3>Conversu</h3>
              <p>
                Nacala <br>
                Nampula, Moçambique<br><br>
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
              <li><i class="bx bx-chevron-right"></i> <a href="/portfolio/">Portfólio</a></li>
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
              <input type="email" name="email" required><input type="submit" value="Subscrever">
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
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="/assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="/assets/vendor/php-email-form/validate.js"></script>
  <script src="/assets/js/main.js"></script>

</body>

</html>
