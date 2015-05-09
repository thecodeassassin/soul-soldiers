{% if tournament.systemName %}
    {% set mode = 'edit' %}
    {% set postRoute = 'admin/tournaments/manage/'~tournament.systemName %}
{% else %}
    {% set mode = 'add' %}
    {% set postRoute = 'admin/tournaments/add' %}
{% endif %}

{{ form(postRoute, "method": "post", "name":"tournament", "class":"validate form-horizontal", "role":"form", 'enctype':"multipart/form-data", 'onsubmit':'ajaxLoadCallback()') }}

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

{#<div class="form-group">#}
    {#<label for="name" class="col-sm-2 control-label">Achtergrond afbeelding</label>#}
    {#<div class="col-sm-10 noaddon">#}
        {#{{ form.render('imageUpload') }}#}
    {#</div>#}
{#</div>#}

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
        <a class="btn btn-danger btn-lg" href="{{ url('admin/tournaments/delete/' ~ tournament.systemName) }}" onclick="return confirm('Zeker weten?')">
            Verwijderen
        </a>
        {% endif %}
        {{ form.render('Opslaan') }}
    </div>
</div>
{{ endform() }}