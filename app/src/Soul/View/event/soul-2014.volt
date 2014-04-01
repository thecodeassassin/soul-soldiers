<div class="row">
    <div class="container">
        <div class="col-md-12">

            <div class="jumbotron color0 lan-info" data-stellar-background-ratio="0.5">

                <h1>{{ event.name }}</h1>

                <p>
                    Sinds het success van Soul-Soldiers: The Reunion hebben we besloten een nieuwe lan te organiseren!<br />
                    Het beloofd weer een gezellige lan
                </p>
                {% if amountPayed >= event.maxEntries %}
                <p><a class="btn btn-primary btn-lg disabled" role="button" href="javascript:void(0)">Dit evenement is helaas volgeboekt.</a></p>
                {% else %}
                    {% if user %}
                        {% if not registered %}
                            <p><a class="btn btn-primary btn-lg" id="registerEvent" eventName="{{ event.name }}" systemName="{{ event.systemName }}" role="button" href="{{ url('event/register' ~ event.systemName) }}">Schrijf je nu in!</a></p>
                        {% else %}

                            {% if not payed %}
                                <p><a class="btn btn-primary btn-lg" role="button" href="{{ url('event/pay/' ~ event.systemName) }}">
                                        <span class="icon-euro"></span>
                                        Entreeticket betalen
                                    </a>
                                </p>
                            {% else %}
                                <p><a class="btn btn-primary btn-lg disabled" role="button" href="javascript:void(0)">Bedankt voor je betaling!</a></p>
                            {% endif %}


                        {% endif %}
                    {% else %}
                        <p><a class="btn btn-primary btn-lg" role="button" href="{{ url('register') }}">Maak nu je account aan!</a></p>
                    {% endif %}
                {% endif %}
            </div>

        </div>
    </div>
</div>
<div class="row color0">

    <div class="col-md-5 col-md-offset-1 pb15 pt15">
        <h2>Inschrijvingen ({{ event.entries|length }}/{{ event.maxEntries }})</h2>

        <table class="table table-condensed">
            <thead>
            <tr>
                <th>Naam</th>
                <th>Nickname</th>
                {% if user and registered %}
                <th>Betaald</th>
                {% endif %}
            </tr>
            </thead>
            <tbody>
            {% for entry in event.entries %}
                <tr>
                    <td>{{ entry.user.realName}}</td>
                    <td>{{ entry.user.nickName}}</td>
                    {% if user and registered %}
                    <td>
                        {% if entry.payment and entry.payment.confirmed %}
                            <span class="label label-success">Ja</span>
                        {% else %}
                            <span class="label label-warning">Nee</span>
                        {% endif %}
                    </td>
                    {% endif %}
                </tr>
            {% else %}
                <tr>
                    <td colspan="3">Er zijn nog geen inschrijvingen voor dit evenement</td>
                </tr>
            {% endfor %}
            </tbody>
        </table>
    </div>

    <div class="col-md-4 col-md-offset-1 pb15 pt15">
        <h2>Informatie</h2>

        <h3>Datum en locatie</h3>
        <table class="table noborders">
            <tr>
                <td><strong>Datum</strong></td>
                <td>24, 25 en 25 Mei 2014</td>
            </tr>
            <tr>
                <td><strong>Locatie</strong></td>
                <td><address>
                    JC de Stip <br />
                    Venneperweg 298, 2153 AE Nieuw-Vennep
                    </address>
                </td>
            </tr>
        </table>

        <h3>Aanvullende informatie</h3>
        <p>
            Tijdens het evenement is het <strong>toegestaan</strong> om eigen eten en drinken mee te nemen. <br />
            Er wordt een koelkast ter beschikking gesteld om producten die gekoeld dienen te worden in op te slaan. <br />
            De ruimte in de koelkast is beperkt, dus we willen aan iedereen vragen de volgende richtlijnen niet te overschrijden. <br /> <br />

            * max 2 flessen/pakken drinken
            * max 2 avond maaltijden/snacks (zoals broodjes bapau)

            <br /><br />
            Het nuttigen van alcohol is <strong>niet toegestaan</strong> tijdens het evenement. <br/>
            Tijdens dit evenement gelden onze <a href="{{ url('content/rules') }}">algemene voorwaarden.</a>
        </p>
    </div>
</div>