{% extends 'layout.volt' %}

{% block pageTitle %}<i class="icon-download"></i>Keep Talking and nobody explodes{% endblock %}
{% block content %}

{% set image_1 = 'img/downloads/keeptalking_ingame.jpg' %}
{% set image_2 = 'img/downloads/keeptalking_ingame2.jpg' %}
{% set image_3 = 'img/downloads/keeptalking_ingame3.jpg' %}

<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

         <h1>Installatie instructies</h1>
         <p><br />
         Download het bestand en installeer deze. <br />
         Start het spel op met ktane.exe <br /> <br />

         Belangrijk !!<br />
         Om dit spel te kunnen spelen heeft speler 2 de manual nodig : http://www.bombmanual.com/ <br />


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