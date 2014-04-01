
// Soul-Soldiers javascript
$(function() {

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

                    $(element).popover('destroy');
                    $(element).popover({
                        html: true,
                        placement: 'right',
                        trigger: 'manual',
                        content: message,
                        container: 'body'
                    });
                    $(element).popover('show');

                });
            } else {
//
            }
        },

        highlight: function (element) {
            $(element).siblings('.form-error').remove();
            $(element).addClass('error');

            $(element).popover()
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

        bootbox.confirm("Weet je zeker dat je je wilt inschrijven voor " + eventName + "?", function(result) {
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

});

function ajaxLoad(mode){

    if(mode) {
        $.blockUI({ message: '<div class="ajax-loader center"><img src="/img/ajax-loader.gif" /> <br />Bezig met laden...&nbsp;</div>' });
    } else {
        $.unblockUI();
    }

}
