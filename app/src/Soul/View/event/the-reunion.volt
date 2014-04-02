<div class="jumbotron color0">
    <div class="container">
        <h1>Soul-Soldiers: The Reunion</h1>

        <p>
            Soul-Soldiers: The Reunion op 26, 27 en 28 oktober 2013 was de eerste LAN-party van Soul-Soldiers die gehouden werd in De Stip te Nieuw-Vennep. <br />
            De nieuwe locatie werd meteen op de proef gesteld en de mensen waren razend enthousiast over deze nieuwe locatie!
            <br /><br />
            Het was een gezellige LAN die onze verwachtingen overtroffen heeft! Dit is mede dankzij alle deelnemers geweest en we kijken uit naar de volgende keer!
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