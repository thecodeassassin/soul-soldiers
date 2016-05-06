{% extends 'layout.volt' %}

{% block pageTitle %}<i class="icon-download"></i>&nbsp;Duck Game{% endblock %}
{% block content %}
{% set image_1 = 'img/downloads/duck_ingame.jpg' %}
{% set image_2 = 'img/downloads/duck_ingame2.jpg' %}
{% set image_3 = 'img/downloads/duck_ingame3.jpg' %}
<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

            <h1>Installatie instructies</h1>
            <p>Download de file en installeer deze naar de plek naar keuze<br />
                Voeg het spel toe in steam als een non steam spel en start het via steam op.<br /><br />
                mocht het spel niet starten dien je XNA te installeren ( staat in de _commonredist map )
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