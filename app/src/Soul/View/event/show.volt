{{ eventView }}

<div class="jumbotron color0">
    <div class="container">
        <h1>{{ event.name }}</h1>

        {{ event.details }}


        <p><a class="btn btn-primary btn-lg" role="button">Schrijf je nu in!</a></p>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="gutter">
                <h2>Inschrijvingen ({{ event.entries|length }}/{{ event.maxEntries }})</h2>

                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Nickname</th>
                            <th>Betaald</th>
                        </tr>
                    </thead>
                    <tbody>
                {% for entry in event.entries %}
                    <tr>
                        <td>{{ entry.user.realName}}</td>
                        <td>{{ entry.user.nickName}}</td>
                        <td>
                            {% if entry.payment and entry.payment.confirmed %}
                            <span class="label label-success">Ja</span>
                            {% else %}
                            <span class="label label-warning">Nee</span>
                            {% endif %}
                        </td>
                    </tr>
                {% else %}
                    <tr>
                        <td colspan="3">Er zijn nog geen inschrijvingen voor dit evenement</td>
                    </tr>
                {% endfor %}
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div class="gutter">
                <h2>Inschrijvingen</h2>
            </div>
        </div>
    </div>

</div>
