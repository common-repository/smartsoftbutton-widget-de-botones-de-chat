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

$all_options = $smartsoftbutton_frontend->get_active_options();
$channel_options = $smartsoftbutton_frontend->get_options_channels();
$extra_options = $smartsoftbutton_frontend->get_options_additional_info();
?>

<?php
// ***PRINTS DE PRUEBAS: (comentar cuando el plugin esté listo)***
echo "<div>";
//echo "<p>HELLO I am a front-end view widget-template.php</p>";
$smartsoftbutton_frontend->present_debug_options();
$smartsoftbutton_frontend->present_dirs_constants();
echo "</div>";
?>

<?php
//Agrega informacion extra al widget
//$organization_name = $extra_options['line_organization_name'];
//$organization_address = $extra_options['line_organization_address'];

// Genera cada tag del widget ID de canal : Tooltip Label : Mensaje inicial de contato
$wa = $smartsoftbutton_frontend->generate_widget_channel_tag($channel_options, SMARTSOFT_BUTTON_WHATSAPP_WEB);
$fb = $smartsoftbutton_frontend->generate_widget_channel_tag($channel_options, SMARTSOFT_BUTTON_FB_MESSENGER);

$tags  = $wa ? ' wa="'.$wa.'" ' : '';
$tags .= $fb ? ' fb="'.$fb.'" ' : '';


?>

<vue-widget  <?php echo $tags ?> ></vue-widget>