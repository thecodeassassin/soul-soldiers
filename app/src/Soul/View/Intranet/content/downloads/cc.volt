{% extends 'layout.volt' %}

{% block pageTitle %}<i class="icon-download"></i>&nbsp;Red Alert 2 : Yuri's revenge{% endblock %}
{% block content %}
{% set image_1 = 'img/downloads/cc_ingame.jpg' %}
{% set image_2 = 'img/downloads/cc_ingame2.jpg' %}
{% set image_3 = 'img/downloads/cc_ingame3.jpg' %}

<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

         <h1>Installatie instructies</h1>
         <p><br />
         Download het bestand en installeer deze naar de gewenste locatie<br />
         Start het spel op met RA2MD.exe die je kan vinden in de RA2 folder
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