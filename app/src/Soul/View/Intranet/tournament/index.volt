{% set pageTitle = '<i class="icon-award"></i> Toernooien' %}


<div class="row">
{% for tournament in tournaments %}

{% set entered = tournament.hasEntered(user.userId) %}
{% set id = tournament.tournamentId %}
{% set isChallonge = tournament.isChallonge %}
{% set isAdmin = user.isAdmin() %}
{% set scoreType = tournament.type == 1 %}

{% set complete = tournament.isCompleted() %}

{% if isChallonge %}
    {% set pending = tournament.challonge.isPending() %}
    {% set complete = tournament.challonge.isCompleted() %}
    {% set awaitingReview = tournament.challonge.isAwaitingReview() %}
{% endif %}

    <div class="col-md-4 tournament-container">
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

                {% if isChallonge and isAdmin and not scoreType %}

                    {% if pending %}
                        <a class="btn btn-block btn-success" href="{{ url('tournament/start/' ~ tournament.systemName ) }}">Start toernooi</a>
                    {% elseif awaitingReview %}
                        <a class="btn btn-block btn-danger" href="{{ url('tournament/end/' ~ tournament.systemName ) }}">Beeindig toernooi</a>
                    {% endif %}
                {% endif %}

                {% if complete %}
                    <div class="alert alert-warning">
                        <span><i class="icon-info-circled"></i>  Het toernooi is afgelopen</span>
                    </div>
                {% endif %}
            </div>

            <!-- Nav tabs -->
            <div class="tournament-tabs">
                <ul class="nav nav-pills mt30">
                    <li class="active"><a href="#deelnemers{{ id }}" data-toggle="tab">Deelnemers</a></li>
                    <li><a href="#regels{{ id }}" data-toggle="tab">Regels</a></li>
                    <li><a href="#prijzen{{ id }}" data-toggle="tab">Prijzen</a></li>
                    {% if isChallonge and not scoreType %}
                    <li><a href="#matches{{ id }}" data-toggle="tab">Matches</a></li>
                    {% endif %}
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="deelnemers{{ id }}">

                        <ul class="list-group">
                            {% for place,player in tournament.playersArray %}

                            {% set removed = player['active'] is '0' %}

                            <li class="list-group-item {% if removed %} disabled{% endif %}">

                                {% if scoreType %}{{ place + 1 }}.&nbsp;{% endif %}{{ player['user']['nickName'] }}

                                {% if scoreType %}
                                    <span class="badge">{{ player['totalScore'] }}</span>
                                {% endif %}

                                {% if isAdmin and scoreType and not removed %}
                                    {{ form('tournament/score/add/' ~ player['tournamentUserId'], "method": "post", "name":"addScore", "class":"validate scoreAdd", "role":"form") }}

                                    <input type="number"  min='0' max="999" value="0" name="scoreCount" class="form-control scoreCount">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="icon-plus"></i></button>
                                    <button type="button" data-url="{{ url('tournament/remove/' ~ player['tournamentUserId']) }}" class="btn btn-sm btn-danger removeUser"><i class="icon-trash"></i></button>

                                    {{ endform() }}


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
    </div>
{% endfor %}
</div>