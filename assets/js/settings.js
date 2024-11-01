// Reusable ACTIONS

// Function to save configuration_mode
function save_configuration_mode($, n_configuration_mode) {
    //La llamada AJAX
    $.ajax({
        beforeSend: function (qXHR, settings) {
            $('#div-loading').fadeIn();
            //console.log("Por enviar");
        },
        complete: function () {
            $('#div-loading').fadeOut(1000);
            //console.log("Teminado");
        },
        type: "post",
        url: ajax_settings_vars.url,
        data: {
            ajax: 1,
            url: ajax_settings_vars.url,
            action: ajax_settings_vars.action,
            nonce: ajax_settings_vars.nonce,
            message: "Seleccionar modo de configuración",
            configuration_mode: n_configuration_mode
        },
        error: function (response) {
            console.log(response);
        },
        success: function (response) {
            // Actualiza el mensaje con la respuesta
            //console.log("Exito");
            //console.log(response);
            $('#p-selection-message').text(response.message);
        }
    })
}

// EVENTS
// Save by click configuration mode
jQuery(document).ready(function ($) {
    $("#my-button").on('click', function () {
        console.log("My button cliqueado");

        let selected_mode = $("input[type=radio][name=radio-1-seleccionar-configuracion]:checked").val();
        save_configuration_mode($, selected_mode );

    });
});