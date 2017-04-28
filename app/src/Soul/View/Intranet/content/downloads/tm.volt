{% extends 'layout.volt' %}

{% block pageTitle %}<i class="icon-download"></i>Keep Talking and nobody explodes{% endblock %}
{% block content %}

{% set image_1 = 'img/downloads/tm_ingame.jpg' %}
{% set image_2 = 'img/downloads/tm_ingame2.jpg' %}
{% set image_3 = 'img/downloads/tm_ingame3.jpg' %}

<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

         <h1>Installatie instructies</h1>
         <p><br />
         Download het bestand en installeer deze. <br />
         Start het spel op met het .exe bestand <br /> <br />

        Ook is het mogelijk om de Steam versie te gebruiken <br />

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