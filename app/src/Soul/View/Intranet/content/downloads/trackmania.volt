{% set pageTitle = '<i class="icon-download"></i>&nbsp;TrackMania' %}
{% set image_1 = 'img/downloads/trackmania_ingame.jpg' %}
{% set image_2 = 'img/downloads/trackmania_ingame2.jpg' %}
{% set image_3 = 'img/downloads/trackmania_ingame3.jpg' %}
<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

            <h1>Installatie instructies</h1>
           <br />
                Trackmania kan op 2 manieren geinstalleerd worden.<br />
                Manier 1 (steam ) : <br />
                Download het zip bestand en pak deze op een willekeurige plaats op <br />
                Open je steam en ga naar : Steam -> backup en herstel <br />
                Selecteer herstel een backup en blader naar de map waar je het bestand heb uitgepakt.<br />
                Klik op volgende en steam zal het spel gaan installeren. <br /><br />

                Manier 2 ( manual ) : <br />
                Deze optie is meer voor de geavanceerde gebruiker.<br />
                Download het zip bestand en pak deze uit naar de volgende locatie in je steam folder :<br />
                Steam -> SteamApps -> common <br />
                Start Steam op en zoek in de steam store naar Trackmania Nation Forever. <br />
                Klik op install / play now en steam zorgd voor de rest <br /><br />



                Om Trackmania te kunnen spelen heb je steam nodig.<br />
                Indien je deze niet heb kan je deze hier gratis downloaden :<a href="http://store.steampowered.com/about/" target="_blank"> Download Steam</a>
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
