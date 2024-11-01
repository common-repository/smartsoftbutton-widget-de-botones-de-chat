<?php
/**
 * @package SmartsoftButton\Admin
 * Archivo class-admin-menu
 */

/**
 * Descripcion de SmartsoftButton_Admin_Menu
 * Clase responsable controlar el menu de administracion
 * Clase que sirve a SmartsoftButton_Admin 
 * 
 * @author diego.salinas
 */
class SmartsoftButton_Admin_Menu {

    /**
     * Propiedad utilizada para almacenar el objeto de destino (administrador de clase)
     * @var object $target_object conexion/referencia con clase admin
     */
    private $target_object;

    /**
     * El slug principal de los elementos del submenú en función de si los paneles están deshabilitados o no.
     * @var string
     */
    private $parent_slug;

    /**
     * Constructor del menu de administracion
     * Configura el target_object (clase principal de admin) y agrega acciones
     *
     * @param object $target_object
     */
    public function __construct($target_object) {

        SmartsoftButton_Admin::debug_to_console("admn menu contructor start");
        $this->target_object = $target_object;

        add_action('admin_menu', array($this, 'create_admin_menu'), 10);

        if (!function_exists('is_plugin_active_for_network')) {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }

        if (is_plugin_active_for_network(SMARTSOFTBUTTON_PATH)) {
            add_action('network_admin_menu', array($this, 'create_admin_menu'), 5);
        }

        global $smartsoftbutton_prefix;
        $this->parent_slug = $smartsoftbutton_prefix . 'settings';

        SmartsoftButton_Admin::debug_to_console("admin menu contructor end");
    }

    /**
     * Crea el admin menu
     */
    public function create_admin_menu() {

        SmartsoftButton_Admin::debug_to_console("admin menu create_admin_menu start");

        $menu_name = 'settings';

        // Agrega la main page
        $page_title = 'Configuraciones SmartsoftButton';
        $menu_title = 'Menu SmartsoftButton';
        $capability = 'manage_options';

        global $smartsoftbutton_prefix;
        $menu_slug = $smartsoftbutton_prefix . $menu_name;

        //La clase admin se encarga de hacer load del html de la pagina
        $function = array($this->target_object, 'load_page');
        
        $icon_url = SMARTSOFTBUTTON_URL.'assets/img/icon.png'; // optional
        $position = 2; // optional

        add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);

        $this->add_submenu_pages();

        SmartsoftButton_Admin::debug_to_console("admin menu create_admin_menu finish");
    }

    /**
     * Prepara y agrega páginas de submenú al menú del Plugin para Wordpress:
     * En este caso, el menú de smartsoftbutton son:
     * - settings
     * - appearance
     * 
     * @return void
     */
    private function add_submenu_pages() {
        foreach ($this->get_submenu_types() as $submenu_type => $submenu) {
            if (isset($submenu['color'])) {
                $submenu_page = $this->prepare_submenu_page($submenu['label'], $submenu['slug'], $submenu['color']);
            } else {
                $submenu_page = $this->prepare_submenu_page($submenu['label'], $submenu['slug']);
            }

            $this->add_submenu_page($submenu_page, $submenu_type);
        }
    }

    /**
     * Determina qué tipos de submenú se deben agregar como página de submenú.
     * Vea la estructura del array, que representa un item de menú, que se debe retornar.
     * El valor devuelto de este metodo es utilizado por los métodos add_submenu_pages y prepare_submenu_page
     *
     * @return array
     * Array structure:
     * ```
     * array(
     *   $submenu_name => array(
     *        'color' => $font_color,
     *        'label' => 'text-label',
     *        'slug'  => $menu_slug,
     *        ),
     *   ..,
     * )
     * ```
     * - $font_color (opcional), se puede dejar sin esa caracteristica.
     *
     */
    private function get_submenu_types() {

        $submenu_types = array();

        $submenu_types['settings'] = array(
            //'color' => '#f18500',
            'label' => 'Configurar Botones',
            'slug' => 'settings',
        );

        /*$submenu_types['appearance'] = array(
            //'color' => '#f18500',
            'label' => 'Apariencia',
            'slug' => 'appearance',
        );*/

        $submenu_types['others'] = array(
            //'color' => '#f18500',
            'label' => 'Otras Opciones',
            'slug' => 'others',
        );

        return $submenu_types;
    }

    /**
     * Prepara un array que se puede usar para agregar una página de submenú del Plugin, en el menu Wordpress 
     * El valor retornado (array) de este método es utilizado por el método add_submenu_page
     * 
     * @param string $submenu_name
     * @param string $submenu_slug
     * @param string $font_color (optional)
     *
     * @return array  submenu preparado
     */
    private function prepare_submenu_page($submenu_name, $submenu_slug, $font_color = '') {
        global $smartsoftbutton_plugin_short_name;
        global $smartsoftbutton_prefix;
        return array(
            'parent_slug' => $this->parent_slug,
            'page_title' => $smartsoftbutton_plugin_short_name . ': ' . $submenu_name,
            'menu_title' => $this->parse_menu_title($submenu_name, $font_color),
            'capability' => 'manage_options',
            'menu_slug' => $smartsoftbutton_prefix . $submenu_slug,
            'submenu_function' => array($this->target_object, 'load_page'),
        );
    }

    /**
     * Prepara/Parsea el título del menú (agrega el estilo $font_color al $menutitle en un objeto span)
     *
     * @param string $menu_title
     * @param string $font_color
     *
     * @return string span del $menu_title con el color $font_color
     */
    private function parse_menu_title($menu_title, $font_color) {
        if (!empty($font_color)) {
            $menu_title = '<span style="color:' . $font_color . '">' . $menu_title . '</span>';
        }

        return $menu_title;
    }

    /**
     * Agrega una página de submenu del Plugin en el menu de WordPress
     *
     * @param array $submenu_page submenu preparado por prepare_submenu_page()
     * @param string $submenu_type llave de la definicion de submenu dada por get_submenu_types()
     */
    private function add_submenu_page($submenu_page, $submenu_type) {
        $page = add_submenu_page(
                $submenu_page['parent_slug'], $submenu_page['page_title'], $submenu_page['menu_title'], $submenu_page['capability'], $submenu_page['menu_slug'], $submenu_page['submenu_function']);
        
        $this->add_assets($page, $submenu_type);
        $this->add_ajax_actions($submenu_type);
    }

    /**
     * Añade stylesheets y scripts al admin page
     *
     * @param string  $page
     */
    private function add_assets($page, $submenu_type) {

        // Encola Assets: Estilos y scripts generales para adim
        SmartsoftButton_Admin_Assets::enqueue_styles_admin_page();
        SmartsoftButton_Admin_Assets::enqueue_scripts_admin_page();
        
        //Encola Assets: Version de jquery en uso 
        //SmartsoftButton_Admin_Assets::enqueue_jquery_ui_1_11_4(); //(Anterior)
        //(Desde 2020-12-24: jquery_ui_1.12.1 )
        SmartsoftButton_Admin_Assets::enqueue_jquery_ui_1_12_1();        

        SmartsoftButton_Admin::debug_to_console("admin menu assets added: type=$submenu_type, page= $page");
        
        // Otra opcion menos recomendada
        //add_action( 'admin_print_styles-' . $page, array( 'SmartsoftButton_Admin_Assets', 'enqueue_styles_admin_page' ) );
        //add_action( 'admin_print_scripts-' . $page, array( 'SmartsoftButton_Admin_Assets', 'enqueue_scripts_admin_page' ) );
    }

    /**
     * Agregra funciones para manejar acciones de ajax a la pagina
     * @param string $submenu_type llave de la definicion de submenu dada por get_submenu_types()
     */
    private function add_ajax_actions($submenu_type){

        switch ($submenu_type) {
            case 'settings':

                //Accion para encolar scripts y variables para eventos ajax de settings (registro del callback)
                add_action( 'wp_enqueue_scripts', array($this,  'my_settings_load_scripts' ));
                add_action( 'admin_enqueue_scripts',  array($this, 'my_settings_load_scripts' ));

                break;
            
            default:
                // Acciones para todas las paginas
                // Por defecto ninguna
                break;
        }
    }

    // Funcines de encolar scrtips para hooks de ajax

    public function my_settings_load_scripts()
    {
        // Acciones de ajax para la pagina de settings

        $name_to_localize = SmartsoftButton_Admin_Assets::enqueue_settings_events_scripts();
        SmartsoftButton_Admin::debug_to_console("add_ajax_actions: $name_to_localize");

        wp_localize_script($name_to_localize, 'ajax_settings_vars', array(
            'url'    => admin_url('admin-ajax.php'),
            'nonce'  => wp_create_nonce( 'my-settings-ajax-nonce' ),
            'action' => 'notify_button_click'
        ));
    }
}
