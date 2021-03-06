{% extends 'layout.volt' %}
{% block pageTitle %}<i class="icon-download"></i>Golden Eye : Source{% endblock %}
{% block content %}
{% set image_1 = 'img/downloads/eye_ingame.jpg' %}
{% set image_2 = 'img/downloads/eye_ingame2.jpg' %}
{% set image_3 = 'img/downloads/eye_ingame3.jpg' %}
<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

            <h1>Installatie instructies</h1>
           <br />
                Indien je je nog geen steam geïnstalleerd heb staan installeeer je deze met behulp van de eerste link.<br />
                Vervolgens download je de 2de link ( SDK 2007 ) .<br />
                Als laatste installeer je bestand 3 ( Golden Eye : Source ). <br />
                Start het spel op via de snelkoppelig op je bureaublad.<br /><br />
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