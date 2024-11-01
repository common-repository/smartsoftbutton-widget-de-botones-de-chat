<?php
/**
 * @package SmartsoftButton\Admin
 */

/** 
 * -------------------------------------
 * CLASES Y VARIABLES USADAS POR LA PAGINA
 * -------------------------------------
 */

 // Instancia de la clase que maneja la logica (hereda de SmartsoftButton_Options)
global $smartsoftbutton_admin;

?>
<?php
//Header para la pagina de administracion
echo $smartsoftbutton_admin->content_head();
?>

<?php
// ***PRINTS DE PRUEBAS: (comentar cuando el plugin esté listo)***
echo "<div>";
//echo "<p>HELLO I am the page appearance.php</p>";";

/*
  echo "<p>" . "SERVER" . "</p>";
  echo "<p>" . var_dump($_SERVER) . "</p>"; */
/*
  echo "<p>" . "REQUEST" . "</p>";
  echo "<p>" . var_dump(filter_input(INPUT_SERVER, 'REQUEST_METHOD')) . "</p>"; */
/*
  echo "<p>" . "ACTION FILTER POST" . "</p>";
  echo "<p>" . var_dump(filter_input(INPUT_POST, 'action')) . "</p>"; */

echo "</div>";
?>
<?php
/** 
 * -------------------------------------
 * RENDERIZAR pagina
 * -------------------------------------
 */
// Renderizar titulo del sub-menu
echo ("<div class='wrap'><h3>Apariencia</h3></div>");
?>

<?php
// Renderizar contenido de la pagina/sub-smenu
?>

<?php
echo $smartsoftbutton_admin->content_footer();
