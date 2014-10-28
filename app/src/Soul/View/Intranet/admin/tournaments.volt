{% set pageTitle = '<i class="icon-gauge"></i> <a href="/admin/index">Admin</a> / Toernooien' %}


<div class="row">

    <div class="col-md-12 pt15 pb15">
        <a class="btn btn-primary" href="{{ url('admin/tournaments/add') }}"><i class="icon-plus-circled">Nieuw toernooi</i></a>
    </div>

    <div class="col-md-12">
        <div class="gutter well">

            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>Naam</th>
                        <th>Type</th>
                        <th>Startdatum</th>
                        <th>Status</th>
                        <th>Actie</th>
                    </tr>
                </thead>
                <tbody>
                    {% for tournament in tournaments %}
                    <tr>
                        <td>{{ tournament.name }}</td>
                        <td>{{ tournament.typeString }}</td>
                        <td>{{ tournament.startDate|date('d-m-Y H:i:s') }}</td>
                        <td>{% if tournament.hasError %}<span class="text-danger"><i class="icon-attention-circle"></i> </span>{% else %}<span class="text-success"><i class="icon-check"></i> </span>{% endif %}</td>
                        <td>
                            <a class="btn btn-default" href="{{ url('admin/tournaments/manage/' ~ tournament.systemName) }}"><i class="icon-edit">Aanpassen</i></a>
                            <a class="btn btn-danger" href="{{ url('admin/tournaments/delete/' ~ tournament.systemName) }}" onclick="return alert('Zeker weten?')"><i class="icon-remove-circle">Verwijderen</i></a>

                            {% if tournament.isChallonge %}
                                <a class="btn btn-info matchLink" data-toggle="tooltip" title="Bekijk matches" href="#matches{{ id }}" data-tournament-id="{{ tournament.systemName }}"><i class="icon-eye"></i> </a>
                            {% endif %}
                        </td>
                    </tr>
                    {% else %}
                        <tr>
                            <td colspan="4">
                                <h3>Geen toernooien gevonden</h3>
                            </td>
                        </tr>a
                    {% endfor %}
                </tbody>
            </table>
        </div>
    </div>
</div>