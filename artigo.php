<?php
include 'template/header.php';
if (isset($_POST['artigo'])) {
  require_once 'config/ConexaoMySQL.php';
  $conn = new ConexaoMysql();
  $conn->conectar();
  $id_artigo = $_REQUEST['artigo'];
  $sql = "SELECT artigo.*, categoria.descricao AS categoria_descricao , utilizador.nome as nome_autor
                FROM artigo 
                INNER JOIN categoria ON artigo.id_categoria = categoria.id_categoria 
                INNER JOIN utilizador ON artigo.autor = utilizador.id
                WHERE artigo.id = $id_artigo";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $categoria = $row['categoria_descricao'];
?>
    <main id="main">
      <section id="breadcrumbs" class="breadcrumbs">
        <div class="container">
          <div class="d-flex justify-content-between align-items-center">
            <ol>
              <li><a href="/">Home</a></li>
              <li> <a href="artigos.php">Artigos</a></li>
              <li> <?php echo $row['titulo'] ?> </li>
            </ol>
          </div>
        </div>
      </section>
      <section id="portfolio-details" class="portfolio-details">
        <div class="container">
          <div class="row gy-4 justify-content-center">
            <div class="col-lg-10">
              <div class="portfolio-description">
                <h2><?php echo $row['titulo'] ?> </h2>
                <div class="portfolio-info">
                  <ul>
                    <li><strong>Categoria: </strong> <?php echo $categoria ?> </li>
                    <li><strong>Autor: </strong> <?php echo $row['nome_autor'] ?> </li>
                    <li><strong>Data: </strong><?php
                                                echo $row['data_publicacao'] ?> </li>
                  </ul>
                </div>
                <p>
                  <?php echo $row['conteudo'] ?>
                </p>
              </div>
            </div>
          </div>
          <div class="row gy-4 mt-4">
            <div class="col-lg-12 ">
              <?php
              $categoria_id = $row['id_categoria'];
              $sql_relacionados = "SELECT * FROM artigo WHERE id_categoria = $categoria_id AND id != $id_artigo LIMIT 10";
              $result_relacionados = $conn->query($sql_relacionados);
              if ($result_relacionados->num_rows > 0) {
                echo "<p class=\"text-center\"> Artigos Relacionados </p>";
              ?>
                <div class="swiper-slide align-items-center d-flex flex-row justify-content-between">
                  <?php
                  while ($row_relacionados = $result_relacionados->fetch_assoc()) {
                  ?>
                    <div class="card swiper-slide">
                      <div class="image-content">
                        <span class="overlay"></span>
                        <div class="card-image">
                          <img src="<?php echo $row_relacionados['imagem']; ?>" alt="<?php echo $row_relacionados['titulo']; ?>" class="card-img" />
                        </div>
                      </div>
                      <div class="card-content">
                        <h2 class="name"><?php echo $row_relacionados['titulo']; ?></h2>
                        <form action="artigo.php" method="POST">
                          <input type="hidden" id="artigo" name="artigo" value="<?php echo $row_relacionados['id']; ?>">
                          <input class="button" type="submit" value="Ver Artigo">
                        </form>
                      </div>
                    </div>
                </div>
                <?php
                  }
                } else {
                  echo "<p class=\" text-center h2 \">Não há artigos relacionados disponíveis.</p>";
                }
                ?>
                </div>
            </div>
          </div>
        </div>
        </div>
      </section>
    </main>
<?php
  } else {
    echo "<p>Nenhum artigo encontrado</p>";
  }
} else {
  echo "<p class=\"text-center h1 \"><BR><BR><BR><strong>Nenhum artigo foi seleccionado.</strong></p><BR><BR><BR><BR><BR><BR><BR><BR>";
}
include 'template/footer.php';
?>