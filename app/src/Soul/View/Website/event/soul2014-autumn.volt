<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="jumbotron color0 lan-info lan-info-{{ rand(1,4) }}" data-stellar-background-ratio="0.5">

                <h1>{{ event.name }}</h1>

                <p>
                    Een nieuwe editie van Soul-Soldiers!!! We komen beter terug dan ooit.<br />
                    We hebben naar jullie geluisterd en wordt aardig wat geïnvesteerd in het professionaliseren van het netwerk en de diensten rondom de lan.
                    <br /><br />
                    Dit doen wij om voor jullie wederom een fantastische LAN ervaring te bezorgen!<br /> Schrijf je vandaag nog in!
                </p>

                {% if amountPayed >= event.maxEntries %}
                    <p><a class="btn btn-primary btn-lg disabled" role="button" href="javascript:void(0)">Dit evenement is helaas volgeboekt.</a></p>
                {% else %}
                    {% if user %}
                        {% if not registered %}
                            <p><a class="btn btn-primary btn-lg" id="registerEvent" eventName="{{ event.name }}" systemName="{{ event.systemName }}" role="button" href="{{ url('event/register' ~ event.systemName) }}">
                                    <i class="icon-left"></i> Schrijf je nu in!
                                </a>
                            </p>
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
                        <p>
                            <a class="btn btn-primary btn-lg" role="button" href="{{ url('register') }}">Maak nu je account aan!</a> of klik
                            <a href="{{ url('login') }}">hier</a> om in te loggen
                        </p>
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
                {% if user and user.userType == 3 %}
                    <th>Email</th>
                {% endif %}
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
                    {% if user and user.userType == 3 %}
                        <td>{{ entry.user.email }}</td>
                    {% endif %}
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

        <table class="table noborders">
            <tr>
                <td><strong>Datum</strong></td>
                <td>Vrijdag 31 oktober 2014 19:00 - Zondag 2 november 2014 17:00</td>
            </tr>
            <tr>
                <td><strong>Locatie</strong></td>
                <td><address>
                        JC de Stip <br />
                        Venneperweg 298, 2153 AE Nieuw-Vennep
                    </address>
                </td>
            </tr>
            <tr>
                <td><strong>Prijs entreeticket</strong></td>
                <td>&euro; {{ '%01.2f'|format(event.product.cost) }} voor 3 dagen</td>
            </tr>
        </table>

        <h3>Aanvullende informatie</h3>
        <p>
            <strong>Let op! Per persoon is er maar 1 monitor toegestaan!</strong><br /><br />

            Tijdens het evenement is het <strong>toegestaan</strong> om eigen eten en drinken mee te nemen. <br />
            Er wordt een koelkast ter beschikking gesteld om producten die gekoeld dienen te worden <br /> in op te slaan.
            Zorg dat je stickers mee neemt om je producten te markeren, zodat iemand niet per ongeluk iets van jou pakt.
            De ruimte in de koelkast is beperkt, <br />dus we willen aan iedereen vragen de volgende richtlijnen niet te overschrijden: <br /> <br />

            * max 2 flessen/pakken drinken<br />
            * max 2 avond maaltijden/snacks (zoals broodjes bapau)

            <br /><br />
            Het nuttigen van alcohol is <strong>niet toegestaan</strong> tijdens het evenement. <br/>
            Tijdens dit evenement gelden onze <a href="{{ url('content/rules') }}">algemene voorwaarden.</a>
            <br /><br />
            Er is een aparte slaapruimte, de ruimte is echter beperkt dus het is <strong>NIET</strong> toegestaan <br />
            een 2 persoons luchtbed mee te nemen, tenzij je de luchtbed deelt met een andere deelnemer.
        </p>
    </div>
</div>
