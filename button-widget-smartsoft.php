<?php
/*
Plugin Name: SmartsoftButton (Widget de botones de chat)
Plugin URI: https://agentechat.com/
Description: Permite configurar facilmente "Links de contacto por chat" (botones), para las redes sociales de su negocio. El Widget de botones de chat (o SmarsoftButton) aparecera sobre su pagina web, sin marcas de agua, y puede configurarse desde el administrador; permitiendo a sus clientes enviarle un primer mensaje de contacto por chat de whatsapp o facebook messenger.
Version: 1.0.1
Author: Smartsoft Solutions SAS
Author URI: https://smartsoft.com.co/
License: GPL2
*/


//-----------------------------------------------------------
// Constantes de directorios requeridos
//-----------------------------------------------------------

/**
 * SMARTSOFTBUTTON_PLUGIN_DIR = (ruta en disco)...\wp-content\plugins\button-widget-smartsoft/
 */
define( 'SMARTSOFTBUTTON_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Version del plugin
 */
define( 'SMARTSOFTBUTTON_VERSION', 1.0 );

/**
 * SMARTSOFTBUTTON_FILE = (ruta en disco)...\wp-content\plugins\button-widget-smartsoft\button-widget-smartsoft.php
 */
define( 'SMARTSOFTBUTTON_FILE', __FILE__ ); 

/**
 *   SMARTSOFTBUTTON_PATH = button-widget-smartsoft/button-widget-smartsoft.php
 */
define( 'SMARTSOFTBUTTON_PATH', plugin_basename( __FILE__ ) ); // = button-widget-smartsoft/button-widget-smartsoft.php

/**
 *  SMARTSOFTBUTTON_URL =  http://<host name>/wordpress/wp-content/plugins/button-widget-smartsoft/
 */
define( 'SMARTSOFTBUTTON_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );


//-----------------------------------------------------------
//GLOBAL VARIBLES
//-----------------------------------------------------------

/**
 * Punto de entrada a la logica de la administracion del plugin
 */
$smartsoftbutton_admin ;

/**
 * Punto de entrada a la logica del frontend del plugin
 */
$smartsoftbutton_frontend;

/**
 * Punto de entrada a logica de acciones enganchadas tipo ajax
 */
$smartsoftbutton_acciones ;

/**
 * Prefijo para funciones, varibles y cosas tipo global --> Para evitar conflictos
 * Usado como prefijo en urls/slugs y otros strings. 
 */
$smartsoftbutton_prefix = 'smartsoftbutton_';
/**
 * Varible global con el nombre del plugin
 */
$smartsoftbutton_plugin_name= 'SmartsoftButton (Widget de botones de chat)';

/**
 * Varible global con el nombre del plugin corto. 
 * Usado para titulos de los menus y otro strings
 */
$smartsoftbutton_plugin_short_name= 'SmartsoftButton';

/**
 * Varible global con el nombre de la empresa 
 * Usado para footers de los menus y otro strings
 */
$smartsoftbutton_author= 'Smartsoft Solutions S.A.S';

//-----------------------------------------------------------
//INCLUDES (utils, admin y frontend)
//-----------------------------------------------------------

include('includes/constants.php'); // centralizador de constantes
include('includes/class-options.php'); // manejador de opciones
include('includes/class-utils.php'); // utilidades generales para front o back

include('admin/class-admin.php'); // clase principal para logica de admin.
include('admin/class-admin-menu.php'); // clase para manejar el menu de admin.
include('admin/class-admin-assets.php'); // clase para manejar los assets en las vistas de admin.

include('frontend/class-frontend.php'); // clase principal para logica de frontend.

//-----------------------------------------------------------
// CLASE QUE CONTIENE FUNCIONES AJAXS y HOOKS PARA ANCLARLOS
//-----------------------------------------------------------

require_once SMARTSOFTBUTTON_PLUGIN_DIR . 'includes/class-ajax.php'; // Incluye clase para manejar acciones y funciones para ajax
global $smartsoftbutton_acciones;
$smartsoftbutton_acciones  = new SmartsoftButton_Actions(); // Crear y registra hooks de ajax

//-----------------------------------------------------------
// CLASES QUE CONTROLAN ADMINISTRACION Y FRONTEND (Crea una instancia experta en manejar cada tema)
//-----------------------------------------------------------

if (is_admin()) {

    global $smartsoftbutton_admin;
    $smartsoftbutton_admin = new SmartsoftButton_Admin();
} else {
    global $smartsoftbutton_frontend;
    $smartsoftbutton_frontend = new SmartsoftButton_Frontend();
}

//-----------------------------------------------------------
// METODOS PARA PRUEBAS Y DEBUG
//-----------------------------------------------------------
    
/**
 * Funcion de prueba, hello world para pruebas
 */
function hello_smartsoftbutton()
{
    $mensaje = 'Hello world SmartsoftButton';
    echo "<p>$mensaje</p>";
}

/**
 * Funcion de prueba, que imprime las constantes disponibles de directorios del plugin
 */
function smartsoftbutton_print_dirs_constants($mensaje='No hay mensaje')
{   
    global $smartsoftbutton_plugin_short_name;
    // .../wp-content/plugins
    echo "<p>".'Rutas del plugin: '. $smartsoftbutton_plugin_short_name ."</p>";
    echo "<p>".'Mensaje: '.$mensaje."</p>";;
    echo "<div class='wrap'>";
    echo "<pre><ul>";
    echo "<li> WP_PLUGIN_DIR = "  .WP_PLUGIN_DIR ."</li>";
    echo "<li> SMARTSOFTBUTTON_PLUGIN_DIR = ".SMARTSOFTBUTTON_PLUGIN_DIR."</li>";
    echo "<li> SMARTSOFTBUTTON_FILE = ".SMARTSOFTBUTTON_FILE."</li>";
    echo "<li> SMARTSOFTBUTTON_PATH = ".SMARTSOFTBUTTON_PATH."</li>";
    echo "<li> SMARTSOFTBUTTON_URL = ".SMARTSOFTBUTTON_URL."</li>";
    echo "</ul></pre>";
    echo "</div>"; 
    
}

/**
 * Funcion de prueba, que retorna mensaje basico para pintar en frontend
 */
function smartsoftbutton_display_frontend_simple()
{
    echo "<p>HELLO I am the front-end view with a simple message</p>";
    
}

//-----------------------------------------------------------
// HOOKS PARA PRUEBAS Y DEBUG
//-----------------------------------------------------------

if (SmartsoftButton_Constants::$DEGUB_ACTIVE) {
    //add_action( 'wp_footer', 'hello_smartsoftbutton');
    //add_action( 'admin_notices', 'smartsoftbutton_print_dirs_constants');
    //add_action( 'wp_footer', 'smartsoftbutton_display_frontend_simple');
}


//-----------------------------------------------------------
// METODOS/HOOKS PARA ENGANCHAR ACCIONES
//-----------------------------------------------------------

if ( is_admin() ) {
	// Hooks adicionales para usuarios logueados
}
else {
    //Engancha frontend
	add_action('wp_enqueue_scripts',array($smartsoftbutton_frontend, 'enqueue_frontend_widget'));
    add_action('wp_footer', array($smartsoftbutton_frontend,'display_frontend'));
}

?>