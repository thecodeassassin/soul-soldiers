{% extends 'layout.volt' %}

{% block pageTitle %}<i class="icon-download"></i>Command and Conquer 3{% endblock %}
{% block content %}
{% set image_1 = 'img/downloads/cnc_ingame.jpg' %}
{% set image_2 = 'img/downloads/cnc_ingame2.jpg' %}
{% set image_3 = 'img/downloads/cnc_ingame3.jpg' %}
<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

            <h1>Installatie instructies</h1>
           <br />
                Download de bestanden en installeer deze op plek van eigen keuze.<br />
                Installeer alle bestanden op volgorde, inclusief de patches<br />
                In de map waar je het spel heb geinstalleerd staat de file CNC3EP1.exe, start hiermee het spel op als administrator (Ookal heb je een administrator account). <br />
                In de zelfde map staat ook een key generator, genereer hier een key mee, gebruik deze key in het opties menu om multiplayer werkend te krijgen.<br /><br />
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