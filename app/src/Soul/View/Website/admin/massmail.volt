{% if submitted %}
    <h1>Email verstuurd</h1>
{% else %}

        <h1>Mass mail</h1>
        {{ form('admin/massmail', "method": "post", "name":"massmail", "class":"validate", "role":"form") }}
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-3 control-label">Onderwerp *</label>
            <div class="col-sm-9 noaddon">
                {{ form.render('subject') }}
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-3 control-label">Emails *</label>
            <div class="col-sm-9 noaddon">
                {{ form.render('emails') }}
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-3 control-label">Email text *</label>
            <div class="col-sm-9 noaddon">
                {{ form.render('content') }}
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-2 col-md-offset-10">
                <br />
                {{ form.render('csrf', ['value': security.getToken()]) }}
                {{ form.render('Versturen') }}
            </div>
        </div>
        {{ endform() }}

{% endif %}