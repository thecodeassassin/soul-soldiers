{{ partial('event/' ~ event.systemName) }}
<div class="row color0">
    <div class="col-md-5 col-md-offset-1 pb15 pt15">
        <h2>Inschrijvingen ({{ event.entries|length }}/{{ event.maxEntries }})</h2>

        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Nickname</th>
                    <th>Betaald</th>
                </tr>
            </thead>
            <tbody>
        {% for entry in event.entries %}
            <tr>
                <td>{{ entry.user.realName}}</td>
                <td>{{ entry.user.nickName}}</td>
                <td>
                    {% if entry.payment and entry.payment.confirmed %}
                    <span class="label label-success">Ja</span>
                    {% else %}
                    <span class="label label-warning">Nee</span>
                    {% endif %}
                </td>
            </tr>
        {% else %}
            <tr>
                <td colspan="3">Er zijn nog geen inschrijvingen voor dit evenement</td>
            </tr>
        {% endfor %}
            </tbody>
        </table>
    </div>

    <div class="col-md-4 col-md-offset-1 pb15 pt15">
        <h2>Media</h2>
        <div class="imgHover clearfix portfolioMosaic mosaic5">
            {% for img in media['images'] %}
            <article>
                <figure class="minimalBox">
                    <a href="{{ url(img) }}" class="image-link">
                        {{ image(img, "class" : "img-responsive") }}
                    </a>
                </figure>
            </article>
            {% else %}
            <span>Er is nog geen media voor dit evenement beschikbaar</span>
            {% endfor %}

        </div>

    </div>
</div>