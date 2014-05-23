{% set pageTitle = '<i class="icon-download"></i>&nbsp;Heartstone' %}
{% set image_1 = 'img/downloads/hs_ingame.jpg' %}
{% set image_2 = 'img/downloads/hs_ingame2.jpg' %}
{% set image_3 = 'img/downloads/hs_ingame3.jpg' %}
<div class="row">

    <div class="col-md-12">
        <div class="gutter well">
            <h1>Installatie instructies</h1>
            <p>Download de file en installeer deze naar de plek naar keuze<br />
                In de Hearthstone map staat het bestand Hearthstone.exe waarmee het spel gespeeld kan worden</p>

            <p>
            Om Hearthstone te kunnen spelen heb je een blizzard account nodig.<br />
                Indien je deze niet heb kan je deze hier gratis aanmaken :<a href="https://eu.battle.net/account/creation/tos.html" target="_blank"> Battlenet account Creatie</a>
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
