<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="postTitle">
              {{ post.title }}
            </div>
            {% if post.user.userId == user.userId or isAdmin %}
            <span class="editIcon">
                <a href="javascript:void(0)" class="editTitle" data-post-id="{{ post.postId }}"><i class="icon-pencil-squared"></i> </a>
            </span>
            {% endif %}

        </div>
        <div class="col-md-4 pull-right">
            <a href="{{ url('forum') }}" class="btn btn-default"><i class="icon-right"></i> Terug naar forum overzicht</a>
            <a href="{{ url('forum/respond/'~post.title) }}" class="btn btn-default"><i class="icon-reply-all"></i>Reageer</a>

        </div>
    </div>
    {% include 'forum/post' with post %}
</div>
