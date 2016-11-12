{% extends 'layout.volt' %}

{% block pageTitle %}<i class="icon-home"></i>&nbsp;Home{% endblock %}

{% block content %}
    <div class="row">

    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12">
                <div class="gutter well">
                    <h1>Welkom op het intranet van Soul-Soldiers!</h1>
                    <p>
                        Op het intranet kun je je inschrijven voor toernooien, games voor de toernooien downloaden en het laatste nieuws lezen. <br />
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="gutter well">
                    <h4>Nieuws</h4>

                    {{ partial("../partials/news") }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <div class="gutter well">
                    <h3><i class="icon-info-circled"></i> Informatie</h3>

                    <hr />
                    <h3>Avondeten (Zaterdag 19:00)</h3>
                    <p>
                        {% if payedForBuffet %}
                        Fantastisch dat je mee eet op Zaterdag avond! We starten met het serveren van eten om 19:00. <br />
                        Zorg dus dat je op tijd buiten staat om gezellig met ons te eten.
                        {% else %}
                        Je hebt (nog) niet betaald voor het avondeten. Mocht je hier toch aan mee willen doen, geef dit dan aan bij 1 van de crewleden. <br />
			De kosten zijn 7,50 per persoon.
                        {% endif %}
                    </p>

                    <h4>Eten/Drinken</h4>
                    <p>
                        {#Er is bij de crew energy drink te koop voor &euro; 1 euro per stuk. Tijdens deze lan geld tevens 3 halen 2 betalen voor de energy drink :) <br />#}
                        Iedereen is verantwoordelijk voor z'n eigen eten en drinken tijdens de LAN party. <br />Er staat een koelkast in de zaal die vrij te gebruiken is.
                    </p>
                    <h4>Filesharing</h4>
                    <ul>
                        <li><a href="{{ url('content/downloads') }}">Downloads pagina</a></li>
			<li>Fileserver: \\FILESERVER</li>
                    </li>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="gutter well">
                    <div class="mt30">
                        <h3><span class="icon-award"></span> Toernooien</h3>
                        <p>
                            Om deel te nemen aan een toernooi dien je enkel naar de pagina van het toernooi te gaan
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


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{% endblock %}
