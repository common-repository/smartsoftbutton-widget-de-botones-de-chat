<?php

/**
 * @package SmartsoftButton\Admin
 * Template para incluir un admin-header, para las paginas de administracion del plugin
 */

// Echo de pruebas: 
//echo "<p>HELLO I am the view admin-head.php</p>";
global $smartsoftbutton_plugin_name;

?>
<div class='wrap'><h2><?php echo $smartsoftbutton_plugin_name ?></h2></div>
<div class='wrap smartsoftbutton-banner-box'>
<img class='image-banner' width="40" height="40" src="<?php echo SMARTSOFTBUTTON_URL . 'assets/img/AgenteChat-x-230.png'; ?>">
<h5>¿Atiendes por chat a tus clientes? Conoce también <a href="https://agentechat.com/" target="_blank">AgenteChat</a></h5>
</div>