<?php
/**
 * @package SmartsoftButton\Frontend
 * Archivo class-frontend
 */

/**
 * Descripcion de SmartsoftButton_Frontend
 * Clase responsable de manejar temas de frontend del plugin
 * 
 * @author diego.salinas
 */
class SmartsoftButton_Frontend {

    static $sufix_assets = '-smartsoftbutton';
    private $titulo;
    private $manager;

    public function __construct(){

        $this->titulo = 'SmartsoftButton_Frontend';
        $this->manager = SmartsoftButton_Options::instance();
    }

    /**
     * Retorna todas las opciones ALMACENADAS en el admin backend, almacenadas en opciones de worpress
     * @return array array con todas las opcioenes almacenadas
     */
    public function get_stored_options() {
        
        $options =  $this->manager->get_options_custom();
        
        return $options;
    }

    
    /**
     * Retorna todas las opciones ACTIVAS en el admin backend, almacenadas en opciones de worpress
     * @return array array con solo las opciones que se encuentran activas
     */
    public function get_active_options() {
        
        $options =  $this->manager->get_options_custom();

        //Filtra/Selecciona opciones segun la configurcion activa
        $active_options = array_diff_key($options, array_flip(["active_configuration", "configuration_agentechat"]));

        if( $this->manager->get_active_configuration() == SMARTSOFTBUTTON_CONFIGURACION_JSON_AGENTECHAT){
            $active_options = $options["configuration_agentechat"]["settings"];
        }
        
        return $active_options;
    }
    
    /**
     * Retorna un subconjunto de las opciones, relacionadas a informacion de canales. 
     * Ver tambien constants.php -> SmartsoftButton_Constants::$KEYS_CHANNELS
     * @return array (link_ids=>arrray(...), labels => array(...), messages=>array(..), displays=>array(..))
     * 
     */
    public function get_options_channels() {
        $all_options = $this->get_active_options();
        
        $sub_options =  $all_options['channels'];

        return $sub_options;
    }
    
     /**
     * Retorna informacion adicional relacionada al plugin, almacenada en opciones de worpress
     * @return array|mixed 
     */
    public function get_options_additional_info() {
        $all_options = $this->get_active_options();

        $sub_options = array(
            'line_organization_name' => $all_options['line_organization_name'],
            'line_organization_address' => $all_options['line_organization_address'],
            'url_sitio_home' => $all_options['url_sitio_home'],
            'url_sitio_home_label' => $all_options['url_sitio_home_label']
        );

        return $sub_options;
    }

     /**
     * Retorna el tag string generado para integrar un canal en el widget de front.
     * @see SmartsoftButton_Constants::$KEYS_CHANNELS para ver canales disponibles
     * @param array $channel_options arreglo de opciones asociadas a los canales
     * @param string $channel_key key de canal para consultar las opciones
     * @return string|bool retorna string del tag para el widget, o false si la opcion de display esta deshabilitada
     */
    public function generate_widget_channel_tag($channel_options, $channel_key){
        
        $tag = false;
        $sep = ":";
        
        if ($channel_options[$channel_key]['display'] == 1) { 
            $tag = "";
            $tag .= $channel_options[$channel_key]['id'] . $sep;
            $tag .= $channel_options[$channel_key]['name'] . $sep;
            $tag .= $channel_options[$channel_key]['message'];
        }

        return $tag;
    }
    

    /**
     * Permite agregrar/personalizar scripts y estilos del frontend en general.
     * Llamar desde cualquier template. Ej: widget-template.php
     */
    public function enqueue_frontend_assets(){
        //Archivo general para scripts personalizados
        wp_enqueue_style('custom-frontend'.$this->sufix_assets , plugin_dir_url(SMARTSOFTBUTTON_FILE).'assets/css/custom-frontend.css', array(), SMARTSOFTBUTTON_VERSION);
        //Archivo general para estilos personalizados
        wp_enqueue_script('custom-frontend'. $this->sufix_assets , plugin_dir_url(SMARTSOFTBUTTON_FILE).'assets/css/custom-frontend.js', array(), SMARTSOFTBUTTON_VERSION);   
    }

    /**
     * Agregar scripts y estilos especificos del widget
     */
    public function enqueue_frontend_widget(){
       //Agrega/encola el archivo frontend-widget-$sufix_assets.css
       wp_enqueue_style('frontend-widget' . SmartsoftButton_Frontend::$sufix_assets, plugin_dir_url(SMARTSOFTBUTTON_FILE).'assets/css/app.css', array(), SMARTSOFTBUTTON_VERSION);
       
       //Agrega/encola el archivo app.js as frontend-widget-$sufix_assets.js
       wp_enqueue_script('frontend-widget' . SmartsoftButton_Frontend::$sufix_assets, plugin_dir_url(SMARTSOFTBUTTON_FILE).'assets/js/app.js', array('jquery'), SMARTSOFTBUTTON_VERSION);  
      
    }

    /**
     * Presenta el contenido en frontend a insertar
     */
    public function display_frontend() {
        
        require 'views/widget-template.php';
    }
    
    

    //----------------------------------------------
    // METODOS DE PRUEBAS Y DEBUG
    //----------------------------------------------

     /**
     * Metodo para hacer pruebas.
     * Presenta las opciones almacenadas, probando que son accesibles desde el frontend
     */
    public function present_debug_options(){

        if (SmartsoftButton_Constants::$DEGUB_ACTIVE) {

            echo "<h2>" . $this->titulo . "</h2>";

            echo "<h3>" . 'Opciones como se almacenan en wordpress' . "</h3>";
            echo "<div class='wrap'>";
            echo "<pre>";
            echo print_r(get_option('smartsoftbutton_options'));
            echo "</pre>";
            echo "</div>";

            echo "<h3>" . 'Opciones de logica de Settings' . "</h3>";
            echo "<div class='wrap'>";
            echo "<pre>";
            echo print_r($this->get_stored_options());
            echo "</pre>";
            echo "</div>";

            echo "<h3>" . 'Opciones Activas = ' .  $this->manager->get_active_configuration() . "</h3>";
            echo "<div class='wrap'>";
            echo "<pre>";
            echo print_r($this->get_active_options());
            echo "</pre>";
            echo "</div>";
        }
    }
    
     /**
     * Metodo para hacer pruebas.
     * Presenta los directorios del plugin, probando que son accesibles desde el frontend
     */
    public function present_dirs_constants(){
        if (SmartsoftButton_Constants::$DEGUB_ACTIVE) {
            smartsoftbutton_print_dirs_constants('Llamadas desde frontend class '.$this->titulo);
        }
    }
    
    

}
