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
            body = postContent.html().trim();

        if (postContent.find('textarea').length == 0) {

            postContent.html('');
            postContent.append($('<textarea class="form-control" />').attr('name', 'postContent').html(body));


            $(this).parent().parent().append($('<input type="button" class="btn btn-primary" value="Opslaan">').click(function() {
                alert('save');
            }));
        }

    });

    $('.editTitle').click(function() {
        var titleObj = $(this).parent().siblings('.postTitle'),
            title = titleObj.html().trim(),
            postId = $(this).attr('data-post-id'),
            me = this,
            editElement = generateEditElement('text', 'postTitle'),
            saveBtn = getActionElement(function() {
                postAjax('/forum/change/title', {'postTitle' : editElement.val(), 'postId' : postId},
                function(data) {
                    console.log(data);
                    title = data;
                    cleanUp();
                },
                function(obj, code, msg) {
                    bootbox.alert(msg);
                });
            }),
            cancelBtn = getActionElement(function() {
                cleanUp();
            }, 'icon-cancel', 'btn-danger'),
            cleanUp = function() {
                editElement.remove();
                titleObj.html(title);
                $(me).show();

                cancelBtn.hide();
                saveBtn.hide();
            };

        if (titleObj.find('textarea').length == 0) {

            $(me).hide();
            titleObj.html('');
            titleObj.append(
                editElement.val(title)
            );


            $(this).parent().parent().append(saveBtn).append(
              cancelBtn
            );
        }

    });
});

/**
 *
 * @param url
 * @param data
 * @param doneFn
 * @param failFn
 */
function postAjax(url, data, doneFn, failFn) {
    ajaxLoad(true);

    $.ajax(url, {
            data: data,
            type: 'post'
        })
        .done(doneFn)
        .fail(failFn)
        .always(function() {
            ajaxLoad(false);
        });
}

/**
 *
 * @param type
 * @param name
 *
 * @returns {*|jQuery}
 */
function generateEditElement(type, name) {
    var objType = type || 'text';

    if (objType == 'text') {
        return $('<input type="text" class="form-control '+name+'" />').attr('name', name);
    }

}


/**
 *
 * @param callback
 * @param type
 * @param btnType
 *
 * @returns {*|jQuery}
 */
function getActionElement(callback, type, btnType) {
    var selectedType = type || 'icon-floppy',
        buttonType = btnType || 'btn-primary';

    return $('<a class="btn '+ buttonType +' action-btn"><i class="'+selectedType+'"></i></a>').click(callback);
}

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