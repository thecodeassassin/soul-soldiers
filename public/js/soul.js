
// Soul-Soldiers javascript
$(function() {

    activateToolTips();

    $('.index-caroussel').slick({
        'autoplay': true,
        'arrows': false,
        'dots': true,
        'mobileFirst': true,
        'pauseOnHover': true,
        'speed': 1500,
        'autoplaySpeed': 4500
    });

    var emailEdit = $('.edit-email');
    emailEdit.click(function() {
        var emailField = $('.email-field'),
            inputField = emailField.find('input'),
            emailInfoField =  $('.email-info-field'),
            emailHiddenField = emailInfoField.find('input');
        emailInfoField.toggleClass('hidden');
        emailField.toggleClass('hidden');
        $('.email-display-field').toggleClass('hidden');
        inputField.toggle();

        if (emailField.is(':visible')) {
            inputField.removeAttr('disabled');
            emailHiddenField.attr('disabled', 'disabled');
        } else {
            inputField.attr('disabled', 'disabled');
            emailHiddenField.removeAttr('disabled');
        }
    });

    bootbox.setDefaults({
        /**
         * @optional String
         * @default: en
         * which locale settings to use to translate the three
         * standard button labels: OK, CONFIRM, CANCEL
         */
        locale: "nl",

        /**
         * @optional Boolean
         * @default: true
         * whether the dialog should be shown immediately
         */
        show: true,

        /**
         * @optional Boolean
         * @default: true
         * whether the dialog should be have a backdrop or not
         */
        backdrop: true,

        /**
         * @optional Boolean
         * @default: true
         * show a close button
         */
        closeButton: true,

        /**
         * @optional Boolean
         * @default: true
         * animate the dialog in and out (not supported in < IE 10)
         */
        animate: true
    });

    $('[role=form]').validate({
        errorElement: 'span',
        errorClass: 'form-error',
        errorPlacement: function(error, element) {
            error.html('')
                .addClass('icon-attention-circle')
                .insertBefore(element);

        },

        invalidHandler: function(event, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                $.each(validator.errorList, function(num, error) {
                    var element = error.element,
                        message = error.message;


                    if ($( document ).width() > 768) {

                        $(element).popover('destroy');
                        $(element).popover({
                            html: true,
                            placement: 'right',
                            trigger: 'manual',
                            content: message,
                            container: 'body'
                        });
                        $(element).popover('show');
                    } else {
                        $(element).popover('hide');
                    }
                });
            } else {
//
            }
        },

        highlight: function (element) {
            $(element).siblings('.form-error').remove();
            $(element).addClass('error');

            if ($( document ).width() > 768) {
                $(element).popover()
            }
        },
        unhighlight: function (element) {

            $(element).removeClass('error');
            $(element).popover('hide');

        },

        rules: {
            password: "required",
            passwordRepeat: {
                equalTo: "#password"
            },
            email: {
                required: true,
                email: true
            }
        }

    });

    $('#registerEvent').click(function(e) {

        e.preventDefault();

        var eventName = $(this).attr('eventName'),
            eventSystemName = $(this).attr('systemName');

        bootbox.confirm("<br /><h3>Weet je zeker dat je je wilt inschrijven voor " + eventName + "? </h3><br /><br /><strong>Door jezelf in te schrijven ga je akkoord met onze <a href=\"/content/rules\" target=\"_blank\" style=\"color: white;text-decoration: underline;\">algemene voorwaarden</a> die op het moment gelden.</strong>", function(result) {
            if (result) {
                window.location = 'register/'+eventSystemName;
            }
        });
    });

    $('form[name=payment]').submit(function() {
       if ($(this).valid()) {
           ajaxLoad(true);
       }

    });

    var offset = 200;
    var duration = 500;
    $(window).scroll(function () {
        if ($(this).scrollTop() > offset) {
            $('.back-to-top').fadeIn(duration);
        } else {
            $('.back-to-top').fadeOut(duration);
        }
    });

    $(window).resize(function () {
        if ($( document ).width() < 768) {
            // disable click on main menu item once the mobile menu pops up
            $('.hasSubMenu').click(function () {
               return false;
            });

        }
    });

    setTimeout(function() {
        // hide alerts after 8 seconds (only success messages)
        $('#messages .alert.alert-success').fadeOut('slow');
    }, 8000);

    if(window.location.hash) {
        $("html, body").animate({scrollTop:0}, '500');
    }


    // preload the loader image
    $('<img/>')[0].src = __loading_img;

});
