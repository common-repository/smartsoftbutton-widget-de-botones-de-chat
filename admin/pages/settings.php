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

//Options de los links de la canales
$channels_data = $smartsoftbutton_admin->get_links_data_channels();

//Option con los valores para configuracion desde agentechat
$config_agentechat_value = $smartsoftbutton_admin->get_config_agentechat_value();
$config_agentechat_info =  SmartsoftButton_Utils::convert_array_to_json_string($config_agentechat_value);

//Otros Options con informacion adicional
$additional_info = $smartsoftbutton_admin->get_additional_info_values();

?>
<?php
//Header para la pagina de administracion
echo $smartsoftbutton_admin->content_head();

?>

<?php
// ***PRINTS DE PRUEBAS: (comentar cuando el plugin esté listo)***
echo "<div>";
//echo "<p>HELLO I am the page settings.php</p>";
/*
echo "<p>" . "INIT OPTIONS" . "</p>";
echo "<p>" . print_r($smartsoftbutton_admin->get_options_custom()) . "</p>";

echo "<p>" . "INIT OPTIONS VARS" . "</p>";
echo "<p>" . print_r($channels_data) . "</p>";
echo "<p>" . print_r($config_agentechat_value) . "</p>";
echo "<p>" . print_r($config_agentechat_info) . "</p>";
echo "<p>" . print_r($additional_info) . "</p>";*/

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
 * PROCESAR Acciones POST (Antes de renderizar la pagina)
 * -------------------------------------
 */

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');

if ($method == 'POST' && is_admin()) {
    /*
      echo "<p>" . "POST VALUES" . "</p>";
      echo "<p>" . var_dump(filter_input_array(INPUT_POST)) . "</p>"; */

    $action_clicked = filter_input(INPUT_POST, 'action');
    $form_name = filter_input(INPUT_POST, 'form-name');

    switch ($action_clicked) {
        case "salvaropciones1":

            $params_prefix = array('param-link-', 'param-label-', 'param-message-', 'param-display-');
            $new_channels_data = array();

            foreach (SmartsoftButton_Constants::$KEYS_CHANNELS as $key_value) {
                $new_channels_data[$key_value]['id'] = filter_input(INPUT_POST, $params_prefix[0] . $key_value);
                $new_channels_data[$key_value]['name']= filter_input(INPUT_POST, $params_prefix[1] . $key_value);
                $new_channels_data[$key_value]['message'] = filter_input(INPUT_POST, $params_prefix[2] . $key_value);
                $new_channels_data[$key_value]['display'] = filter_input(INPUT_POST, $params_prefix[3] . $key_value);
            }
            $rta = $smartsoftbutton_admin->validate_params_structure($new_channels_data, SmartsoftButton_Constants::$KEYS_CHANNELS);

            if ($rta['todo_ok']) {
                /*
                  echo "<p>" . 'NEW CHANNELS DATA' . "</p>";
                  echo "<p>" . print_r($new_channels_data) . "</p>"; */

                $guardar = $smartsoftbutton_admin->set_links_data_channels($new_channels_data);
                if ($guardar['todo_ok']) {
                    //Recarga datos de las opciones guardadas
                    $channels_data = $smartsoftbutton_admin->get_links_data_channels();

                    echo ("<div class='updated notice is-dismissible' style='padding: 10px'>Opciones para <strong>"
                        . $form_name . "</strong> guardadas exitosamente.</div>");
                } else {
                    //Mantiene los datos del post en la pagina de respuesta para que sean corregidos
                    $channels_data = $new_channels_data;

                    echo ("<div class='error notice is-dismissible' style='padding: 10px'>"
                        . "Error en formato salvando <strong>" . $form_name . "</strong>"
                        . "<br>" . $guardar['error']
                        . "</div>");
                }
            } else {
                echo ("<div class='error notice is-dismissible' style='padding: 10px'>"
                    . "Error de estructura salvando <strong>" . $form_name . "</strong> "
                    . "<br>" . $rta['error']
                    . "</div>");
            }


            break;

        case "salvaropciones2":

            $param_prefix = 'param-info-';
            $config_agentechat_key = $smartsoftbutton_admin->get_config_agentechat_key();
            $new_config_agentechat_info = '';
            $new_config_agentechat_value = array();

            if ($config_agentechat_key) {
                $new_config_agentechat_info = filter_input(INPUT_POST, $param_prefix . $config_agentechat_key);
            }

                /*echo "<pre>";
                echo "<p>" . 'NEW CONFIG AGENTECHAT TEXT' . "</p>";
                echo "<p>Type=" . gettype($new_config_agentechat_info) . "</p>";
                echo "<p>" . print_r($new_config_agentechat_info) . "</p>";
                echo "</pre>";*/

                $new_config_agentechat_value = SmartsoftButton_Utils::convert_json_string_to_array($new_config_agentechat_info);

                /*echo "<pre>";
                echo "<p>" . 'NEW CONFIG AGENTECHAT ARRAY' . "</p>";
                echo "<p>Type=" . gettype($new_config_agentechat_value) . "</p>";
                print_r($new_config_agentechat_value);
                echo "</pre>";*/

            $guardar = $smartsoftbutton_admin->set_config_agentechat_value($new_config_agentechat_info);
            if ($guardar['todo_ok']) {
                //Recarga datos de las opcion guardada
                $config_agentechat_value = $smartsoftbutton_admin->get_config_agentechat_value();
                $config_agentechat_info =  SmartsoftButton_Utils::convert_array_to_json_string($config_agentechat_value);


                echo ("<div class='updated notice is-dismissible' style='padding: 10px'>Opciones para <strong>"
                    . $form_name . "</strong> guardadas exitosamente.</div>");

            } else {
                //Mantiene los datos del post en la pagina de respuesta para que sean corregidos
                $config_agentechat_value = $new_config_agentechat_value;
                $config_agentechat_info = $new_config_agentechat_info;

                echo ("<div class='error notice is-dismissible' style='padding: 10px'>"
                    . "Error en formato salvando <strong>" . $form_name . "</strong>"
                    . "<br>" . $guardar['error']
                    . "</div>");
            }

            break;

        default:
            break;
    }

    //Recarga valores luego de cualquier POST/UPDATE
    //Re-carga Options de los links de la canales
    $channels_data = $smartsoftbutton_admin->get_links_data_channels();

    //Re-carga Otros Options con informacion adicional
    $additional_info = $smartsoftbutton_admin->get_additional_info_values();

    // ***PRINTS DE PRUEBAS: (comentar cuando el plugin esté listo)***
    /*echo "<p>" . "RELOADED OPTIONS" . "</p>";
    echo "<p>" . print_r($smartsoftbutton_admin->get_options_custom()) . "</p>";

    echo "<p>" . "RELOADED OPTIONS VARS" . "</p>";
    echo "<p>" . print_r($channels_data) . "</p>";
    echo "<p>" . print_r($additional_info) . "</p>";*/
}

?>
<?php
/** 
 * -------------------------------------
 * SCRIPTS para componentes visuales
 * -------------------------------------
 */
/**
 * Script para agregar jquery ui layouts a las vistas (accordion, tabs, etc)
 * Notas: 
 * - Es importante que la declaracion los accordions quede antes de la del tabs. 
 * - El parametro "beforeActivate" en los tabs es para actualizar el hash en la url (luego del post)
 * - El parametro "activate" en los tabs es para actualizar el hash en la url al cambiar entre ellos
 */
?>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        
        //Acordeones
        $("#accordion-one").accordion();
        $("#accordion-two").accordion();

        //Agrega tabs
        $("#tabs").tabs({
            beforeActivate: function(event, ui) {
                window.location.hash = ui.newPanel.selector;
            },
            activate: function(event, ui) {
                var scrollTop = $(window).scrollTop(); // save current scroll position
                window.location.hash = ui.newPanel.attr('id'); // add hash to url
                $(window).scrollTop(scrollTop); // keep scroll at current position
            },
        });
        // Checkboxes
        draw_checkboxs_and_select_edition_mode();
    });
</script>

<?php
/** 
 * -------------------------------------
 * RENDERIZAR pagina
 * -------------------------------------
 */

//Seleccionar el tipo de configuracion a usar: Manual o AgenteChat
echo $smartsoftbutton_admin->content_config_selector();
// Renderizar titulo del sub-menu
echo ("<div class='wrap'><h3>Configurar botones</h3></div>");

?>
<h4>Según la configuración seleccionada, puede personalizar las siguientes opciones: </h4>
<?php
// Renderizar contenido de la pagina/sub-smenu
?>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Configurar Manualmente</a></li>
        <li><a href="#tabs-2">Configurar con AgenteChat</a></li>
    </ul>
    <div id="tabs-1">
        <h4> Configure aquí manualmente las opciones para los canales que desea visualizar en el botón de contacto </h4>

        <form method="post" name="settings_form_1" id="settings_form_1" action="admin.php?page=smartsoftbutton_settings#tabs-1">
            <input type='hidden' name='action' value='salvaropciones1'>
            <input type='hidden' name='form-name' value='Configuración Manual'>
            <div id="accordion-one">

                <?php
                foreach ($channels_data as $key => $value) {
                $channel_placeholders = SmartsoftButton_Constants::get_channel_placeholders($key);
                ?>

                    <h3>Botón de <?php echo SmartsoftButton_Constants::get_display_name_channel($key) ?> <?php  echo SmartsoftButton_Constants::$DEGUB_ACTIVE? ", Metodo=" . $method :"" ?> </h3>
                    <!--Campos que se repiten (se usa un accordion para deplegar cada set de datos)  -->
                    <div>

                        <dl>
                            <dt>
                                <label for="label-label">Título del Botón</label>
                            </dt>
                            <dd><input type="text" id="id-label-link-<?php echo $key ?>" placeholder="<?php echo $channel_placeholders['name']?>" size="40" name="param-label-<?php echo $key ?>" value="<?php echo $channels_data[$key]['name']?>" />
                                <br><small>Texto que sale al pasar el mouse sobre el botón de contacto</small><br>
                            </dd>
                        </dl>
                        <dl>
                            <dt>
                                <?php $forma_id_canal = SmartsoftButton_Constants::get_format_id_channel($key) ?>
                                <label for="label-link">Identificador del Canal (<?php echo $forma_id_canal?>)</label>
                            </dt>
                            <dd><input type="text" id="id-link-id-<?php echo $key ?>" placeholder="<?php echo $channel_placeholders['id']?>" size="40" name="param-link-<?php echo $key ?>" value="<?php echo $channels_data[$key]['id'] ?>" />
                                <?php echo $key==SMARTSOFT_BUTTON_WHATSAPP_WEB ? '<br><small>Formato: Extensión País + Número celular (Sin espacios, ni puntos). Ej: 57##########</small><br>':'' ?>
                                <?php echo $key==SMARTSOFT_BUTTON_WHATSAPP_WEB ? '<small>La extensión para Colombia es 57</small><br>':'' ?>
                                <?php echo $key==SMARTSOFT_BUTTON_FB_MESSENGER ? '<br><small>Formato: id de perfil de facebook (Sin espacios). Ej: AgenteChatSmart </small><br>':'' ?>
                            </dd>
                            
                        </dl>
                        <dl>
                            <dt>
                                <label for="label-link">Mensaje inicial de contacto</label>
                            </dt>
                            <dd><input type="text" id="id-message-link-<?php echo $key ?>" placeholder="<?php echo $channel_placeholders['message']?>" size="80" name="param-message-<?php echo $key ?>" value="<?php echo $channels_data[$key]['message'] ?>" />
                            <br><small>Primer mensaje que por defecto envian sus contactos para comunicarse con usted. Ej: Hola, requiero más información</small><br>
                            </dd>
                        </dl>
                        <dl>
                            <dt>
                                <label for="label-display">Desplegar botón de contacto para este canal en el smartsoftbutton?</label>
                            </dt>
                            <dd>
                                <ul>
                                    <li><input type="radio" id="id-desplegar-link-si-<?php echo $key ?>" name="param-display-<?php echo $key ?>" value="1" <?php echo ($channels_data[$key]['display'] == 1 ? "checked='checked'" : ""); ?> />
                                        <label>Si</label>
                                    </li>
                                    <li><input type="radio" id="id-desplegar-link-no-<?php echo $key ?>" name="param-display-<?php echo $key ?>" value="0" <?php echo ($channels_data[$key]['display'] == 0 ? "checked='checked'" : ""); ?> />
                                        <label>No</label>
                                    </li>
                                </ul>
                                <small><strong>Seleccione: 'Si'</strong> para mostrar este canal en el botón de contacto de su sitio web;<br> <strong>'No'</strong> para ocultarlo </small>
                            </dd>
                        </dl>

                    </div>

                <?php
                } //End for each
                ?>


            </div>
            <div id="submit_buttons" style="margin-top: 20px">
                <button type="reset" class="button button-cancel">Limpiar Cambios</button>
                <button type="submit" class="button button-primary">Salvar Cambios</button>
            </div>

        </form>
    </div>

    <div id="tabs-2">
        <h4>  Pegue aquí la configuración generada por AgenteChat </h4>
        
        <div id="accordion-two">

            <!--Otras Opciones editables en el plugin-->

            <h3>Configuración AgenteChat</h3>

            <div>

                <form method="post" name="settings_form_2" id="settings_form_2" action="admin.php?page=smartsoftbutton_settings#tabs-2">
                    <input type='hidden' name='action' value='salvaropciones2'>
                    <input type='hidden' name='form-name' value='Configuración AgenteChat'>

                    <?php
                    $config_agentechat_key = $smartsoftbutton_admin->get_config_agentechat_key() ;
                    $config_agentechat_label = $smartsoftbutton_admin->get_config_agentechat_label();
                    $config_empty = empty($config_agentechat_value)
                    //if configuracion archivo
                    ?>

                        <dl>
                            <dt>
                                <label for="label-info"><?php echo $config_agentechat_label ?></label>
                            </dt>
                            <dd><textarea id="id-configuracion-agentechat" rows="30" cols="70" placeholder="Pegue la configuración dada por agentechat aquí..."
                            name="param-info-<?php echo $config_agentechat_key ?>"><?php if ( !$config_empty ) { echo $config_agentechat_info; } ?></textarea></dd>
                        </dl>

                    <?php
                    //} //End if
                    ?>

                    <div id="submit_buttons" style="margin-top: 20px">
                        <button type="reset" class="button button-cancel">Limpiar Cambios</button>
                        <button type="submit" class="button button-primary">Salvar Cambios</button>
                    </div>
                </form>

            </div>

        

        </div>

    </div>
</div>

<?php
// ***PRINTS DE PRUEBAS: (comentar cuando el plugin esté listo)***
echo "<div>";
/*
echo "<p>" . "END OPTIONS" . "</p>";
echo "<p>" . print_r($smartsoftbutton_admin->get_options_custom()) . "</p>";

echo "<p>" . "END OPTIONS VARS" . "</p>";
echo "<p>" . print_r($channels_data) . "</p>";
echo "<p>" . print_r($additional_info) . "</p>";*/

echo "</div>";
?>

<div>
    <?php echo $smartsoftbutton_admin->content_footer(); ?>
</div>

<?php

