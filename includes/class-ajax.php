<?php

class SmartsoftButton_Actions{


    /**
     * Contructor de la clase
     */
    public function __construct()
    {
        // Manejador AJAX,  Button click de prueba
        add_action('wp_ajax_notify_button_click', array( $this, 'notify_button_click'));
    }

    //----------------------------------------
    // AJAX functions
    //----------------------------------------


    // --- SCRIPTS PARA CONSUMIR POR AJAX ---

    //Encola scripts y variables para eventos ajax de settings
    public static function my_test_load_scripts()
    {

        SmartsoftButton_Admin::debug_to_console("my_test_load_scripts");
        wp_enqueue_script('test-smartsoftbutton-test', plugin_dir_url(SMARTSOFTBUTTON_FILE) . 'assets/js/settings.js', array('jquery'), SMARTSOFTBUTTON_VERSION);

        wp_localize_script('test-smartsoftbutton-test', 'wp_ajax_test_vars', array(
            'url'    => admin_url('admin-ajax.php'),
        ));
    }

    // --- FUNCIONES PARA PROCESAR LLAMADOS AJAX ---

    // Función que procesa la llamada AJAX
    public static function notify_button_click()
    {
        //Chequeo de seguridad
        check_ajax_referer( 'my-settings-ajax-nonce', 'nonce' );

        $input_data = filter_input(INPUT_POST, 'metodo_post');

        //Optiene parametros especificos de la peticion (filtrar  y sanitizar)
        $ajax_configuration_mode = filter_input(INPUT_POST, 'configuration_mode', FILTER_SANITIZE_STRING );

        
        $min_fields_check  = filter_input(INPUT_POST, 'configuration_mode') ? filter_input(INPUT_POST, 'configuration_mode') : false;
        $admin_instance = new SmartsoftButton_Admin(false);
        
        
        ob_clean();
        if( !$min_fields_check ) {
            wp_send_json( array('message' => 'Data recibida insuficiente', 'input_data' => $input_data  ) );
        }
        else {

            $rta = $admin_instance->set_active_configuration($ajax_configuration_mode);

            if ($rta['todo_ok']){
                $display_config_mode = SmartsoftButton_Constants::get_display_name_configuration($ajax_configuration_mode);
                wp_send_json( array('result'=>'CORRECTO','message' => "Se ha guardado la opcion: '$display_config_mode'", 'input_data' => $input_data  ));
            }
            else{
                $message_error =  $rta['error'];
                wp_send_json( array('result'=>'ERROR', 'message' => $message_error, 'input_data' => $input_data  ));
            }

           
        }
        wp_die();
    }
}
