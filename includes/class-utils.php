<?php
/**
 * @package SmartsoftButton\Includes
 *  Archivo class-utils
 */

 /**
 * Clase generica para manejar utilidades del plugin
 */
class SmartsoftButton_Utils {

    private function __construct() {
        //Clase de uso estatico, Constructor privado
    }
    
    
    /**
     *  Convierte un json string en un array.
     *  Decodifica un string de JSON en un array
     * @param string $json_text texto con un formato json que sera convertido en array
     * @return array Array con valores del json mapeados
     */
    public static function convert_json_string_to_array( $json_text ){
        $decoded_text = html_entity_decode($json_text);
        $my_array = json_decode($decoded_text, true);

        return $my_array;
    }

    

    /**
     * Convierte un json string en un array.
     * Retorna la representación JSON del valor dado
     * @param array $my_array array que sera convertido a un texto con formato json
     * @return string texto con un formato json
     */
    public static function convert_array_to_json_string( $my_array ){
        $encoded_json_text = json_encode($my_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $encoded_json_text;
    }

    /**
     * Funciona como JSON Validator.
     * Comprobará que la variable $data no esté vacía. Si $data está vacío, devolverá falso.
     * json_decode analiza los datos y devuelve la variable PHP si la cadena es válida. 
     * 
     * Si la cadena no es válida, generará el error. El carácter "@" suprimirá el error.
     * Verificará si $data es una cadena JSON válida comparándola con JSON_ERROR_NONE. json_last_error () devuelve el último error cuando json_decode () ha llamado si hay alguno.
     * @param $data json string a validar
     * @return bool true si el json es valido, false si no lo es
     */
    public static function json_validator($data = NULL){
        if (!empty($data)) {
            @json_decode($data);
            if (SmartsoftButton_Constants::$DEGUB_ACTIVE) {
                SmartsoftButton_Admin::debug_to_console("json_last_error= ".json_last_error());
            }
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }

}