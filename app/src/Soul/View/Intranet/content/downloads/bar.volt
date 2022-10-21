{% extends 'layout.volt' %}

{% block pageTitle %}<i class="icon-download"></i>Starcraft 2 - Direct Strike{% endblock %}
{% block content %}
{% set image_1 = 'img/downloads/bar_ingame.jpg' %}
{% set image_2 = 'img/downloads/bar_ingame2.jpg' %}
{% set image_3 = 'img/downloads/bar_ingame3.jpg' %}
<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

            <h1>Installatie instructies</h1>
           <br />
                Download alle bestand en open daarna het eerste (.exe) bestand.<br />
                Installeer hiermee de game naar je gewenste map<br />
                Start het spel op via Beyond-All-Reason.exe en klik op Update .<br />
                Wacht tot dit klaar is en klik op Start.<br />
                Voor dit spel heb je een BAR account nodig, deze kan je aanmaken in het spel op het moment dat het spel is opgestart.<br />
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