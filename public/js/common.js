


function ajaxLoad(mode){

    if(mode) {
        $.blockUI({ message: '<div class="ajax-loader center"><img src="'+__loading_img+'" /> <br />Bezig met laden...&nbsp;</div>' });
    } else {
        $.unblockUI();
    }

}

/**
 * Activate tooltips
 */
function activateToolTips(){
    $("[data-toggle='tooltip']").tooltip();
}

/**
 * Reserve a seat confirmation message
 * @returns {*}
 */
function reserveSeat() {
    var confirmed = confirm('Weet u zeker dat u deze plek wilt reserveren?');

    if (confirmed) {
        ajaxLoad(true);
    }
    return confirmed;
}
