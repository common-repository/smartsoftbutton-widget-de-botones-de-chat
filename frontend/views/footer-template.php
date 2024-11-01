<?php

/**
 * @package SmartsoftButton\Frontend
 */

 /** 
 * -------------------------------------
 * CLASES Y VARIABLES USADAS POR LA PAGINA
 * -------------------------------------
 */
global $smartsoftbutton_frontend;

$smartsoftbutton_frontend->enqueue_frontend_styles();

$all_options = $smartsoftbutton_frontend->get_active_options();
$channel_options = $smartsoftbutton_frontend->get_options_channels();
$extra_options = $smartsoftbutton_frontend->get_options_additional_info();
?>

<?php
// ***PRINTS DE PRUEBAS: (comentar cuando el plugin esté listo)***
echo "<div>";
//echo "<p>HELLO I am a front-end view footer-template.php</p>";
$smartsoftbutton_frontend->present_debug_options();
$smartsoftbutton_frontend->present_dirs_constants();

/*
      echo "<p>" . 'CHANNEL OPTIONS' . "</p>";
      echo "<p>" . print_r($channel_options) . "</p>"; */
echo "</div>";
?>


<div class="widget smartsoftbutton" style="text-align: center;">
  <?php
  $organization_name = $extra_options['line_organization_name'];
  $organization_address = $extra_options['line_organization_address'];
  $home_site_url = $extra_options['url_sitio_home'];
  $home_site_name = $extra_options['url_sitio_home_label'];
  //Agrega informacion extra al widget
  ?>
  <br /> <?php echo $organization_name; ?>
  <br /> <?php echo $organization_address; ?>
  <br /> <a href="<?php echo $home_site_url; ?>"><?php echo $home_site_name; ?></a>
</div>
<br />
</div>