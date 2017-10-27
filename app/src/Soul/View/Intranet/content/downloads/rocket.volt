{% extends 'layout.volt' %}

{% block pageTitle %}<i class="icon-download"></i>Rocket League{% endblock %}
{% block content %}

{% set image_1 = 'img/downloads/rl_ingame.jpg' %}
{% set image_2 = 'img/downloads/rl_ingame2.jpg' %}
{% set image_3 = 'img/downloads/rl_ingame3.jpg' %}

<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

         <h1>Installatie instructies</h1>
         <p><br />
         BELANGRIJK : je moet hiervoor Rocket League al hebben op steam!! <br />
         Als je Rocket League al op steam geinstalleerd heb staan hoeft je niets te doen<br />
         Anders installeer je rocket League ,met behulp van de link.<br /> <br />

      

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