{% if tournament.systemName %}
    {% set mode = 'edit' %}
    {% set postRoute = 'admin/tournaments/manage/'~tournament.systemName %}
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
    <div class="col-sm-10 noaddon {% if mode is 'edit' %}textonly{% endif %}">
        {% if mode is 'edit' %}
            {{ tournament.typeString }}
        {% else %}
            {{ form.render('type') }}
        {% endif %}
    </div>
</div>

{#<div class="form-group">#}
    {#<label for="name" class="col-sm-2 control-label">Challonge ID</label>#}
    {#<div class="col-sm-10 noaddon">#}
        {#{{ form.render('challongeId') }}#}
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
    <div class="col-md-2 col-md-offset-10">
        <br />
        {{ form.render('Opslaan') }}
    </div>
</div>
{{ endform() }}