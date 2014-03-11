
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
        highlight: function (element) {
            $(element).siblings('.form-error').remove();
            $(element).addClass('error');
        },
        unhighlight: function (element) {
            $(element).siblings('.form-error').remove();
            $(element).removeClass('error');
        }

    });
});