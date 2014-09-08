<div class="row">
    <div class="col-md-4 pb15 pt15">
        <div class="gutter color0 postbox">
            <img src="{{ gravatar_url(post.user.email) }}" class="pull-left"/>
            <div class="post-right pull-left">
                <dl>
                    <dd>{{ post.user.nickName }}</dd>
                    <dd>{{ user.nickName }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-8 pb15 pt15">
        <div class="gutter color0 postbox">
            {% if post.user.userId == user.userId %}
                <div class="editbox">
                    <a href="javascript:void(0)" class="editPost" data-post-id="{{ post.postId }}"><i class="icon-pencil-squared"></i> </a>
                    <a href="{{ url('forum/delete/' ~ post.postId) }}" onclick="return confirm('Weet je het zeker dat je deze post wilt verwijderen?')" id="editPost"><i class="icon-trash"></i> </a>
                </div>
            {% endif %}

            <div class="postContent">
                {{ post.body|striptags('<p><a><b><strong><ul><li>') }}
            </div>
        </div>
    </div>
</div>