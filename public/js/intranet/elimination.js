$(function () {
    var bracketOptions = {
        init: __BRACKET_DATA,
        //skipSecondaryFinal: true
    },
        jqBracket,
        height = 0;

    if (typeof __BRACKET_OPTS != 'undefined') {
        bracketOptions = $.extend({}, bracketOptions, __BRACKET_OPTS);
    }

    $('#bracket').bracket(bracketOptions)

    $('.team').find('.label').each(function () {
        var originalFunction,
            parentElem = $(this).parent(),
            title = $(this).html();
        parentElem.attr('title', title);

        if (title != '--' && title != __BYE_STR) {
            parentElem.tooltip({
                trigger: 'manual'
            });
            parentElem.hover(function () {
                $(this).tooltip('toggle')
            });
        }
    });

    var heightCheck = setInterval(function() {
        jqBracket = $('.jQBracket');

        if (jqBracket.find('.finals').length > 0) {
            height = jqBracket.find('.finals').height();
        } else {
            height = jqBracket.find('.bracket').height();
        }

        jqBracket.css('min-height', height);

        if (height > 0) {
            clearInterval(heightCheck)
        }
    }, 1000);
});