<div class="container main-page">
    <div class="row">
        <div class="jumbotron color0 homepage">
            <h1>Welkom bij Soul-Soldiers!</h1>

            <p>
                Wij organiseren sinds 2007 LAN-party's met als enige doel: zo veel mogelijk fun! <br><br>

                Wij hebben geen commerciële doelstelling en onze specialisatie is het organiseren <br>
                van kleinschalige maar vooral gezellige LAN-party's.
            </p>
        </div>
    </div>


    <div class="row mb30">
        <div class="col-md-8 carousel-parent color0">
            <div class="index-caroussel">
                <div class="p40 slide1">
                    <div class="text">
                        <h3>LAN-Parties met oog op gezelligheid en competiviteit!</h3>

                        <p>
                            Wij van Soul-Soldiers zijn gedreven om het uiterste uit onze evenementen te halen.
                            De deelnemer van onze LAN-Party's hebben zich keer op keer positief uitgelaten over onze
                            evenementen en dit haalt ook het uiterste uit ons.<br /> We streven er voor om altijd de leukste
                            en gezelligste evenementen te organiseren.
                        </p>

                        <h2>Competitief gamen</h2>
                        <p>
                            Ook voor de competieve gamer is het leuk om naar een LAN van Soul-Soldiers te gaan.
                            We organiseren altijd evenementen met leuke spellen als toernooi spelen<br /> en uiteraard
                            hebben we altijd prijzen voor onze deelnemers! <br />
                        </p>

                        <h2>Casual gaming</h2>
                        <p>Natuurlijk is de casual gamer ook van harte welkom bij Soul-Soldiers! <br >
                        Er zijn altijd wel mensen in voor een klassiek spelletje Command & Conquer &trade;, Age Of Empires II &trade; of Serious Sam &trade;!

                        </p>
                    </div>
                </div>
                {#<div class="p40 slide2">#}
                {##}
                {#</div>#}
            </div>

        </div>
        <div class="col-md-4 right-container">
            <div class="gutter home-gutter">
                <h2>Volgende evenement</h2>

                <div class="text" style="padding: 10px;">
                    <h3>Soul-Soldiers 2019: Spring Edition</h3>
                    <h4>12, 13 en 14 April 2019</h4>
                    Deze editie zal opnieuw plaats vinden in JC Solution in Hillegom. Ook deze keer hebben we leuke competitities en prijzen! <br /><br />

                    De competities en prijzen zullen z.s.m. bekend gemaakt worden.
                        
                    {#De volgende competities zullen worden gespeeld tijdens de lan: <br  /><br />

                    <ul>
                        <li>Unreal Tournament 2004: Instagib</li>
                        <li>Call of Duty 4: Modern Warfare</li>
                        <li>Warcraft 3: Frozen Throne</li>
                        <li>Trackmania</li>
                    </ul>#}

                    {#<strong>Sponsored by:</strong>
                    <a href="https://www.pyka-wifi.com" target="_blank">
                        <img src="/img/pyka.png" width="310" height="95" border="0"/>
                    </a>#}
                    <br /><br />
                    <a href="{{ url('event/current') }}" class="btn btn-primary">Naar evenements pagina</a>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="jumbotron color0">
            <div class="gutter color0">
                <h3>Nieuws</h3>

                {{ partial("../partials/news") }}
            </div>
        </div>
    </div>
</div>