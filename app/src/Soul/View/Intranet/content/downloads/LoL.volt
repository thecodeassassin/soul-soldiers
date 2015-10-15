{% extends 'layout.volt' %}

{% block pageTitle %}<i class="icon-download"></i>&nbsp;League of Legends {% endblock %}
{% block content %}

{% set image_1 = 'img/downloads/sc2_ingame.jpg' %}
{% set image_2 = 'img/downloads/sc2_ingame2.jpg' %}
{% set image_3 = 'img/downloads/sc2_ingame3.jpg' %}

<div class="row">

    <div class="col-md-12">
        <div class="gutter well">

         <h1>Installatie instructies</h1>
         <p><br />
         Download de 2 bestanden en installeer deze. <br />
         Start het spel op lol.launcher.exe <br /> <br />

         Belangrijk !!<br />
         Om dit spel te kunnen spelen heb je een account op : https://signup.euw.leagueoflegends.com <br />


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