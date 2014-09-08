<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>{{ post.title }}</h1>
        </div>
        <div class="col-md-4 pull-right">
            <a href="{{ url('forum') }}" class="btn btn-default"><i class="icon-right"></i> Terug naar forum overzicht</a>
            <a href="{{ url('forum/respond/'~post.title) }}" class="btn btn-default"><i class="icon-reply-all"></i>Reageer</a>

        </div>
    </div>
    {% include 'forum/post' with post %}
</div>
