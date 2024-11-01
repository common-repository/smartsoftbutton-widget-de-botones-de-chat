<?php
/**
 * @package SmartsoftButton\Admin
 * Archivo class-admin-assets
 */

/**
 * Descripcion de SmartsoftButton_Admin_Assets.
 * Clase backend responsable de manejar assets del admin
 * 
 * @author diego.salinas
 */
class SmartsoftButton_Admin_Assets {

    static $sufix_assets = '-smartsoftbutton';
    static $plugin_directory;

    //-------------------------------------------------------------------
    // ADMIN PAGES WP_ENQUEUEs 
    //-------------------------------------------------------------------

    /**
     * Agrega scripts al admin head
     */
    public static function enqueue_scripts_admin_page() {

        //Agrega/encola el archivo custom-admin.js
        wp_enqueue_script('custom-admin' . SmartsoftButton_Admin_Assets::$sufix_assets, self::get_asset_path('assets/js/custom-admin.js'), array(), SMARTSOFTBUTTON_VERSION);

        //Agrega/encola OTRAS  DEPENDENCIAS js a TODAS las paginas
        // . self::file_ext('.js') agregar después del segundo parámetro para intentar cargar archivos .min
    }

    /**
     * Agrega styles en el admin head
     */
    public static function enqueue_styles_admin_page() {

        //Agrega/encola el archivo custom-admin.css
        wp_enqueue_style('custom-admin' . SmartsoftButton_Admin_Assets::$sufix_assets, self::get_asset_path('assets/css/custom-admin.css'), array(), SMARTSOFTBUTTON_VERSION);

        //Agrega/encola OTRAS  DEPENDENCIAS css a TODAS las paginas
        // Ej. Agrega/encola el archivo chosen_css
        //wp_enqueue_style('chosen_css', self::get_asset_path('assets/dependencies/chosen/chosen') . self::file_ext('.css'), array(), SMARTSOFTBUTTON_VERSION);
        // . self::file_ext('.css') agregar después del segundo parámetro para intentar cargar archivos .min
    }

    //wp_enqueue_script( jquery, , json2, 1.12.4, $in_footer );

    //-------------------------------------------------------------------
    // Agregue 'WP_ENQUEUEs' ESPECIFICOS para alguna pagina AQUI
    // (**llámelos como necesite en getAssets() desde la clase del menú de administración)
    //-------------------------------------------------------------------

    public static function enqueue_jquery_ui_1_12_1(){
        
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-form' );
        wp_enqueue_script( 'jquery-color' );
        wp_enqueue_script( 'jquery-masonry' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-widget' );
        wp_enqueue_script( 'jquery-ui-accordion' );
        wp_enqueue_script( 'jquery-ui-autocomplete' );
        wp_enqueue_script( 'jquery-ui-button' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_enqueue_script( 'jquery-ui-draggable' );
        wp_enqueue_script( 'jquery-ui-droppable' );
        wp_enqueue_script( 'jquery-ui-menu' );
        wp_enqueue_script( 'jquery-ui-mouse' );
        wp_enqueue_script( 'jquery-ui-position' );
        wp_enqueue_script( 'jquery-ui-progressbar' );
        wp_enqueue_script( 'jquery-ui-selectable' );
        wp_enqueue_script( 'jquery-ui-resizable' );
        wp_enqueue_script( 'jquery-ui-selectmenu' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_script( 'jquery-ui-spinner' );
        wp_enqueue_script( 'jquery-ui-tooltip' );
        wp_enqueue_script( 'jquery-ui-tabs' );
        wp_enqueue_script( 'jquery-effects-core' );
        wp_enqueue_script( 'jquery-effects-blind' );
        wp_enqueue_script( 'jquery-effects-bounce' );
        wp_enqueue_script( 'jquery-effects-clip' );
        wp_enqueue_script( 'jquery-effects-drop' );
        wp_enqueue_script( 'jquery-effects-explode' );
        wp_enqueue_script( 'jquery-effects-fade' );
        wp_enqueue_script( 'jquery-effects-fold' );
        wp_enqueue_script( 'jquery-effects-highlight' );
        wp_enqueue_script( 'jquery-effects-pulsate' );
        wp_enqueue_script( 'jquery-effects-scale' );
        wp_enqueue_script( 'jquery-effects-shake' );
        wp_enqueue_script( 'jquery-effects-slide' );
        wp_enqueue_script( 'jquery-effects-transfer' );
                        
        //wp_deregister_style('jquery-ui');
        wp_enqueue_style('jquery-ui', self::get_asset_path('assets/dependencies/jquery-ui-1.12.1/jquery-ui.min.css'), array(), SMARTSOFTBUTTON_VERSION, false);
    }

    /**
     * Agrega/encola scripts personalizados para settings
     * @return string name_to_handle nombre con el que fue agregagado el script
     */
    public static function enqueue_settings_events_scripts()
    {
        SmartsoftButton_Admin::debug_to_console("inicio encolar settings");
        $name_to_handle = 'settings' . SmartsoftButton_Admin_Assets::$sufix_assets;
        wp_enqueue_script($name_to_handle, self::get_asset_path('assets/js/settings.js') , array('jquery'), SMARTSOFTBUTTON_VERSION);
        
        return $name_to_handle;
    }
    //EJEMPLO de un ENQUE ESPECIFICO:
    /**
    * Agrega/encola estilos especificos para la pagina settings
    */
    //public static function enqueue_settings_styles() {
    //  //Agrega/encola el archivo chosen_css 
    //  wp_enqueue_style('chosen_css', self::get_asset_path('assets/dependencies/chosen/chosen') . self::file_ext('.css'), array(), SMARTSOFTBUTTON_VERSION);
    //}

    //-------------------------------------------------------------------
    // END WP_ENQUEUEs ESPECIFICOS
    //-------------------------------------------------------------------

    //-------------------------------------------------------------------
    // UTILS
    //-------------------------------------------------------------------
    /**
     * Obtiene la ruta completa del $asset dado
     *
     * @param string $asset
     *
     * @return string ruta completa
     */
    public static function get_asset_path($asset) {

        if (SmartsoftButton_Admin_Assets::$plugin_directory == null) {
            SmartsoftButton_Admin_Assets::$plugin_directory = plugin_dir_url(SMARTSOFTBUTTON_FILE);
        }

        $return = SmartsoftButton_Admin_Assets::$plugin_directory . $asset;

        return $return;
    }

    /**
     * 
     */
    // public static function get_includes_path($asset) {

    //     $url = includes_url();
    //     $url = $url . $asset;

    //     return $url;
    // }

    /**
     * Comprueba si podemos incluir la versión minificada o no
     *
     * @param string $ext
     *
     * @return string
     */
    private static function file_ext($ext) {
        if (!defined('SCRIPT_DEBUG') || !SCRIPT_DEBUG) {
            $ext = '.min' . $ext;
        }

        return $ext;
    }

}
