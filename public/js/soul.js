
// Soul-Soldiers javascript
$(function() {
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
//
//    var nextContainer = $('.container').closest('[col-md-*]')
});