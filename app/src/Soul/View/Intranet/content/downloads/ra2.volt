{% extends 'layout.volt' %}

{% block content %}
{% set pageTitle = '<i class="icon-download"></i>&nbsp;Red Alert 2' %}
{% set image_1 = 'img/downloads/ra2_ingame.jpg' %}
{% set image_2 = 'img/downloads/ra2_ingame2.jpg' %}
{% set image_3 = 'img/downloads/ra2_ingame3.jpg' %}

<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

         <h1>Installatie instructies</h1>
         <p><br />
         Download het bestand wat op de download pagina te vinden is.<br />
         Installeer het spel ( met behulp van de gedownloade exe file). <br />
         In de map waar je het spel heb geinstalleerd staat de file ra2md.exe, start hiermee het spel op.<br /><br />

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