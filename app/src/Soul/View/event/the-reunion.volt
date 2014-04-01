<div class="jumbotron color0">
    <div class="container">
        <h1>{{ event.name }}</h1>

        <p>
            Soul-Soldiers: The Reunion was de eerste lan-party van Soul-Soldiers die gehouden werd in De Stip. <br />
            De nieuwe locatie werd meteen op de proef gesteld en de mensen waren razend enthousiast over deze nieuwe locatie.
            <br /><br />
            Het was een gezellige lan die onze verwachtingen te boven ging. Bedankt aan alle deelnemers en tot de volgende keer!
        </p>
    </div>
</div>
<div class="row color0">
    <div class="{% if archived %} col-md-6 col-md-offset-3 {% else %}col-md-4 col-md-offset-1 {% endif %} pb15 pt15">
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