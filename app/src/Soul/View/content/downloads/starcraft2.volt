
{#<div class="row">#}
    {#{% set image_1 = 'img/downloads/starcraft_2_ingame.jpg' %}#}
    {#{% set image_2 = 'img/downloads/starcraft_2_ingame2.jpg' %}#}
    {#{% set image_3 = 'img/downloads/starcraft_2_ingame3.jpg' %}#}

    {#<div class="container">#}
        {#<h1>Starcraft 2 Screenshots + Installatie instructies</h1>#}
        {#<div class="col-md-12 color0 pb15 pt15">#}

         {#<h1>Installatie instructies</h1>#}
         {#<p>Uitleg<br />#}
         {#Download alle 4 de delen die op de download pagina te vinden zijn.<br />#}
         {#Installeer het spel ( met behulp van de gedownloade exe file. <br />#}
         {#In de map waar je het spel heb geinstalleerd staat de file starcraft2.exe, start hiermee het spel op.<br /><br />#}

         {#Om starcraft 2 te kunnen spelen heb je een blizzard account nodig.<br />#}
         {#Indien je deze niet heb kan je deze hier gratis aanmaken :<a href="https://eu.battle.net/account/creation/tos.html" target="_blank"> Battlenet account Creatie</a>#}
         {#</p>#}

         {#<H1>Screenshots : </H1>#}
         {#<p>Klik op de plaatjes om ze in het groot te bekijken</p>#}


            {#<a href="{{ url(image_1) }}" class="image-link">#}
                {#{{ image(image_1 ,'height': 250, 'width': 250)}}#}
            {#</a>#}

            {#<a href="{{ url(image_2) }}" class="image-link">#}
                {#{{ image(image_2 ,'height': 250, 'width': 250)}}#}
            {#</a>#}

            {#<a href="{{ url(image_3) }}" class="image-link">#}
                {#{{ image(image_3 ,'height': 250, 'width': 250)}}#}
            {#</a>#}


        {#</div>#}
    {#</div>#}
{#</div>#}
