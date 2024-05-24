</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<script type="text/javascript" src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/assets/plugins/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
<script type="text/javascript" src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/assets/js/script.js"></script>
<script src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/assets/js/pcoded.min.js"></script>
<script src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/assets/js/demo-12.js"></script>
<script src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/assets/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/assets/extensions/responsive/js/responsive-custom.js"></script>
<script src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/assets/extensions/responsive/js/dataTables.responsive.min"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="dist/pell.js"></script>
<script>
    var editor = window.pell.init({
        element: document.getElementById('editor'),
        defaultParagraphSeparator: 'p',
        onChange: function(html) {
            document.getElementById('text-output').innerHTML = html;
            document.getElementById('html-output').textContent = html;
            document.getElementById('conteudo').value = html;
        }
    });

    function handleSubmitu() {
        var conteudo = document.getElementById('conteudo').value;
        if (!conteudo) {
            alert('O conteúdo não pode estar vazio!');
            return false;
        }

        $.ajax({
            url: '../../../forms/publicar_artigo.php',
            type: 'POST',
            data: $('#artigoForm').serialize(),
            success: function(response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    alert('Sucesso: ' + res.message);
                } else {
                    alert('Erro: ' + res.message);
                }
            },
            error: function() {
                alert('Ocorreu um erro ao enviar o artigo.' + error);
            }
        });
        return false;
    }
        
</script>
<!-- custom js -->
<script src="<?php echo dirname($_SERVER['PHP_SELF']); ?><?php echo dirname($_SERVER['PHP_SELF']); ?>/assets/js/vartical-layout.min.js"></script>
<script type="text/javascript" src="<?php echo dirname($_SERVER['PHP_SELF']); ?><?php echo dirname($_SERVER['PHP_SELF']); ?>/assets/pages/dashboard/custom-dashboard.js"></script>
<script type="text/javascript" src="<?php echo dirname($_SERVER['PHP_SELF']); ?><?php echo dirname($_SERVER['PHP_SELF']); ?>/assets/js/script.min.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-23581568-13');
</script>
</body>
</html>