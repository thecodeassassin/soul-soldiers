{% set pageTitle = '<i class="icon-award"></i> Toernooien' %}

{% for tournament in tournaments %}

{% set entered = tournament.hasEntered(user.userId) %}
{% set id = tournament.tournamentId %}

<div class="col-md-4">
    <div class="gutter well">
        <div class="text-center mb30">
            <h2>{{ tournament.name }}</h2>
        </div>
        <div class="text-left">

            <table class="table table-unstyled">
                <tbody>
                    <tr>
                        <th>Type tournooi</th>
                        <td>{{ tournament.typeString }}</td>
                    </tr>
                    <tr>
                        <th>Start datum</th>
                        <td>{{ tournament.startDateString }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt30">
            {% if not entered %}
                <a class="btn btn-block btn-success" href="{{ url('tournament/signup/' ~ tournament.systemName ) }}">Inschrijven</a>
            {% endif %}
        </div>

        <!-- Nav tabs -->
        <ul class="nav nav-pills mt30">
            <li class="active"><a href="#deelnemers{{ id }}" data-toggle="tab">Deelnemers</a></li>
            <li><a href="#regels{{ id }}" data-toggle="tab">Regels</a></li>
            <li><a href="#prijzen{{ id }}" data-toggle="tab">Prijzen</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="deelnemers{{ id }}">

                <ul class="list-group">
                {% for player in tournament.playersArray %}
                    <li class="list-group-item">{{ player['user']['nickName'] }}

                        {% if tournament.type == 1 %}
                            <span class="badge">{{ player['totalScore'] }}</span>
                        {% endif %}
                    </li>
                {% else %}
                </ul>
                    <h5>Geen inschrijvingen</h5>
                {% endfor %}
            </div>
            <div class="tab-pane" id="regels{{ id }}">
                {% if view.exists('tournament/rules/' ~ tournament.systemName) %}
                    {{ partial('tournament/rules/' ~ tournament.systemName) }}
                {% endif %}
            </div>
            <div class="tab-pane" id="prijzen{{ id }}">
                {% if view.exists('tournament/prizes/' ~ tournament.systemName) %}
                    {{ partial('tournament/prizes/' ~ tournament.systemName) }}
                {% endif %}
            </div>
        </div>

    </div>
</div>

{% endfor %}