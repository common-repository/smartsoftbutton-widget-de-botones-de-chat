<?php
/**
 * @package SmartsoftButton\Admin
 * Archivo class-admin
 */

/**
 * Descripcion de SmartsoftButton_Admin
 * Clase responsable de manejar temas del admin/backend del plugin
 * Singleton. Esta clase es para el backend, extensible/usable para todas las clases secundarias.
 * @author diego.salinas
 */

class SmartsoftButton_Admin extends SmartsoftButton_Options {


    /** 
     * Propiedad con el menu de admnistracion
     * @var type  
     */
    public $menu;

    /**
     * @param $init_menus bool False cuando no se requieren utilidades/funciones visules (Ej. En ajax), True cuando si (por defecto)
     */
    public function __construct($init_menus=true) {

        parent::__construct();
        
        if($init_menus)
        {
            add_action('plugins_loaded', array($this, 'init_admin'));
            add_action('admin_init', array($this, 'init_settings'));
        }
    }

    /*
     * Funcion de inicializacion cuando el plugin esta cargado.
     */
    public function init_admin() {
        SmartsoftButton_Admin::debug_to_console("init admin antes");

        //Inicializa el menu de aministracion
        $this->menu = new SmartsoftButton_Admin_Menu($this);

        SmartsoftButton_Admin::debug_to_console("init admin despues");
    }

    //-----------------------------------------------------
    //LOGICA DEL PLUGIN PARA RENDERIZAR
    //-----------------------------------------------------

    /**
     * Función de Init para la configuración del plugin
     */
    public function init_settings() {
        SmartsoftButton_Admin::debug_to_console("init settings antes ");
        $this->options = $this->get_options_custom();
        SmartsoftButton_Admin::debug_to_console("init settings despues");
    }

    public function handle_post_request() {
        //TO-IMPROVE
    }

    /**
     * Muestra notificaciones si tenemos una
     */
    public function show_notification() {
        //TO-IMPROVE
    }

    /**
     * Renderiza el encabezado de la página de administración para el plugin
     */
    public function content_head() {

        require 'views/admin-head.php';
    }

    /**
     * Renderiza el pie de página de la página de administración para el plugin
     */
    public function content_footer() {

        require 'views/admin-footer.php';
    }

    /**
     * Renderiza el sub-encabezado de la página de administración para el plugin
     */
    public function content_config_selector() {

        require 'views/config-selector.php';
    }

    /**
     * Carga la página de un item de menú en el plugin, de acuerdo al parametro GET 'page'
     */
    public function load_page() {

        //-------------------------------
        // **Agregar aqui acciones personalizadas para agregar o preparar las páginas.

        // Carga la página filtrando la entrada get request param 'page' de la URL
        switch (filter_input(INPUT_GET, 'page')) {

            case 'smartsoftbutton_appearance':
                require_once( $this->plugin_path . 'admin/pages/appearance.php' );
                break;

            case 'smartsoftbutton_others':
                require_once( $this->plugin_path . 'admin/pages/others.php' );
                break;
            
            case 'smartsoftbutton_settings':
            default:
                require_once( $this->plugin_path . 'admin/pages/settings.php' );
                break;
        }
        
    }

    //-----------------------------------------------------
    //LOGICA DEL PLUGIN PARA PROCESAR OPCIONES/CONFIGURACIONES
    //-----------------------------------------------------

    /**
     * Permite reiniciar las configuraciones a su valor por defecto
     */
    public function reset_to_default_values() {
        parent::reset_to_default_values();
    }

    /**
     * Obtiene la configuracion que se encuentra activa
     */
    public function get_active_configuration() {
        return parent::get_active_configuration();
    }
    

    /**
     * Cambia la configuracion que se encuentra activa: Manual o AgenteChat
     * Ver tambien constants.php -> SmartsoftButton_Constants::$KEYS_CONFIGURATIONS
     * @param string sub_option_configuration_mode
     * @return array|mixed Una key 'todo_ok'= true or
     *  'todo_ok'=false y una key 'error' con la informacion relacionada al error.
     * 
     */
    public function set_active_configuration($sub_option_configuration_mode)
    {
        $rta = array('todo_ok' => true);

        $all_options = $this->get_options_custom();


        //Valida que el modo de configuracion sea una constante valida
        if (! in_array($sub_option_configuration_mode, SmartsoftButton_Constants::$KEYS_CONFIGURATIONS, true)) {
            $rta['todo_ok'] = false;
            $rta['error'] = 'El valor del modo de configuración no es valido. Se recibio el valor =  "' . $sub_option_configuration_mode.'"'
            .'; Se esperaba uno de estos valores: '. '[' . implode(", ", SmartsoftButton_Constants::$KEYS_CONFIGURATIONS) . ']';
            return $rta;
        }
 

        $all_options['active_configuration'] = $sub_option_configuration_mode;

        $this->update_option_custom($all_options);


        return $rta;
    }

    /**
     * Retorna un subconjunto de las opciones, relacionadas a informacion de canales. 
     * Ver tambien constants.php -> SmartsoftButton_Constants::$KEYS_CHANNELS
    * @return array (link_ids=>arrray(...), labels => array(...), messages=>array(..), displays=>array(..))
     * 
     */
    public function get_links_data_channels() {
        $all_options = $this->get_options_custom();
        
        $sub_options = $all_options['channels'];

        return $sub_options;
    }

    /**
     * Actualiza un subconjunto de las opciones, relacionadas a informacion de canales. 
     * Ver tambien constants.php -> SmartsoftButton_Constants::$KEYS_CHANNELS
     * 
     * @param array $sub_options array(link_ids=>array(...), labels => array(...), messages=>array(..), displays=>array(..))
     * @return array|mixed Una key 'todo_ok'= true or
     *  'todo_ok'=false y una key 'error' con la informacion relacionada al error.
     * 
     */
    public function set_links_data_channels($sub_options) {

        $all_options = $this->get_options_custom();

        //Valida que la estructura del array que llego por parametro sea respetada
        $rta = $this->validate_params_structure($sub_options);
        if ($rta['todo_ok'] == false) {
            return $rta;
        }

        //**Se pueden hacer mas validaciones de campos aqui como filters

        //Valida que los ids de canal no tenga espacios
        //$link_ids = array_map(function($o) { return $o->id; }, $sub_options); (deprecated)
        $channels = array_keys($sub_options);
        SmartsoftButton_Admin::debug_to_console($channels);

        foreach ($sub_options as $channel) {
            //Revisa si hay caracteres en blanco en la url
            $id = $channel['id'];
            if (preg_match('/\s/', $id)) {
                $rta['todo_ok'] = false;
                $rta['error'] = 'El identificador de canal no puede contener espacios <br/> Se recibio el valor =  "' . $id.'"';
                return $rta;
            }
        }

        $all_options['channels'] = $sub_options;

        //Valida si hay opciones nuevas(nueva key) en el array de options recibido, si las hay las agrega.
        //$this->check_options($all_options);

        $this->update_option_custom($all_options);


        return $rta;
    }


    /**
     * Valida la estructura correcta en los parámetros pasados de las vistas a la lógica.
     * Las claves en el array de primer nivel deben ser los canales
     * Las claves de el array de segundo nivel deben ser las propiedades por cada canal
     * @param array $sub_options array 
     * @return array|mixed Una key 'todo_ok'= true or
     *  'todo_ok'=false y una key 'error' con la informacion relacionada al error.
     */
    public function validate_params_structure($sub_options) {
        $rta = array('todo_ok' => true);

        $keys_esperadas  = SmartsoftButton_Constants::$KEYS_CHANNELS;
        $keys_sub_arrays= ['id', 'name','message', 'display'];
        // Valida las keys utilizadas para cambio de parametros entre logia y vista 

        if (array_keys($sub_options) !== $keys_esperadas) {
            $rta['todo_ok'] = false;
            $rta['error'] = 'Se esperaban datos por cada una de estos canales: ' . '[' . implode(", ",$keys_esperadas) . ']'
            . '<br> Se recibio: ' . json_encode($sub_options);
            return $rta;
        }

        //Valida las Keys utilizadas para guardar los datos en el sistema de options (ver archivo constantes.php)
        foreach ($sub_options as $sub_array) { // sub array channel
            if ($keys_sub_arrays !== array_keys($sub_array)) {
                $rta['todo_ok'] = false;
                $rta['error'] = 'Se esperan 4 campos por canal ' . implode(", ",$keys_sub_arrays)
                . ' en ese orden <br/> Se recibio: ' . json_encode($sub_options);
                return $rta;
            }
        }

        return $rta;
    }

    /**
     * Devuelve las llaves para la informacion adicional que se puede editar.
     * @return array|mixed 
     */
    public function get_additional_info_keys() {

        $additional_info_keys = array(
            'line_organization_name',
            'line_organization_address',
            'url_sitio_home' ,
            'url_sitio_home_label' 
        );

        return $additional_info_keys;
    }
    
    /**
     * Devuelve las etiquetas para la informacion adicional que se puede editar.
     * @return array|mixed 
     */
    public function get_additional_info_labels() {

        $sub_options_labels = array(
            'line_organization_name' => 'Nombre de la Organización',
            'line_organization_address' => 'Dirección de la Organización',
            'url_sitio_home' => 'Link a pagina de Inicio',
            'url_sitio_home_label' => 'Título para el link a la pagina de inicio'
        );

        return $sub_options_labels;
    }

    /**
     * Devuelve los valores configurados para la informacion adicional que se puede editar.
     * @return array|mixed 
     */
    public function get_additional_info_values() {
        $all_options = $this->get_options_custom();

        $sub_options = array(
            'line_organization_name' => $all_options['line_organization_name'],
            'line_organization_address' => $all_options['line_organization_address'],
            'url_sitio_home' => $all_options['url_sitio_home'],
            'url_sitio_home_label' => $all_options['url_sitio_home_label']
        );

        return $sub_options;
    }

    /**
     * Actualiza las configuraciones de información adicional
     * @param array $sub_options las nuevas configuraciones 
     * @return array|mixed Una key 'todo_ok'= true or
     *  'todo_ok'=false y una key 'error' con la informacion relacionada al error.
     */
    public function set_additional_info($sub_options) {
        $all_options = $this->get_options_custom();

        $rta = array('todo_ok' => true);

        //Valida que el parametro sea un array y no este vacio
        if (!is_array($sub_options) || empty($sub_options)) {
            $rta['todo_ok'] = false;
            $rta['error'] = 'Se espera un array con los campos a guardar'
                    . '<br/> Se recibio: ' . json_encode($sub_options);

            return $rta;
        }

        //Valida que cada variable este inicializada
        foreach ($sub_options as $key => $value) {

            if (!isset($key) || empty($value)) {
                $rta['todo_ok'] = false;
                $rta['error'] = 'Se espera que todas los campos tengan un valor diferente de vacio.'
                        . '<br/> Se recibio: ' . json_encode($sub_options)
                        . '<br/> Con el valor para ' . $key . ' = ' . $value;
                return $rta;
            }
        }
        
        //Verifica que la url del sition no tenga espacios
        if (preg_match('/\s/', $sub_options ['url_sitio_home'])) {
                $rta['todo_ok'] = false;
                $labels = $this->get_additional_info_labels();
                $rta['error'] = 'La url "'.$labels['url_sitio_home'].'" no puede contener espacios '
                        . '<br/> Se recibio la Url =  "' . $sub_options ['url_sitio_home'].'"';
                return $rta;
            }
        
        $all_options['line_organization_name'] = $sub_options['line_organization_name'];
        $all_options['line_organization_address'] = $sub_options['line_organization_address'];
        $all_options['url_sitio_home'] = $sub_options['url_sitio_home'];
        $all_options['url_sitio_home_label'] = $sub_options['url_sitio_home_label'];

        $this->update_option_custom($all_options);

        return $rta;
    }

    /**
     * Devuelve la llave para la configuracion por texto/json desde agentechat para que se pueda usar.
     * @return string 
     */
    public function get_config_agentechat_key() {

        $additional_info_keys = 'configuration_json_agentechat';

        return $additional_info_keys;
    }
    
    /**
     * Devuelve la etiqueta para la configuracion desde agentechat para que se pueda mostrar.
     * @return string 
     */
    public function get_config_agentechat_label() {

        $sub_options_label = 'Texto de configuración copiado desde AgenteChat';

        return $sub_options_label;
    }

    /**
     * Devuelve el  valor  para la configuruacion de json de agentechat para que se puede editar.
     * @return array|mixed 
     */
    public function get_config_agentechat_value() {
        $all_options = $this->get_options_custom();

        $sub_option_config =  $all_options['configuration_agentechat'];

        return $sub_option_config;
    }

    /**
     * Actualiza la configuracion de json de agentechat para que se pueda almacenar
     * @param string $sub_option_config_info la nueva configuracion (json string), sera convertido a array antes de guardarlo
     * @return array|mixed Una key 'todo_ok'= true or
     *  'todo_ok'=false y una key 'error' con la informacion relacionada al error.
     */
    public function set_config_agentechat_value($sub_option_config_info) {
        $all_options = $this->get_options_custom();

        $rta = array('todo_ok' => true);

        //Valida que no este vacio el json y cumpla el formato
        //TODO Mejora - validar el json - con los campos obtenidos de AgenteChat
        if ( SmartsoftButton_Utils::json_validator($sub_option_config_info) === false ) {
            $rta['todo_ok'] = false;
            $rta['error'] = 'Se espera una configuración valida, por favor verifique que la copió correctamente'
                    . '<br/> Se recibio: ' . $sub_option_config_info;
            return $rta;
        }

        $config_agentechat_value = SmartsoftButton_Utils::convert_json_string_to_array($sub_option_config_info);
        //Valida que la variable este declarada, no sea null, sea texto, y no este vacia
        if (!is_array($config_agentechat_value) || empty($config_agentechat_value)) {
            $rta['todo_ok'] = false;
            $rta['error'] = 'Se espera una configuración no vacia, por favor copiela desde AgenteChat'
                    . '<br/> Se recibio: ' . json_encode($config_agentechat_value);

            return $rta;
        }
        
        $all_options['configuration_agentechat'] = $config_agentechat_value;

        //print_r($all_options);
        $this->update_option_custom($all_options);

        return $rta;
    }


    //-----------------------------------------------------
    //DEBUG
    //-----------------------------------------------------
    /**
     * Debugger para hacer print de $data en la js console
     * 
     * @param type $data
     */
    public static function debug_to_console($data, $var_export=false) {

        if (SmartsoftButton_Constants::$DEGUB_ACTIVE and wp_doing_ajax() == false) {
            if (is_array($data) and $var_export == false ) {
                $output = "<script>console.log( 'Debug Objects: " . implode(',', $data) . "' );</script>";
            }
            elseif ( is_array($data) and $var_export == true ) {
                $output = "<script>console.log( 'Debug Objects: " . var_export($data) . "' );</script>";
            } 
            else {
                $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";
            }
            echo $output;
        }
    }

    public function get_dato_prueba(){
        return "Hola mundo desde admin";
    }

}

/*End of File*/ 



