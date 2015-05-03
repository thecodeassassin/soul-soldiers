$(function (){
    var bracketOptions = {
      init: __BRACKET_DATA
    },
    jqBracket,
    height = 0;

    if (typeof __BRACKET_OPTS != 'undefined') {
        bracketOptions = __BRACKET_OPTS;
    }

    $('#bracket').bracket(bracketOptions);

    $('.team').find('.label').each(function() {
       var originalFunction,
           parentElem = $(this).parent();
       parentElem.attr('title', $(this).html());
       parentElem.tooltip({
           trigger: 'manual'
       });
       parentElem.hover(function() {
           $(this).tooltip('toggle')
       });
    });
    jqBracket = $('.jQBracket');

    if (jqBracket.find('.finals').length > 0) {
        height = jqBracket.find('.finals').height();
    } else {
        height = jqBracket.find('.bracket').height();
    }

    jqBracket.css('min-height', height);
});