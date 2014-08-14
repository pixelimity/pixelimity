<?php

if (!defined('PXL')) {
    exit('You can\'t access direct script.');
}

global $load_scripts;

?>
    </div>
    <!-- #main -->
    <footer id="footer" class="clearfix">
      Copyright &copy; <?php echo date('Y'); ?> <?php site_name(); ?>. All rights reserved.<br/>
      Powered by <a href="http://dickecil.net/pixelimity">Pixelimity</a>.
    </footer>
    <!-- #footer -->
  </div>
  <!-- .wrap -->
  <script type="text/javascript">
    var admin_url = <?php echo json_encode(get_admin_url()); ?>,
        ajax_url = <?php echo json_encode(get_admin_url() .'/admin-ajax.php'); ?>;
  </script>
  <script type="text/javascript" src="<?php admin_url(); ?>/js/jquery.form.js"></script>
  <script type="text/javascript" src="<?php admin_url(); ?>/js/jquery.ui.min.js"></script>
  <script type="text/javascript" src="<?php admin_url(); ?>/js/functions.js"></script>
  <script type="text/javascript" src="<?php admin_url(); ?>/js/admin.js"></script>   
<?php foreach ($load_scripts as $load) : ?>
    <script type="text/javascript" src="<?php admin_url(); ?>/js/<?php echo $load; ?>.js"></script>
<?php endforeach; ?>
</body>
</html>