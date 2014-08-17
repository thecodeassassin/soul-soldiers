{% if posts is defined %}
    {% for post in posts %}
        <tr>
            <td>{{ post.title }}</td>
            <td>{{ post.viewCount }}</td>
            <td>{{ post.postDate|date('d-m-Y H:i:s') }}</td>
        </tr>
    {% else %}
    <h4>Geen posts gevonden</h4>
    {% endfor %}
{% endif %}