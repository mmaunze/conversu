<?php include 'template/header.php' ?>
<main id="main">
  <div class="card-container">
    <div class="card-content">
      <?php
      require_once 'config/ConexaoMySQL.php';
      $conn = new ConexaoMysql();
      $conn->conectar();
      $sql = "SELECT * FROM artigo";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
      ?>
          <div class="card swiper-slide">
            <div class="card-titulo text-center">
            <h2 class="name"><?php echo $row['titulo']; ?></h2>
            </div>
        
            <div class="image-content">
              <span class="overlay"></span>
              <div class="card-image">
                <img src="<?php echo $row['imagem']; ?>" alt="<?php echo $row['titulo']; ?>" class="card-img" />
              </div>
              
            </div>
            
            <div class="card-resumo">
             
              <p class="description justify-content-between"><?php echo $row['resumo']; ?></p>
            </div>
            <div class="text-center">
            <form action="artigo" method="POST">
                <input type="hidden" id="artigo" name="artigo" value="<?php echo $row['id']; ?>">
                <input class="button" type="submit" value="Ver Artigo">
              </form>
            </div>
          </div>
        <?php
        }
        ?>
    </div>
    <div class="pagination"></div>
  </div>
<?php
      } else {
        echo "<p class=\"text-center h1 \"><strong>Não há artigos disponíveis.</strong></p>";
      }
      $conn->fecharConexao();
?>
</main>
<?php include 'template/footer.php' ?>