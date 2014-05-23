{% set pageTitle = '<i class="icon-home"></i>Home' %}

<div class="row">
    <div class="col-md-12">
        <div class="gutter well">
            <h4>Welkom op het intranet van Soul-Soldiers 2014!</h4>


            <p>
                Op het intranet kun je je inschrijven voor toornooien, games voor de toernooien downloaden en het laatste nieuws lezen.
            </p>

        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-8">
        <div class="gutter well">
            <h4>Nieuws</h4>

            {{ partial("../partials/news") }}
        </div>
    </div>

    <div class="col-md-4">
        <div class="gutter well">
            <h4><span class="icon-help"></span> Help</h4>

            <div class="mt30">
                <h5>Toernooien</h5>
                <p>
                    Om deel te nemen aan een toernooi dien je enkel naar de pagina <a href="{{ url('tournaments') }}">Toernooien</a> te gaan
                    en op 'Inschrijven' te klikken. De scores worden door de crew bijgewerkt. <br /> <br />

                    <i class="icon-attention-circle"></i>&nbsp; Let op! Voor Starcraft II is het verplicht om door te geven wie er heeft gewonnen en het maken van
                    een replay is tevens verplicht.

                </p>
                <h5>BBQ</h5>
                <p>
                    Er is een BBQ op zaterdag. We hebben voor elke deelnemer een hamburger, een worst en 2 sate stokjes.
                </p>

            </div>
        </div>
    </div>
</div>
