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
                <h3>Toernooien</h3>
                <p>
                    Om deel te nemen aan een toernooi dien je enkel naar de pagina <a href="{{ url('tournaments') }}">Toernooien</a> te gaan
                    en op 'Inschrijven' te klikken. De scores worden door de crew bijgewerkt. <br /> <br />

                    {#<i class="icon-attention-circle"></i>&nbsp; Let op! Voor Starcraft II is het verplicht om door te geven wie er heeft gewonnen en het maken van#}
                    {#een replay is tevens verplicht.#}

                </p>
                {% if tournaments|length > 0 %}
                <h4>Lopende toernooien</h4>
                <p>
                    Hieronder vindt je een lijst van toernooien waar jij voor bent ingeschreven en die momenteel actief zijn.
                </p>

                <div class="list-group">

                {% for systemName,tournamentName in tournaments %}
                    <a class="list-group-item" href="{{ url('tournament/view/'~systemName) }}">{{ tournamentName }}</a>
                {% endfor %}
                </div>

                {% endif %}

                {% if payedForBuffet %}
                <hr />
                <h3>Buffet</h3>
                <p>
                    Fantastisch dat je mee eet met ons buffet op Zaterdag avond. We starten met het serveren van eten om 19:00. <br />
                    Zorg dus dat je op tijd bij de bar staat om gezellig met ons te eten.

                </p>
                <h4>Menu</h4>
                <ul class="menuList">
                    <li>Huisgemaakte Nasi</li>
                    <li>Huisgemaakte thaise loempia's</li>
                    <li>Kipsate</li>
                    <li>Salade</li>
                </ul>
                {% endif %}

            </div>
        </div>
    </div>
</div>
