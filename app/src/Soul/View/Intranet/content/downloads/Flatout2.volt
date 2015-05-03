{% extends 'layout.volt' %}

{% block pageTitle %}<i class="icon-download"></i>&nbsp;Flatout 2{% endblock %}
{% block content %}
{% set image_1 = 'img/downloads/flatout_ingame.jpg' %}
{% set image_2 = 'img/downloads/flatout_ingame2.jpg' %}
{% set image_3 = 'img/downloads/flatout_ingame3.jpg' %}
<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

            <h1>Installatie instructies</h1>
            <p>Download de file en installeer deze naar de plek naar keuze<br />
                In de Flatout 2 map staat het bestand Flatout2.exe waarmee het spel gespeeld kan worden</p>


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