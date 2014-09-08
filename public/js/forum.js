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

    categories.find('a:first').addClass('active');

    $('#topics').find('tr.topic').click(loadPost);

    $('.editPost').click(function() {
        var postContent = $(this).parent().siblings('.postContent'),
            body = postContent.html();

        if (postContent.find('textarea').length == 0) {

            postContent.html('');
            postContent.append($('<textarea class="form-control" />').attr('name', 'postContent').html(body));


            $(this).parent().parent().append($('<input type="button" class="btn btn-primary" value="Opslaan">').click(function() {
                alert('save');
            }));
        }

    });
});

function loadPost() {
    document.location.href = '/forum/read/' + $(this).attr('data-post-id');
}

function loadCategory(id) {
    ajaxLoad(true);

    $.ajax( '/forum/posts/'+id)
        .done(function(data) {

            // load the topics and rebind the clicks
            var topics = $('#topics');
            topics.find('tbody').html(data);
            topics.find('tr.topic').click(loadPost);
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