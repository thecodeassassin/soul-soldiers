{% set pageTitle = '<i class="icon-download"></i>&nbsp;DC++' %}
{% set image_1 = 'img/downloads/dc_install.jpg' %}
{% set image_2 = 'img/downloads/dc_install2.jpg' %}
{% set image_3 = 'img/downloads/dc_install3.jpg' %}

<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

         <h1>Installatie instructies</h1>
         <p><br />
         Download het bestand en installeer deze naar een plek van keuze.<br />
         Start het programma op via dcplusplus.exe<br />
         Als het programma is opgestart zal deze vragen of je hem wilt updaten, kies hier voor nee.<br />
         Het programma zal de eerste keer opstarten naar het instellingen menu (settings)<br />
         Mocht dit niet gebeuren dien je naar 'file' 'settings' te gaan.<br />
         Eenmaal hier ( zie Afbeelding 1 ) dien je je gebruikersnaam (A) en IP adres (B) op te geven.<br />
         Indien je niet weet hoe je achter je ip adres kan komen kan je dit aan 1 van de crewleden vragen.<br />
         Bij downloads (C) kan je instellen waar je gedownloade bestanden worden opgeslagen.<br />
         Bij sharing (D) kan je instellen welke mappen en bestanden je met andere wilt delen.<br />
         </p><p>
         Als dit ingesteld is kan je de instellingen sluiten.<br />
         Vervolgens ga je naar connecties (Afbeelding 2)(A)<br />
         Daar vul je bij het adres (Afbeelding 3)(A) dc.intranet.lan in en klik je vervolgens op connect) (B)<br />
        </p><p>
         Nu kan je chatten met de andere deelnemers en bestanden delen.<br />




         </p>

         <H1>Screenshots</H1>
         <p>Klik op de plaatjes om ze in het groot te bekijken</p>
         <div class="row">
             <div class="col-md-2">
                <h3>Afbeelding 1</h3>
             </div>

             <div class="col-md-2">
                <h3>Afbeelding 2</h3>
             </div>

             <div class="col-md-2">
                <h3>Afbeelding 3</h3>
             </div>
         </div>

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
