{% extends 'layout.volt' %}

{% block pageTitle %}<i class="icon-download"></i>Starcraft 2 - Direct Strike{% endblock %}
{% block content %}
{% set image_1 = 'img/downloads/sc2_ingame.jpg' %}
{% set image_2 = 'img/downloads/sc2_ingame2.jpg' %}
{% set image_3 = 'img/downloads/sc2_ingame3.jpg' %}
<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

            <h1>Installatie instructies</h1>
           <br />
                Download het bestand en installeer deze.<br />
                Dit bestand installeerd Battle.net , het programma waarmee Blizzard haar spellen mee beheerd.<br />
                Log hier in met je Blizzard account (of maak via de instructies van Blizzard een account aan).<br />
                Als je bent ingelogd kan je vanuit hier Starcraft 2 installeren via de menu opties.<br />
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