{% set admin = user is defined and user.isAdmin()|default(false) %}

<div class="news-admin-wrapper">
    {% if admin and editMode %}
        <a href="{{ router.getRewriteUri() }}" class="btn btn-primary">Nieuws bekijken</a>
        {{ partial('../partials/newsAdmin') }}
    {% elseif admin and not editMode %}
        <a href="{{ router.getRewriteUri() ~ '?editMode' }}" class="btn btn-primary">Nieuws aanpassen</a>
    {% endif %}
</div>

<div class="news-wrapper">
    {% for item in news %}

        {% if admin and editMode %}
            {{ form('news/edit', "method": "post", "name":"editItem") }}
        {% endif %}
        <div class="panel news">
            <div class="panel-heading {% if editMode %}edit{% endif %}">

                {% if admin and editMode %}
                    <input type="text" class="form-control" value="{{ item.title }}" name="title" style="width: 500px;"/>
                {% else %}
                    <span class="news-title">{{ item.title }}</span>
                {% endif %}


                <div class="pull-right" class="news-date"> {{ item.published }}</div></div>
            <div class="panel-body news-body">

                {% if admin and editMode %}
                    <textarea class="form-control" name="content" class="news-content" data-news-id="{{ item.newsId }}">{{ item.body }}</textarea>
                    <input type="hidden" name="newsId" value="{{ item.newsId }}" />
                    <button type="submit" class="btn btn-primary"><i class="icon-edit"></i></button>
                    <a href="{{ url('/news/delete/' ~ item.newsId) }}">
                        <button type="button" class="btn btn-danger" onclick="return confirm('Zeker weten?')"><i class="icon-trash"></i></button>
                    </a>

                {% else %}
                    {{ item.body }}
                {% endif %}
            </div>
        </div>

        {% if admin and editMode %}
            {{ endform() }}
        {% endif %}

    {% else %}
        <h4>Geen nieuws</h4>
    {% endfor %}
</div>