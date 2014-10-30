{% if tournament.systemName %}
    {% set mode = 'edit' %}
    {% set postRoute = 'admin/tournaments/manage/'~tournament.systemName %}

    <div class="alert alert-info">
        <h4><i class="icon-attention-circle"></i> Let op!</h4>
         Als je het type toernooi veranderd van een challonge toernooi (single/double elimination) naar
         een topscore toernooi wordt het challonge toernooi verwijderd. Ook alle deelnemers en teams worden verwijderd!
    </div>
{% else %}
    {% set mode = 'add' %}
    {% set postRoute = 'admin/tournaments/add' %}
{% endif %}

{{ form(postRoute, "method": "post", "name":"tournament", "class":"validate form-horizontal", "role":"form") }}

<h4>Algemene instellingen</h4>
<hr />


<div class="form-group">
    <label for="name" class="col-sm-2 control-label">Naam toernooi</label>
    <div class="col-sm-10 noaddon">
        {{ form.render('name') }}
    </div>
</div>

<div class="form-group">
    <label for="startDate" class="col-sm-2 control-label">Start datum*</label>
    <div class="col-sm-10 noaddon">
        {{ form.render('startDate') }}
    </div>
</div>


<div class="form-group">
    <label for="type" class="col-sm-2 control-label">Type toernooi*</label>
    <div class="col-sm-10 noaddon">
        {{ form.render('type') }}
    </div>
</div>

<h4>Team instellingen</h4>
<hr />

<div class="form-group">
    <label for="isTeamTournament" class="col-sm-2 control-label">Team toernooi</label>
    <div class="col-sm-10 noaddon">
        {{ form.render('isTeamTournament') }}
    </div>
</div>

<div class="form-group">
    <label for="" class="col-sm-2 control-label">Team grootte</label>
    <div class="col-sm-10 noaddon">
        {{ form.render('teamSize') }}
    </div>
</div>

<h4>Omschrijving</h4>
<hr />

<div class="form-group">
    <label for="name" class="col-sm-2 control-label">Regels HTML</label>
    <div class="col-sm-10 noaddon">
        {{ form.render('rules') }}
    </div>
</div>

<div class="form-group">
    <label for="name" class="col-sm-2 control-label">Prijzen HTML</label>
    <div class="col-sm-10 noaddon">
        {{ form.render('prizes') }}
    </div>
</div>

<div class="form-group">
    <div class="{% if mode is 'edit' %} col-md-5 col-md-offset-8 {% else %} col-md-2 col-md-offset-10 {% endif %}">
        {% if mode is 'edit' %}
        <a class="btn btn-danger btn-lg" href="{{ url('admin/tournaments/delete/' ~ tournament.systemName) }}" onclick="return confirm('Zeker weten?')"><i class="icon-remove-circle">Verwijderen</i></a>
        {% endif %}
        {{ form.render('Opslaan') }}
    </div>
</div>
{{ endform() }}