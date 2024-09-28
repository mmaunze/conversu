<?php
if (isset($_GET['titulo'])) {
    $slug = $_GET['titulo'];

    // Configuração da conexão com o banco de dados
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

    // Função para buscar o artigo pelo slug
    function buscarArtigoPorSlug($conn, $slug)
    {
        $sql = "SELECT artigo.*, utilizador.nome AS autor_nome 
                FROM artigo 
                INNER JOIN utilizador ON artigo.autor = utilizador.id
                WHERE artigo.permanent_link = ?
                        ORDER BY artigo.data_publicacao DESC ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $slug);
        $stmt->execute();
        $result = $stmt->get_result();
        $artigo = $result->fetch_assoc();

        if ($artigo) {
            $artigo['data_publicacao'] = date('d/M/Y', strtotime($artigo['data_publicacao']));
        }

        return $artigo;
    }

    // Buscar o artigo pelo slug


    function buscarUltimosArtigos($conn)
    {
        $sql = "SELECT artigo.*, utilizador.nome AS autor_nome 
            FROM artigo 
            INNER JOIN utilizador ON artigo.autor = utilizador.id
            ORDER BY artigo.data_publicacao DESC 
            LIMIT 7";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $artigos = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($artigos as &$artigo) {
            $artigo['data_publicacao'] = date('d/M/Y', strtotime($artigo['data_publicacao']));
        }

        return $artigos;
    }

    $postagem = buscarArtigoPorSlug($conn, $slug);

    function buscarComentarios($conn)
    {
        global $postagem; 

        $id = $postagem['id'];
        $sql = "SELECT comentario_artigo.*, utilizador.nome AS autor_nome 
            FROM comentario_artigo  
            INNER JOIN utilizador ON comentario_artigo.autor = utilizador.id
            WHERE comentario_artigo.artigo = ?
            ORDER BY comentario_artigo.data_comentario DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comentarios = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($comentarios as &$comentario) {
            $comentario['data_comentario'] = date('d/M/Y', strtotime($comentario['data_publicacao']));
        }

        return $comentarios;
    }

    // Função para buscar todas as categorias
    function buscarTodasCategorias($conn)
    {
        $sql = "SELECT * FROM categoria";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Busca o artigo pelo slug
    $artigoEncontrado = buscarArtigoPorSlug($conn, $slug);
    $categorias = buscarTodasCategorias($conn);
    $recentes = buscarUltimosArtigos($conn);
    // Fechar conexão
    $conn->close();

    if ($artigoEncontrado) {
        // Atribui os valores do artigo encontrado às variáveis
        $titulo = $artigoEncontrado['titulo'];
        $imagem = $artigoEncontrado['imagem'];
        $capa = $artigoEncontrado['resumo'];
        $autor = $artigoEncontrado['autor_nome'];
        $data = date('D, d/M/Y', strtotime($artigoEncontrado['data_publicacao']));
        $conteudo = $artigoEncontrado['conteudo'];
        $categoria = $artigoEncontrado['categoria'];
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?php echo $titulo ?> - Conversu - Construções Verdes e Sustentáveis</title>
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

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
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
                    <h2></h2>
                    <ol>
                        <li><a href="/">Página Inicial </a></li>
                        <li><a href="/blog/">Blog</a></li>
                        <li><?php echo $titulo ?></li>
                    </ol>
                </div>
            </div>
        </section>

        <section id="blog" class="blog">
            <div class="container" data-aos="fade-up">
                <div class="row">
                    <div class="col-lg-8 entries">
                        <article class="entry entry-single">
                            <div class="entry-img">
                                <img src="<?php echo "/assets/img/". $imagem ?>" alt="<?php echo $capa ?>" class="img-fluid">
                            </div>
                            <h2 class="entry-title">
                                <a href="/blog/<?php echo $slug ?>"><?php echo $titulo ?></a>
                            </h2>
                            <div class="entry-meta">
                                <ul>
                                    <li class="d-flex align-items-center"><i class="bi bi-person"></i> <a href="/blog/<?php echo $slug ?>"><?php echo $autor ?></a></li>
                                    <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="/blog/<?php echo $slug ?>"><time datetime="2024-07-20"><?php echo $data ?></time></a></li>
                                    <li class="d-flex align-items-center"><i class="bi bi-folder"></i> <a href="/blog/<?php echo $slug ?>"><?php echo $categoria ?></a></li>
                                </ul>
                            </div>
                            <div class="entry-content">
                                <?php echo $conteudo ?>
                            </div>
                            
                        </article>

                        <div class="blog-author d-flex align-items-center">
                            <img src="/assets/img/blog-author.jpg" class="rounded-circle float-left" alt="">
                            <div>
                                <h4><?php echo $autor ?></h4>
                                <div class="social-links">
                                    <a href="https://twitter.com/#"><i class="bi bi-twitter"></i></a>
                                    <a href="https://facebook.com/#"><i class="bi bi-facebook"></i></a>
                                    <a href="https://instagram.com/#"><i class="bi bi-instagram"></i></a>
                                </div>
                                <p>O autor é um especialista em construções verdes e sustentabilidade, comprometido em promover práticas ecológicas e inovadoras no setor da construção civil.</p>
                            </div>
                        </div>

                      <!--  <div class="blog-comments">
                            <h4 class="comments-count">Comentarios</h4>
                            <div id="comment-1" class="comment">
                                <div class="d-flex">
                                    <div class="comment-img"><img src="/assets/img/blog/comments-1.jpg" alt=""></div>
                                    <div>
                                        <h5><a href="">Rui Dias</a> <a href="#" class="reply"><i class="bi bi-reply"></i> Responder</a></h5>
                                        <time datetime="2020-01-01">01 Jan, 2020</time>
                                        <p>Parabéns pelo excelente artigo. A sustentabilidade é crucial para o futuro da construção civil.</p>
                                    </div>
                                </div>
                            </div>
                            <div id="comment-2" class="comment">
                                <div class="d-flex">
                                    <div class="comment-img"><img src="/assets/img/blog/comments-2.jpg" alt=""></div>
                                    <div>
                                        <h5><a href="">Maria Silva</a> <a href="#" class="reply"><i class="bi bi-reply"></i> Reply</a></h5>
                                        <time datetime="2020-01-01">01 Jan, 2020</time>
                                        <p>Artigo muito informativo. Gostei das dicas sobre construções verdes.</p>
                                    </div>
                                </div>
                                <div id="comment-reply-1" class="comment comment-reply">
                                    <div class="d-flex">
                                        <div class="comment-img"><img src="/assets/img/blog/comments-3.jpg" alt=""></div>
                                        <div>
                                            <h5><a href="">Carlos Ferreira</a> <a href="#" class="reply"><i class="bi bi-reply"></i> Reply</a></h5>
                                            <time datetime="2020-01-01">01 Jan, 2020</time>
                                            <p>Concordo com a Maria, especialmente sobre a importância de certificações como LEED e BREEAM.</p>
                                        </div>
                                    </div>
                                    <div id="comment-reply-2" class="comment comment-reply">
                                        <div class="d-flex">
                                            <div class="comment-img"><img src="/assets/img/blog/comments-4.jpg" alt=""></div>
                                            <div>
                                                <h5><a href="">Ana Paula</a> <a href="#" class="reply"><i class="bi bi-reply"></i> Reply</a></h5>
                                                <time datetime="2020-01-01">01 Jan, 2020</time>
                                                <p>Obrigado pelas informações valiosas, Carlos. Vamos investir mais em construções sustentáveis.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="comment-3" class="comment">
                                <div class="d-flex">
                                    <div class="comment-img"><img src="/assets/img/blog/comments-5.jpg" alt=""></div>
                                    <div>
                                        <h5><a href="">João Batista</a> <a href="#" class="reply"><i class="bi bi-reply"></i> Reply</a></h5>
                                        <time datetime="2020-01-01">01 Jan, 2020</time>
                                        <p>Gostei muito do artigo. A sustentabilidade deve ser uma prioridade para todos.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="reply-form">
                                <h4>Deixar uma Resposta</h4>
                                <p>Seu email nao sera publicado. Campos obrigatorios * </p>
                                <form action="publicar_comentario">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <input name="nome" type="text" class="form-control" placeholder="Nome Completo*">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <input name="email" type="email" class="form-control" placeholder=" Email*">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col form-group">
                                            <input name="organizacao" type="text" class="form-control" placeholder="Organizacao">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col form-group">
                                            <textarea name="comentario" class="form-control" placeholder="Comentario*"></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Publicar Comentario</button>
                                </form>
                            </div>
                        </div>
                    </div>-->
                    <div class="col-lg-4">
                        <div class="sidebar">
                            <h3 class="sidebar-title">Categorias</h3>
                            <div class="sidebar-item categories">
                                <ul>
                                    <?php foreach ($categorias as $cat) : ?>
                                        <li><a href="/blog.php?categoria=<?php echo $cat['permanent_link']; ?>"><?php echo $cat['descricao']; ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <h3 class="sidebar-title">Postagens Recentes</h3>
                            <div class="sidebar-item recent-posts">
                                <?php foreach ($recentes as $recente) : ?>
                                    <div class="post-item clearfix">
                                        <img src="<?php echo "/assets/img/".$recente['imagem'] ?>" alt="<?php echo $recente['titulo'] ?>">
                                        <h4><a href="artigo.php?titulo=<?php echo $recente['permanent_link'] ?>"><?php echo $recente['titulo'] ?></a></h4>
                                        <time datetime="<?php echo $recente['data_publicacao'] ?>"><?php echo $recente['data_publicacao'] ?></time>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <h3 class="sidebar-title">Tags</h3>
                            <div class="sidebar-item tags">
                                <ul>
                                    <li><a href="#">Construções Verdes</a></li>
                                    <li><a href="#">Sustentabilidade</a></li>
                                    <li><a href="#">Inovação</a></li>
                                    <li><a href="#">Arquitetura</a></li>
                                    <li><a href="#">Ecologia</a></li>
                                    <li><a href="#">Ambiente</a></li>
                                </ul>
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
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-info">
                            <h3>Conversu</h3>
                            <p>
                                Nacala<br>
                                Nampula, Moçambique<br><br>
                                <strong>Telefone:</strong> +258 21 123 456<br>
                                <strong>Email:</strong> info@conversu.com<br>
                            </p>
                            <div class="social-links mt-3">
                                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 footer-links">
                        <h4>Links Úteis</h4>
                        <ul>
                            <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="#">Sobre</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="#">Serviços</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="#">Blog</a></li>
                            <li><i class="bi bi-chevron-right"></i> <a href="#">Contato</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4 col-md-6 footer-newsletter">
                        <h4>Nosso Boletim Informativo</h4>
                        <p>Inscreva-se para receber as últimas novidades e atualizações.</p>
                        <form action="">
                            <input type="email" name="email"><input type="submit" value="Inscrever">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="container d-md-flex py-4">
            <div class="me-md-auto text-center text-md-start">
                <div class="copyright">
                    &copy; Copyright <strong><span>Conversu</span></strong>. Todos os Direitos Reservados
                </div>

            </div>
            <div class="social-links text-center text-md-right pt-3 pt-md-0">
                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>
    </footer>

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