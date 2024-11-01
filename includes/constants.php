<?php
/**
 * @package SmartsoftButton\Includes
 * Archivo y clase genericos para manejar constantes del plugin
 */

// keys --> opciones de canales
/**
 * Constante para guardar opciones asociadas al canal whatsapp
 */
define('SMARTSOFT_BUTTON_WHATSAPP_WEB', 'wa');
/**
 * Constante para guardar opciones asociadas al canal facebook messenger
 */
define('SMARTSOFT_BUTTON_FB_MESSENGER', 'fb');

// keys --> otras opciones o recursos
/**
 * Other Example 1
 */
define('SMARTSOFTBUTTON_CONFIGURACION_MANUAL', 'CONFIGURACION_MANUAL');
/**
 * Other Example 2
 */
define('SMARTSOFTBUTTON_CONFIGURACION_JSON_AGENTECHAT', 'CONFIGURACION_JSON_AGENTECHAT');

/**
 * Clase generica para manejar constantes del plugin
 */
class SmartsoftButton_Constants {

    // Keys Arrays con constantes FUERA de la definicion de la clase
    public static $KEYS_CHANNELS = array(SMARTSOFT_BUTTON_WHATSAPP_WEB, SMARTSOFT_BUTTON_FB_MESSENGER,);
    public static $KEYS_CONFIGURATIONS = array(SMARTSOFTBUTTON_CONFIGURACION_MANUAL, SMARTSOFTBUTTON_CONFIGURACION_JSON_AGENTECHAT,);
    /**
     * Contante que permite controlar debugs por consola de la ejecucion del plugin
     */
    public static $DEGUB_ACTIVE = false;

    // Constantes DENTRO de la definicion de la clase

    //---- Nombres (Displays names) de Canales  ----

    /**
     * Display Name Whatsapp (wa)
     */
    const DISPLAY_NAME_WA = "Chat de Whatsapp";
    /**
     * Display Name Facebook Messenger (fb)
     */
    const DISPLAY_NAME_FB = "Facebook Messenger";


    //---- Formatos de IDs de Canal  ----
    
    /**
     * Formato Id de canal Whatsapp (wa)
     */
    const FORMT_ID_WA = "Número de teléfono celular";
    /**
     * Formato Id de canal Facebook Messenger (fb)
     */
    const FORMT_ID_FB = "Nombre de usuario";

    //---- Nombres (Displays names) de Canales  ----

    /**
     * Display Name Configuracion Manual
     */
    const DISPLAY_NAME_CONFIGURACION_MANUAL = "Configuración Manual";
    /**
     * Display Name Configuracion con AgenteChat 
     */
    const DISPLAY_NAME_CONFIGURACION_JSON_AGENTECHAT = "Configuración con AgenteChat";

    


    //---- Defaults ----
    /**
     * Display/texto/descripcion por defecto para canal no valido
     */
    const CANAL_NO_VALIDO = "Canal No Válido";
    
    private function __construct() {
        //Clase de uso estatico, Constructor privado
    }
    
    /**
     * Funcion que retorna los placeholders de un canal valido
     * @param string $key_channel Una constante valida de las establecidas en $KEYS_CHANNELS
     * @return array|string array con los placeholders de los campos de la configuracion del canal o string de canal no valido
     */
    public static function get_channel_placeholders($key_channel){
        
        $channel_placeholders_config = array(
            SMARTSOFT_BUTTON_WHATSAPP_WEB => array(
                "id"=>"Ingrese número de télefono celular en whatsapp",
                "name"=>"Ingrese título boton de whatsapp",
                "message"=>"Ingrese mensaje inicial chat de whatsapp",
            ),
            SMARTSOFT_BUTTON_FB_MESSENGER => array(
                "id"=>"Ingrese nombre de usuario en facebook",
                "name"=>"Ingrese título botón de facebook",
                "message"=>"Ingrese mensaje inicial chat de facebook",
            ),
        );

        $channel_placeholders = "";
        switch ($key_channel) {
            case SMARTSOFT_BUTTON_WHATSAPP_WEB:
                $channel_placeholders = $channel_placeholders_config[$key_channel];
                break;
            
            case SMARTSOFT_BUTTON_FB_MESSENGER:
                $channel_placeholders = $channel_placeholders_config[$key_channel];;
                break;
            default:
                $channel_placeholders = self::CANAL_NO_VALIDO;
                break;
        }

        return $channel_placeholders;
    }

    /**
     * Funcion que retorna el 'display name' de un canal valido (nombre visible para el usuario) 
     * @param string $key_channel Una constante valida de las establecidas en $KEYS_CHANNELS
     * @return string el 'display name' de un canal
     */
    public static function get_display_name_channel($key_channel){
        $display_name = "";
        switch ($key_channel) {
            case SMARTSOFT_BUTTON_WHATSAPP_WEB:
                $display_name = self::DISPLAY_NAME_WA;
                break;
            
            case SMARTSOFT_BUTTON_FB_MESSENGER:
                $display_name = self::DISPLAY_NAME_FB;
                break;
            default:
                $display_name = self::CANAL_NO_VALIDO;
                break;
        }

        return $display_name;
    }

    /**
     * Funcion que retorna el 'display name' de un canal valido (nombre visible para el usuario) 
     * @param string $key_channel Una constante valida de las establecidas en $KEYS_CHANNELS
     * @return string el 'display name' de un canal
     */
    public static function get_format_id_channel($key_channel){
        $display_name = "";
        switch ($key_channel) {
            case SMARTSOFT_BUTTON_WHATSAPP_WEB:
                $display_name = self::FORMT_ID_WA;
                break;
            
            case SMARTSOFT_BUTTON_FB_MESSENGER:
                $display_name = self::FORMT_ID_FB;
                break;
                
            default:
                $display_name = self::CANAL_NO_VALIDO;;
                break;
        }

        return $display_name;
    }

    /**
     * Funcion que retorna el 'display name' de una configuracion valida (nombre visible para el usuario) 
     * @param string $key_configuration Una constante valida de las establecidas en $KEYS_CONFIGURATIONS
     * @return string el 'display name' de una configuracion valida
     */
    public static function get_display_name_configuration($key_configuration){
        $display_name = "";
        switch ($key_configuration) {
            case SMARTSOFTBUTTON_CONFIGURACION_MANUAL:
                $display_name = self::DISPLAY_NAME_CONFIGURACION_MANUAL;
                break;

            case SMARTSOFTBUTTON_CONFIGURACION_JSON_AGENTECHAT:
                $display_name = self::DISPLAY_NAME_CONFIGURACION_JSON_AGENTECHAT;
                break;

            default:
                $display_name = self::CANAL_NO_VALIDO;
                break;
        }

        return $display_name;
    }
    

}
