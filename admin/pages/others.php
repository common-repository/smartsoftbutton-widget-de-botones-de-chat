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
echo "<p>" . print_r($additional_info) . "</p>";
*/

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

        case "salvaropciones3":

            $param_prefix = 'param-info-';
            $additional_info_keys = $smartsoftbutton_admin->get_additional_info_keys();
            $new_additional_info = array();

            foreach ($additional_info_keys as $key_info) {
                $new_additional_info[$key_info] = filter_input(INPUT_POST, $param_prefix . $key_info);
            }

            /*
                echo "<p>" . 'NEW ADDITIONAL INFO' . "</p>";
                echo "<p>" . print_r($new_additional_info) . "</p>";*/

            $guardar = $smartsoftbutton_admin->set_additional_info($new_additional_info);
            if ($guardar['todo_ok']) {
                //Recarga datos de las opciones guardadas
                $additional_info = $smartsoftbutton_admin->get_additional_info_values();

                echo ("<div class='updated notice is-dismissible' style='padding: 10px'>Opciones para <strong>"
                    . $form_name . "</strong> guardadas exitosamente.</div>");
            } else {
                //Mantiene los datos del post en la pagina de respuesta para que sean corregidos
                $additional_info = $new_additional_info;

                echo ("<div class='error notice is-dismissible' style='padding: 10px'>"
                    . "Error en formato salvando <strong>" . $form_name . "</strong>"
                    . "<br>" . $guardar['error']
                    . "</div>");
            }

            break;
        case "salvaropciones100":
            $smartsoftbutton_admin->reset_to_default_values();

            echo ("<div class='updated notice is-dismissible' style='padding: 10px'>La acción de <strong>"
                . $form_name . "</strong> se ejecuto con exito. <br>Las opciones han sido restablecidas a su valor por defecto.</div>");
            break;

        default:
            break;
    }

    //Re-carga Otros Options con informacion adicional
    $additional_info = $smartsoftbutton_admin->get_additional_info_values();

    // ***PRINTS DE PRUEBAS: (comentar cuando el plugin esté listo)***
    /*echo "<p>" . "RELOADED OPTIONS" . "</p>";
    echo "<p>" . print_r($smartsoftbutton_admin->get_options_custom()) . "</p>";

    echo "<p>" . "RELOADED OPTIONS VARS" . "</p>";
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
        $("#accordion-three-one").accordion();
        $("#accordion-three-two").accordion();
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
    });
</script>

<?php
/** 
 * -------------------------------------
 * RENDERIZAR pagina
 * -------------------------------------
 */
// Renderizar titulo del sub-menu
echo ("<div class='wrap'><h3>Otras Opciones</h3></div>");
?>

<?php
// Renderizar contenido de la pagina/sub-smenu
?>
<div id="tabs">
    <ul>
        <li><a href="#tabs-3-1">Otras Opciones</a></li>
        <li><a href="#tabs-3-2">Reiniciar Valores</a></li>
    </ul>

    <div id="tabs-3-1">
        <h4>  Configure aquí opciones adicionales para el botón de contacto </h4>
        
        <div id="accordion-three-one">

            <!--Otras Opciones editables en el plugin-->

            <h3> Información Adicional </h3>

            <div>

                <form method="post" name="settings_form_3" id="settings_form_3" action="admin.php?page=smartsoftbutton_others#tabs-3-1">
                    <input type='hidden' name='action' value='salvaropciones3'>
                    <input type='hidden' name='form-name' value='Información Adicional'>

                    <?php
                    $addition_info_labels = $smartsoftbutton_admin->get_additional_info_labels();

                    foreach ($additional_info as $key => $value) {
                    ?>

                        <dl>
                            <dt>
                                <label for="label-info"><?php echo $addition_info_labels[$key] ?></label>
                            </dt>
                            <dd><input type="text" id="id-link-id" size="80" name="param-info-<?php echo $key ?>" value="<?php echo $value ?>" /></dd>
                        </dl>

                    <?php
                    } //End for each
                    ?>

                    <div id="submit_buttons" style="margin-top: 20px">
                        <button type="reset" class="button button-cancel">Limpiar Cambios</button>
                        <button type="submit" class="button button-primary">Salvar Cambios</button>
                    </div>
                </form>

            </div>

        </div>

    </div>
    <div id="tabs-3-2">
        <h4>  Reinicie las opciones a su valor por defecto para el botón de contacto </h4>
        
        <div id="accordion-three-two">

            <!--Restablecer valores de las opciones a los valores por defecto-->

            <h3> Restablecer valores </h3>

            <div>

                <form method="post" name="settings_form_100" id="settings_form_100" action="admin.php?page=smartsoftbutton_others#tabs-3-2">
                    <input type='hidden' name='action' value='salvaropciones100'>
                    <input type='hidden' name='form-name' value='Restablecer datos por defecto'>

                    <dl>
                        <dt>
                            <label for="label-restablecer">Restablecer todas las opciones a los valores por defecto?</label>
                        </dt>
                        <dd>
                            <br>Esta opción restablece los valores por defecto <strong> para todas las opciones de configuracion, </strong>
                            <br>Se restableceran los valores para links de canales, y los campos de información adicional en el widget.

                            <br><br>Al restablecer la información todos los valores quedaran como eran al momento de instalación.
                            <br>Si desea continuar oprima el siguiente boton.
                        </dd>
                    </dl>

                    <div id="submit_buttons" style="margin-top: 20px">
                        <button type="submit" class="button button-primary">Restablecer Valores</button>
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
echo "<p>" . print_r($additional_info) . "</p>";*/

echo "</div>";
?>

<div>
    <?php echo $smartsoftbutton_admin->content_footer(); ?>
</div>