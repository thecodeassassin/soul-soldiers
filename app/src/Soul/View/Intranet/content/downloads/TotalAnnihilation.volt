
<div class="row">
    {% set image_1 = 'img/downloads/ta_ingame.jpg' %}
    {% set image_2 = 'img/downloads/ta_ingame2.jpg' %}
    {% set image_3 = 'img/downloads/ta_ingame3.jpg' %}

    <div class="container">
        <h1>Total Annihilation</h1>
        <div class="col-md-12 color0 pb15 pt15">


            <h1>Installatie instructies</h1>
            <p>Download de file en installeer deze naar de plek naar keuze<br />
                In de Total Annihilation map staat het bestand TotalA.exe waarmee het spel gespeeld kan worden</p>


            <H1>Screenshots : </H1>
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