{% extends 'layout.volt' %}
{% block pageTitle %}<i class="icon-download"></i>Counter Strike : GO{% endblock %}
{% block content %}
{% set image_1 = 'img/downloads/cs_ingame.jpg' %}
{% set image_2 = 'img/downloads/cs_ingame2.jpg' %}
{% set image_3 = 'img/downloads/cs_ingame3.jpg' %}
<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

            <h1>Installatie instructies</h1>
           <br />
                Download de bestanden en installeer deze op plek van eigen keuze.<br />
                In de map waar je het spel heb geinstalleerd staat de file csgo.exe, start hiermee het spel op. <br />
                In dezelfde map staat ook een file genaamd rev.ini. als je dit bestand opend met kladblok kan je je naam aanpassen<br />
                Het spel in NIET compatible met de huidige steam versie, om mee te kunnen doen met de competities dien je deze versie te downloaden<br /><br />
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