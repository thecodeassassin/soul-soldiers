{% if tournament.systemName %}
    {% set mode = 'edit' %}
    {% set postRoute = 'admin/tournaments/manage/'~tournament.systemName %}

    <div class="alert alert-info">
        <h4><i class="icon-attention-circle"></i> Let op!</h4>
         Als je het type toernooi veranderd van een challonge toernooi (single/double elimination) naar
         een topscore toernooi wordt het challonge toernooi verwijderd.
    </div>
{% else %}
    {% set mode = 'add' %}
    {% set postRoute = 'admin/tournaments/add' %}
{% endif %}

{{ form(postRoute, "method": "post", "name":"tournament", "class":"validate form-horizontal", "role":"form") }}
<div class="form-group">
    <label for="name" class="col-sm-2 control-label">Naam toernooi</label>
    <div class="col-sm-10 noaddon">
        {{ form.render('name') }}
    </div>
</div>

<div class="form-group">
    <label for="name" class="col-sm-2 control-label">Start datum*</label>
    <div class="col-sm-10 noaddon">
        {{ form.render('startDate') }}
    </div>
</div>


<div class="form-group">
    <label for="name" class="col-sm-2 control-label">Type toernooi*</label>
    <div class="col-sm-10 noaddon">

        {{ form.render('type') }}

    </div>
</div>

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
    <div class="col-md-5 col-md-offset-8">
        <a class="btn btn-danger btn-lg" href="{{ url('admin/tournaments/delete/' ~ tournament.systemName) }}" onclick="return alert('Zeker weten?')"><i class="icon-remove-circle">Verwijderen</i></a>
        {{ form.render('Opslaan') }}
    </div>
</div>
{{ endform() }}