$(function (){
    $('#bracket').bracket({
       init: __BRACKET_DATA,
       render: function() {
            //console.log((this).attr('data-bracket'));
       }
    });
});