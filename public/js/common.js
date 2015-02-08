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
 *
 * @param msg
 */
function ajaxConfirm(msg){
    var confirmed = confirm(msg);

    if (confirmed) {
        ajaxLoad(true);
    }

    return confirmed;
}

/**
 * ajaxLoader callback for remote modals
 * @returns {boolean}
 */
function ajaxLoadCallback()
{
    ajaxLoad(true);
    return true;
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

$(function() {

    // check submenu's if they have any active menus

    $('.hasSubMenu').siblings('.subMenu').find('li a').each(function() {

       if ($(this).hasClass('active')) {

           var subMenu = $(this).parent().parent();
           subMenu.show();
           subMenu.siblings('.hasSubMenu').addClass('open');
       }
    });

});