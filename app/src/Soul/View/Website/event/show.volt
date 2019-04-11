{% set eventFull = amountPayed >= event.maxEntries}

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="jumbotron color0 lan-info lan-info-{{ rand(1,4) }}" data-stellar-background-ratio="0.5">

                <h1>{{ event.name }}</h1>

                {{ event.details }}

                {% if eventFull and not payed %}
                    <p><a class="btn btn-primary btn-lg disabled" role="button" href="javascript:void(0)">Dit evenement is helaas volgeboekt.</a></p>
                {% else %}
                    {% if user %}
                        {% if not registered and not eventFull %}
                            <p><a class="btn btn-primary btn-lg" id="registerEvent" eventName="{{ event.name }}" systemName="{{ event.systemName }}" role="button" href="{{ url('event/register' ~ event.systemName) }}">
                                    <i class="icon-left"></i> Schrijf je nu in!
                                </a>
                            </p>
                        {% else %}

                            {% if not payed and not eventFull %}
                                <p><a class="btn btn-primary btn-lg" role="button" href="{{ url('event/pay/' ~ event.systemName) }}">
                                        <span class="icon-euro"></span>
                                        Entreeticket betalen
                                    </a>
                                </p>
                            {% elseif payed %}

                                {% if event.seatmap %}
                                <p><a class="btn btn-primary btn-lg"
                                    rel="remote-modal"
                                    data-onshow-callback="activateToolTips"
                                    role="button"
                                    href="{{ url('event/seat/' ~ event.systemName) }}">
                                    Plek reserveren
                                    </a>
                                </p>
                                {% else %}
                                    <p><a class="btn btn-primary btn-lg disabled" role="button" href="javascript:void(0)">Bedankt voor je betaling!</a></p>
                                {% endif %}
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
<div class="row color0 event-page">

    <div class="col-md-5 col-md-offset-1 pb15 pt15">
        <h2>Inschrijvingen ({{ event.entries|length }}) / Vrije plekken: {{ event.maxEntries - amountPayed }}</h2>

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

    {{ partial('event/general_information') }}

</div>
