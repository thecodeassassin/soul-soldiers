{% if tournament %}
    {% set pageTitle = '<i class="icon-award"></i> Toernooien / ' ~ tournament.name %}
{% endif %}

{% if tournament and not tournament.hasError %}
    {% set pageTitle = '<i class="icon-award"></i> Toernooien / ' ~ tournament.name %}

    {% set entered = tournament.hasEntered(user.userId) %}
    {% set id = tournament.tournamentId %}
    {% set isChallonge = tournament.isChallonge %}
    {% set isAdmin = user.isAdmin() %}
    {% set isTeamTournament = tournament.isTeamTournament() %}
    {% set teams = tournament.teams %}
    {% set scoreType = tournament.type == 1 %}

    {% set complete = tournament.state == constant('\Soul\Model\Tournament::STATE_FINISHED') %}
    {% set pending = tournament.state == constant('\Soul\Model\Tournament::STATE_PENDING') %}
    {% set started = tournament.state == constant('\Soul\Model\Tournament::STATE_STARTED') %}


{% if complete %}
   <div class="alert alert-warning">
       <span><i class="icon-info-circled"></i>  Het toernooi is afgelopen</span>
   </div>
{% endif %}

{% if (pending and scoreType) or (not entered and pending) or isAdmin or (isChallonge and not isTeamTournament) or (isTeamTournament and teams|length == 0) %}
<div class="row">
    <div class="col-md-12">
        <div class="gutter well">
            {% if not entered and pending %}
                <a class="btn btn-lg btn-success action-btn" href="{{ url('tournament/signup/' ~ tournament.systemName ) }}">Inschrijven</a>
            {% endif %}

            {% if entered and pending and not isTeamTournament or (entered and isTeamTournament and teams|length == 0) %}
                <a class="btn btn-lg btn-danger" onclick="return ajaxConfirm('Weet je het zeker?');" href="{{ url('tournament/cancel/' ~ tournament.systemName ) }}">Uitschrijven</a>
            {% endif %}

            {% if isAdmin %}

                {% if (pending and not isTeamTournament) or (isTeamTournament and teams|length and pending) > 0%}
                    <a class="btn btn-lg btn-primary action-btn" href="{{ url('tournament/start/' ~ tournament.systemName ) }}">Start toernooi</a>
                {% elseif started %}
                    <a class="btn btn-lg btn-danger action-btn" href="{{ url('tournament/end/' ~ tournament.systemName ) }}">Beeindig toernooi</a>
                {% endif %}
            {% endif %}

            {% if isChallonge and not scoreType and not pending %}
               <a href="#matches{{ id }}" class="btn btn-lg btn-default matchLink" data-tournament-id="{{ tournament.systemName }}">Bekijk matches</a>
            {% endif %}

            {% if isTeamTournament and pending and isAdmin %}
                <a class="btn btn-lg btn-primary" onclick="return ajaxConfirm('Weet je het zeker? Alle huidige teams worden verwijderd.');"
                   href="{{ url('tournament/generateteams/' ~ tournament.systemName ) }}">Genereer teams</a>
            {% endif %}

            {% if isAdmin %}
                <a data-tournament-id="{{ tournament.systemName }}" class="generate-players btn btn-lg btn-default"><i class="icon-attention">

                        </i> Genereer spelers

                </a>
                <a href="{{ url('admin/tournaments/manage/' ~ tournament.systemName) }}" class="btn btn-lg btn-default"><i class="icon-pencil-squared"></i> Aanpassen</a>
            {% endif %}

        </div>
    </div>
</div>
{% endif %}

<div class="row">
    <div class="col-md-4 tournament-container">
        <div class="gutter well">
            <div class="text-left">
                <h3>Informatie</h3>
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
                    <tr>
                        <th>Status</th>
                        <td>{{ tournament.stateString }}</td>
                    </tr>
                    {% if isTeamTournament %}
                        <tr>
                            <th>Team type</th>
                            <td>{{ tournament.teamSize }} vs {{ tournament.teamSize }}</td>
                        </tr>
                    {% endif %}

                    </tbody>
                </table>
            </div>

            <h4>Regels</h4>
            <div class="rules">
                {{ tournament.rules }}
            </div>

            <h4>Prijzen</h4>
            <div class="prizes">
                {{ tournament.prizes }}
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="gutter well">

            <!-- Nav tabs -->
            <div class="tournament-info">
                <h3>Deelnemers ({{ tournament.players|length }})</h3>

                <ul class="list-group">
                    {% for place,player in tournament.players %}

                    {% set removed = player.active is '0' %}

                    <li class="list-group-item {% if removed %} disabled{% endif %}">

                        {% if scoreType %}{{ place + 1 }}.&nbsp;{% endif %}
                        {% if isChallonge and not isTeamTournament and player['rank'] is defined %}{{ player['rank'] }}.&nbsp;{% endif %}

                        {{ player.user.nickName }}

                        {% if scoreType %}
                            {#<span class="badge">{{ player['totalScore'] }}</span>#}
                        {% endif %}


                        {% if isAdmin %}
                        <div class="scoreControls">

                        {% if scoreType and not removed and started %}
                                {{ form('tournament/score/add/' ~ player.tournamentUserId, "method": "post", "name":"addScore", "class":"validate scoreAdd", "role":"form") }}

                                <input type="number"  max="999" value="0" name="scoreCount" class="form-control scoreCount">
                                <button type="submit" class="pull-left btn btn-sm btn-success"><i class="icon-plus"></i></button>
                                {{ endform() }}
                            {% endif %}

                        {% if pending or (scoreType and not complete and player['active']) %}
                        <a href="{{ url('tournament/removeUser/'~ tournament.systemName ~ '/' ~ player.tournamentUserId) }}" onclick="return ajaxConfirm('Weet je zeker dat deze gebruiker niet meer mee doet?');"
                           class="pull-left btn btn-sm {% if started and scoreType %}btn-default{% else %}btn-danger{% endif %} removeUser"><i class="icon-trash"></i></a>
                        {% endif %}
                        </div>
                        {% endif %}
                    </li>
                    {% else %}
                </ul>
                <h5>Er zijn nog geen inschrijvingen</h5>
                {% endfor %}

            </div>

        </div>
    </div>

    {% if isTeamTournament %}
    <div class="col-md-4">
        <div class="gutter well">
            <h3>Teams</h3>
                <div class="list-group team">
                {% for team in teams %}

                    <div class="list-group-item">
                        <h4 class="list-group-item-heading">
                            {{ team.name }}
                            {% if isAdmin and pending %}
                                <a class="btn btn-primary" href="{{ url('admin/editTeamName/' ~ team.teamId) }}"
                                   id="editTeamName"
                                   rel="remote-modal"
                                   data-before-submit-callback="ajaxLoadCallback">
                                    <i class="icon-pencil"></i></a>
                            {% endif %}
                        </h4>
                        <div class="list-group-item-text">
                            <ul class="list-unstyled">
                                {% for player in team.getPlayers() %}
                                    <li>{{ player.user.nickName }}</li>
                                {% endfor %}
                            </ul>
                        </div>
                    </div>

                {% else %}
                    <h4>Er zijn nog geen teams aangemaakt</h4>
                {% endfor %}
                </div>
        </div>
    </div>
    {% endif %}

</div>
{% else %}
    <div class="alert alert-danger">
        Dit toernooi kan nu niet geladen worden, probeer het later nogmaals.
    </div>
{% endif %}


{% block javascript %}
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>

    <script type="text/javascript">
        $('.tournament-info').find('ul.list-group').sortable();
    </script>
{% endblock %}