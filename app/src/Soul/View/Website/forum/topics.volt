{% if posts is defined %}
    {% for post in posts %}
        <tr data-post-id="{{ post.postId }}" class="topic">
            <td>{{ post.title }}</td>
            <td>{{ post.viewCount }}</td>
            <td>{{ post.postDate|date('d-m-Y H:i:s') }}</td>
            <td>
                {% set lastReply =  post.getLastReply() %}
                {% if lastReply %}
                    {{ lastReply.postDate|date('d-m-Y H:i:s') }}
                {% else %}
                    {{ post.postDate|date('d-m-Y H:i:s') }}
                {% endif %}
            </td>
        </tr>
    {% else %}
    <h4>Geen posts gevonden</h4>
    {% endfor %}
{% endif %}