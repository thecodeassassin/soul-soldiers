{% set pageTitle = '<i class="icon-download"></i>&nbsp;Freespace 2' %}
{% set image_1 = 'img/downloads/fs2_ingame.jpg' %}
{% set image_2 = 'img/downloads/fs2_ingame2.jpg' %}
{% set image_3 = 'img/downloads/fs2_ingame3.jpg' %}
<div class="row">

    <div class="col-md-12">
        <div class="gutter well">


            <h1>Installatie instructies</h1>
            <p>Download de file en installeer deze naar de plek naar keuze<br />
            In de Freespace 2 map staat het bestand Freespace.bat waarmee het spel gespeeld kan worden<br />
            <br />
            De eerste keer dat het bestand geopend wordt dient deze nog ingestelt te worden op de onderstaande mannier :<br />
            Ga naar Basic Settings <br />
            Selecteer hier de installatie folder ( standaard zal dit C:\Games\Freespace2\Freespace2 zijn )<br />
            Als je de folder heb geselecteerd komen er meerdere opties beschikbaar om in te stellen<br />
            De 'network' instellingen dienen beide (type en speed) op LAN te staan <br />
            Als dit gedaan is kan je het spel starten door middel van de play knop rechtsonderin <br />


            </p><p>
            <br /> Mocht het spel niet starten dien je het bestand oalinst.exe (staat in de installatie folder) te installeren en het vervolgens opnieuw te proberen <br />
            <br /></p>


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
