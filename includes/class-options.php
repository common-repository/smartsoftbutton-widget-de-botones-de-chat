<?php
/**
 * @package SmartsoftButton\Includes
 */


/**
 * Descripcion de SmartsoftButton_Options
 * Clase responsable de manejar las opciones de configuracion del plugin (wp_options)
 */
class SmartsoftButton_Options {

    /** 
     * Array con opciones del plugin
     * @var array  */
    public $options;

    /**
     * Nombre para almacenar/obtener las configuraciones para el plugin y posibles subplugins
     * @var string
     * 
     * Tiene que ser un nombre unico para las opciones de este plugin
     * (LLAVE USADA PARA LAS OPCIONES DENTRO DE WORDPRESS)
     */
    public $option_name = 'smartsoftbutton_options';

    /**
     * Prefijo usado dentro de la opcion para almacenar las configuaraciones 
     * (Solo usado para guardar las configuraciones dentro de la opcion, en demas logica no deberia ser usado)
     * @var string
     * 
     * Tiene que ser un prefijo unico para las opciones de este plugin
     * (SUB-LLAVE USADA PARA GUARDAR LAS OPCIONES DENTRO DE SMARTSOFTBUTTON_OPTIONS)
     * Usado tambin como prefijo para opcions de configuraicon por defecto (default settings options)
     */
    public $settings_option_prefix = 'settings_options';

    /**
     * Mantiene la ruta/path a el archivo principal del plugin
     *
     * @var string
     * 
     */
    public $plugin_path;

    /**
     * Mantiene la url al directorio principal del plugin
     *
     * @var string
     */
    public $plugin_url;

    /**
     * Patron singleton,
     * Guarda una instancia de si misma en esta variable estatica.
     *
     * @var object
     */
    private static $instance;

    /**
     * Obteine una instancia/objeto de esta clase. Si la instancia no existe sera creada.
     *
     * @return object|SmartsoftButton_Options
     */
    public static function instance() {

        if (is_null(self::$instance)) {
            self::$instance = new SmartsoftButton_Options();
        }

        return self::$instance;
    }

    /**
     * Constructor para las opciones
     */
    protected function __construct() {
        $this->options = $this->get_options_custom();
        
        $this->plugin_path = plugin_dir_path(SMARTSOFTBUTTON_FILE);
        $this->plugin_url = trailingslashit(plugin_dir_url(SMARTSOFTBUTTON_FILE));

        //Si no existen las opciones, obtine las default settings options y las guarda
        if (false == $this->options) {
        
            $defaults= $this->default_smartsoftbutton_values();
            
            //Las guarda con prefijo. Eso facilita actualizarlas o eliminarlas en conjunto
            add_option($this->option_name, $defaults); 
            //Obtiene las options sin prefijo. Para manipularlas en la logica
            $this->options = $this->get_options_custom(); 
            
            SmartsoftButton_Admin::debug_to_console('constructor de opciones ADD DEFAULTS');
        }

        //Actualiza si el plugin no existe o la version es menor
        if (!isset($this->options['version']) || $this->options['version'] < SMARTSOFTBUTTON_VERSION) {
              
             $this->upgrade();
             SmartsoftButton_Admin::debug_to_console('constructor de opciones UPGRADE');
        }

        // Se usa un patron singleton.
        // Si la instancia es nula la crea. Previene crear multiples instancias de esta clase
        if (is_null(self::$instance)) {
            self::$instance = $this;
        }

        //Chequea que todas las opciones por default existan en el $settings_option_prefix
        $this->options = $this->check_options($this->options);

        SmartsoftButton_Admin::debug_to_console('constructor de opciones OPTIONS (cargadas al terminar constructor)');
        SmartsoftButton_Admin::debug_to_console($this->options);
        // Cargar archivos de lenguajes (descomentar si existen y se utilizan en el plugin)
        //add_action('plugins_loaded', array($this, 'load_textdomain'));
    }
    
    /**
     * Retonra las opciones del plugin
     * 
     * @return mixed|void
     */
    public function get_options_custom() {
        SmartsoftButton_Admin::debug_to_console("Entro a get_options_custom");
        $options = get_option($this->option_name);
        if (false == $options) { // Si se elimina la opcion en base de datos
            SmartsoftButton_Admin::debug_to_console("Manejador de opciones no encontro opciones");
            return false;
        }
        return $options[$this->settings_option_prefix];
    }

    /**
     * Actualiza la opcion de SmartsoftButton, usando el settings_option_prefix dentro de la opcion
     *
     * @param array $val

     * @return bool
     */
    public function update_option_custom($val) {
        $options = get_option($this->option_name);
        $options[$this->settings_option_prefix] = $val;

        return update_option($this->option_name, $options);
    }

    /**
     * Convierte un opcion value a boolean 
     * (Util para manejar opciones de activo/inactivo)
     *
     * @param string $option_name
     *
     * @return bool
     */
    public function option_value_to_bool($option_name) {
        $this->options = $this->get_options_custom();

        if (isset($this->options[$option_name]) && $this->options[$option_name] == 1) {
            return true;
        }

        return false;
    }

    /**
     * Obtiene por nombre una opcion tipo arreglo, o null si no es una opcion valida
     *
     * @param string $option_name
     *
     * @return bool
     */
    public function option_value_array($option_name) {
        $this->options = $this->get_options_custom();

        foreach ($this->options as $key => $value) {
            
            if (isset($this->options[$key]) && $key==$option_name && is_array($value)) {
                return $value;
            }
        }
        return null;
    }
    
     /**
     * Verifica si todas las opciones esta configuradas en las opciones, para prevenir ignorarlas
     * Cuando se tengan nuevos cambios, esas configuraciones son agregadas a las opciones
     * 
     * @param array $options las opciones cargadas para validar
     *
     * @return mixed
     */
    public function check_options($options) {
        
        SmartsoftButton_Admin::debug_to_console("Entro a check_options");
        
        $changes = 0;
        $defaults = $this->default_smartsoftbutton_values();
        foreach ($defaults[$this->settings_option_prefix] as $key => $value) {
            if (!isset($options[$key])) {
                $options[$key] = $value;
                $changes ++;
            }
        }

        if ($changes >= 1) {
            SmartsoftButton_Admin::debug_to_console("check_options hay cambios");
            $this->update_option_custom($options);
        }

        return $options;
    }

    /**
     * Renueva/Actualiza las configuraciones/settings cuando son cambiados en una version posterior.
     *
     * @since 1.0.0
     */
    private function upgrade() {

        SmartsoftButton_Admin::debug_to_console("Entro a upgrade");
        //-------------------------
        //Seccion para agregar todos los cambios que requiera cuando se actualice la version.
        //-------------------------
        
        // Verifica que cada setting option tenga un valor (los compara contra las default) y si no se lo agrega/asigna.  
        // La version 1.0.0 arrranca con los default. 
        $defaults = $this->default_smartsoftbutton_values();     
        if (is_array($defaults)) {
            foreach ($defaults[$this->settings_option_prefix] as $key => $value) {
                if (!isset($this->options[$key])) {
                    $this->options[$key] = $value;
                    if (is_array($value)){
                        SmartsoftButton_Admin::debug_to_console("upgrade: agrego option a lista de updates: key = $key, value= ". implode(',', $value));
                    }
                }
            }
        }

        // Configura la versión actual ahora que hemos realizado todas las actualizaciones necesarias
        // Agrega la version a las opciones
        $this->options['version'] = SMARTSOFTBUTTON_VERSION;
        $this->update_option_custom($this->options);
    }
    
    /**
     * Reinicia las opciones a su valor por defecto ( default values options )
     */
    public function reset_to_default_values()
    {  
       SmartsoftButton_Admin::debug_to_console("Entro a reset_to_default_values");
       $options = $this->default_smartsoftbutton_values();
       update_option($this->option_name, $options); 

       $this->options = $options;
    }

    /**
     * Establece las configuraciones por defecto para SmartsoftButton
     * @return array
     */
    public function default_smartsoftbutton_values() {

        $options = array(
            $this->settings_option_prefix => array(
                //configuration manual
                //appearance
                'button_color' => '#ffffff',
                'button_text' => '#0000ff',
                //organization
                'line_organization_name' => 'Nombre de mi empresa',
                'line_organization_address' => 'Direccion de mi empresa',
                'url_sitio_home' => '/',
                'url_sitio_home_label' => 'Pagina de inicio de mi sitio web',
                //channels
                'channels' => array(
                    SMARTSOFT_BUTTON_WHATSAPP_WEB => array(
                        "id"=>"",
                        "name"=>"Whatsapp",
                        "message"=>"Hola, requiero más información",
                        "display"=> 1
                    ),
                    SMARTSOFT_BUTTON_FB_MESSENGER => array(
                        "id"=>"",
                        "name"=>"Facebook Messenger",
                        "message"=>"Hola, requiero más información",
                        "display"=> 1
                    ),
                ),
                //configuration agentechat
                'active_configuration' => SMARTSOFTBUTTON_CONFIGURACION_MANUAL,
                'configuration_agentechat' => array()
            )
        );

        return $options;
    }

    /**
     * Retorna la configuracion que se encuentra activa: Manual o AgenteChat
     * Ver tambien constants.php -> SmartsoftButton_Constants::$KEYS_CONFIGURATIONS
     * @return string configuracion activa 
     * 
     */
    public function get_active_configuration()
    {
        $all_options = $this->get_options_custom();

        $sub_option_configuration = $all_options['active_configuration'];

        return $sub_option_configuration;
    }

    /**
     * Carga el textdomain del plugin
     * 
     * Funcion para cargar archivos de multilenguaje
     */
    public static function load_textdomain() {
        // Descomentar si se cuenta con archivos de multilenguaje
        //load_plugin_textdomain( 'smartsoftbutton', false, dirname( plugin_basename( SMARTSOFTBUTTON_FILE ) ) . '/languages/' );
    }

}
