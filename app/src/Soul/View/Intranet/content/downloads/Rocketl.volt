{% extends 'layout.volt' %}

{% block pageTitle %}<i class="icon-download"></i>&nbsp;Rocket League{% endblock %}
{% block content %}
{% set image_1 = 'img/downloads/rl_ingame.jpg' %}
{% set image_2 = 'img/downloads/rl_ingame2.jpg' %}
{% set image_3 = 'img/downloads/rl_ingame3.jpg' %}

<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

         <h1>Installatie instructies</h1>
         <p><br />
         Download en installeer het spel <br />
         Start het spel op met behulp van het bestand RocketLeague.exe, deze is te vinden in \binaries\win32<br /><br />

         BELANGRIJK !!<br />
         Voor dit spel is Steam met een steam account nodig ( steam is te downloaden op www.steampowered.com )<br />
         Aangezien dit spel gebruikt maakt van officiele servers is aan te raden om een los/nieuw steamaccount voor dit spel aan te maken<br />


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