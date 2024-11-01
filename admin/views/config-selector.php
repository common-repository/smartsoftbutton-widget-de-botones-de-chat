<?php

/**
 * @package SmartsoftButton\Admin
 * Template para incluir un admin-header, para las paginas de administracion del plugin
 */

global $smartsoftbutton_plugin_name;
// Instancia de la clase que maneja la logica (hereda de SmartsoftButton_Options)
global $smartsoftbutton_admin;

$active_config = $smartsoftbutton_admin->get_active_configuration();

// ***PRINTS DE PRUEBAS: (comentar cuando el plugin esté listo)***
/*echo "<p>HELLO I am the page config-selector.php</p>";
echo "<p>$active_config</p>";*/

?>
<div class='wrap'>
    <h3><?php echo "Configuración Manual o con AgenteChat" ?></h3>
    <div>
        <h4>Seleccione cómo desea utilizar las opciones del botón, Manualmente o integrado con AgenteChat</h4>
        <small><strong>**Manualmente: </strong>Permite configurar cada una de las opciones del botón manualmente, por cada canal.</small><br>
        <small><strong>**Con AgenteChat: </strong>Permite pegar su configuración de botón desde AgenteChat.</small><br><br>

        <fieldset>
            <div id="radioset">
                <legend>Seleccione una opción: </legend>
                <label for="radio-1-manualmente" title="Permite configurar cada una de las opciones del botón manualmente, por cada canal">Configurar Manualmente</label>
                <input type="radio" name="radio-1-seleccionar-configuracion" id="radio-1-manualmente" value="<?php echo SMARTSOFTBUTTON_CONFIGURACION_MANUAL ?>">
                <label for="radio-2-agentechat" title="Permite pegar su configuración de botón desde AgenteChat">Configurar con AgenteChat</label>
                <input type="radio" name="radio-1-seleccionar-configuracion" id="radio-2-agentechat" value="<?php echo SMARTSOFTBUTTON_CONFIGURACION_JSON_AGENTECHAT ?>">
            </div>
        </fieldset>
    </div>
</div>
<div class='wrap'>  
    <!--<button id="my-button">Guardar Selección</button>-->
    <div id="#div-loading" style="display:none;"><p>Guardando...</p></div>
    <div class='notice notice-info' id="#div-selection-message"><p id="p-selection-message">Configuración activa: <?php echo SmartsoftButton_Constants::get_display_name_configuration($active_config) ?> </p></div>
</div>
<script type="text/javascript">
    function draw_checkboxs_and_select_edition_mode() {
        jQuery(function($) {
            $("#radio-1-manualmente").checkboxradio();
            $("#radio-2-agentechat").checkboxradio();

            <?php if ($active_config == SMARTSOFTBUTTON_CONFIGURACION_JSON_AGENTECHAT) { ?>
                $("#radio-2-agentechat").prop('checked', true);
                $("#radio-2-agentechat").checkboxradio("refresh");
            <?php } else { ?>
                $("#radio-1-manualmente").prop('checked', true);
                $("#radio-1-manualmente").checkboxradio("refresh");
            <?php } ?>

            update_on_configuration_select();
        });
    }

    function update_on_configuration_select() {
        jQuery(function($) {
            $('input[type=radio][name=radio-1-seleccionar-configuracion]').change(function() {
                if (this.value == '<?php echo SMARTSOFTBUTTON_CONFIGURACION_MANUAL ?>') {
                    //write your logic here
                    console.log("Seleccionada configuracion manual");
                    $("#tabs").tabs({
                        active: 0
                    });

                } else if (this.value == '<?php echo SMARTSOFTBUTTON_CONFIGURACION_JSON_AGENTECHAT ?>') {
                    //write your logic here
                    console.log("Seleccionada configuracion agentechat");
                    $("#tabs").tabs({
                        active: 1
                    });
                }
                save_configuration_mode($, this.value );
            });
        });
    }
</script>
<hr>