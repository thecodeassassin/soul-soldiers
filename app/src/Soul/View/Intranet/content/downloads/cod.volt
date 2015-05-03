{% extends 'layout.volt' %}

{% block pageTitle %}<i class="icon-download"></i>&nbsp;Call Of Duty - Modern Warfare {% endblock %}
{% block content %}

{% set image_1 = 'img/downloads/cod_ingame.jpg' %}
{% set image_2 = 'img/downloads/cod_ingame2.jpg' %}
{% set image_3 = 'img/downloads/cod_ingame3.jpg' %}

<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

         <h1>Installatie instructies</h1>
         <p><br />
         Download de 3 bestanden die op de download pagina te vinden zijn.<br />
         Pak de bestanden uit ( met behulp van de gedownloade exe file) naar een tijdelijke locatie . <br />
         In de map waar je de bestanden heb uitgepakt staan 5 mappen.<br /><br />

         Ga naar de eerste map en open het bestand setup.exe<br>
         Hiermee wordt de installatie van het spel gestart.<br>
         Tijdens de installatie wordt er naar een sleutel gevraagd.<br>
         De sleutel staat in de 2de map.<br>
         Als het spel geïnstalleerd is start je het bestand uit de 3de map.<br>
         Als de bovenstaande klaar is start je het bestand uit de 4de map.<br>
         Als laatste kopieer je de bestanden uit de 5de map en zet je die in de map waar je het spel heb geïnstalleerd.<br>

        <br><br>
        met het bestand iw3mp.exe kan je het spel opstarten <br>


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