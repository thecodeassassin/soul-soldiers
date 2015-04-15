$(function (){
    $('#bracket').bracket({
       save:
       /* Called whenever bracket is modified
        *
        * data:     changed bracket object in format given to init
        * userData: optional data given when bracket is created.
        */
           function saveFn(data, userData) {
               var json = jQuery.toJSON(data);
               //$.post('')
               console.log(json);
       },
       init: __BRACKET_DATA,
       render: function() {
            //console.log((this).attr('data-bracket'));
       }
    });
});