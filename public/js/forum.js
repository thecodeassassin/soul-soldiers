$(function() {
    // forum
    var categories = $('#forum-categories');
    categories.find('a').each(function(index, el) {
        var me = this;
        $(me).click(function() {

            // add the proper class and load the category
            if (!$(me).hasClass('active')) {

                $(me).siblings().removeClass('active');
                $(me).addClass('active');
                loadCategory($(me).attr('data-category-id'));
            }
        });
    });

    categories.find('a:first').click();

});

function loadCategory(id) {
    ajaxLoad(true);


    $.ajax( '/forum/posts/'+id)
        .done(function(data) {
            console.log(data);
        })
        .fail(function(req) {

            if (req.status == 401) {
                bootbox.alert('Geen rechten om deze posts te laden!');
            } else {
                bootbox.alert('Kan posts helaas niet laden...');
            }

        })
        .always(function() {
            ajaxLoad(false);
        });

}