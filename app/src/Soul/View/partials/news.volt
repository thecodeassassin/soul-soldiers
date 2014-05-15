<div class="news-admin-wrapper">
    {% if user.isAdmin() %}
        {{ partial('../partials/newsAdmin') }}
    {% endif %}
</div>

<div class="news-wrapper">
    {% for item in news %}

        {% if user.isAdmin() %}
            {{ form('news/edit', "method": "post", "name":"editItem", "class":"validate", "role":"form") }}
        {% endif %}
        <div class="panel">
            <div class="panel-heading">

                {% if user.isAdmin() %}
                    <input type="text" value="{{ item.title }}" name="title" style="width: 500px;"/>
                {% else %}
                    {{ item.title }}
                {% endif %}


                <div class="pull-right"> {{ item.published }}</div></div>
            <div class="panel-body">

                {% if user.isAdmin() %}
                    <textarea class="form-control" name="content" class="news-content" data-news-id="{{ item.newsId }}">{{ item.body }}</textarea>
                    <input type="hidden" name="newsId" value="{{ item.newsId }}" />
                    <button type="submit" class="btn btn-primary">Aanpassen</button>
                    <a href="{{ url('/news/delete/' ~ item.newsId) }}">
                        <button type="button" class="btn btn-danger" onclick="return confirm('Zeker weten?')"><i class="icon-trash"></i> Verwijderen</button>
                    </a>

                {% else %}
                    {{ item.body }}
                {% endif %}
            </div>
        </div>

        {% if user.isAdmin() %}
            {{ endform() }}
        {% endif %}

    {% else %}
        <h4>Geen niews</h4>
    {% endfor %}
</div>