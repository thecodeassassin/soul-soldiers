{% extends 'layout.volt' %}

{% block pageTitle %}<i class="icon-download"></i>&nbsp;Counter Strike GO {% endblock %}
{% block content %}

    {% set image_1 = 'img/downloads/csg_ingame.jpg' %}
    {% set image_2 = 'img/downloads/csg_ingame2.jpg' %}
    {% set image_3 = 'img/downloads/csg_ingame3.jpg' %}

    <div class="row">

        <div class="col-md-12">
            <div class="gutter well">

                <h1>Installatie instructies</h1>
                <p><br />
                    Download de 3 bestanden die op de download pagina te vinden zijn.<br />
                    Pak de bestanden uit ( met behulp van de gedownloade exe file). <br /><br />
                    BELANGRIJK : in de installatiemap staat een bestandje rev.ini.<br />
                    Open deze en zoek naar het stukje waar PlayerName= staat <br />
                    Hier kan je je eigen naam invullen, ingame is dit niet mogelijk.<br />


                    <br><br>
                    met het bestand CSGO soul soldiers kan je het spel opstarten <br>


                </p>

                <H1>Screenshots</H1>
                <p>Klik op de plaatjes om ze in het groot te bekijken</p>


                <a href="{{ url(image_1) }}" class="image-link">
                    {{ image(image_1 ,'height': 250, 'width': 250)}}
                </a>

                <a href="{{ url(image_2) }}" class="image-link">
                    {{ image(image_2 ,'height': 250, 'width': 250)}}
                </a>

                <a href="{{ url(image_3) }}" class="image-link">
                    {{ image(image_3 ,'height': 250, 'width': 250)}}
                </a>


            </div>
        </div>
    </div>
{% endblock %}