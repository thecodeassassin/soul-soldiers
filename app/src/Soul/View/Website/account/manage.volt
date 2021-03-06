{% if module == 'website' %}
<div class="row">

    <div class="container">
{% endif %}
        {% if module == 'website' %}
        <h1>Mijn account</h1>
        {% endif %}
        <div class="col-md-12 color0 pt15 pb15">
            <ul class="nav nav-pills mb15">
                <li class="active"><a href="#profile" data-toggle="tab">Profiel informatie</a></li>
                <li><a href="#change-password" data-toggle="tab">Wachtwoord wijzigen</a></li>
                {#<li><a href="#newsletter" data-toggle="tab">Nieuwsbrief</a></li>#}

            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="profile">

                    <div class="alert alert-info email-info-field hidden">
                        <i class="icon-attention"></i>&nbsp; Let op! Als u uw email adres wijzigt dient u uw email adres opnieuw te bevestigen voordat u weer kunt inloggen.
                    </div>
                    {{ form('account/manage', "method": "post", "name":"profile-form", "class":"form-horizontal validate", "role":"form") }}
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">E-mail *</label>
                        <div class="col-sm-7 textonly noaddon email-display-field">
                            {{ userObject.email }}&nbsp; <a class="btn-link edit-email"><i class="icon-pencil">Aanpassen</i></a>
                            {{ hidden_field('email', 'value': userObject.email) }}
                        </div>
                        <div class="col-sm-7 noaddon hidden email-field">
                            {{ form.render('email', {'disabled':'disabled'}) }} &nbsp; <a class="btn-link text-danger edit-email"><i class="icon-cancel-circled">Annuleren</i></a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Volledige naam *</label>
                        <div class="col-sm-7 noaddon">
                            {{ form.render('realName') }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Nickname (bijnaam) *</label>
                        <div class="col-sm-7 noaddon">
                            {{ form.render('nickName') }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Telefoonnummer</label>
                        <div class="col-sm-7 noaddon">
                            {{ form.render('telephone') }}
                        </div>
                        <div class="form-group">
                        </div>
                        <label for="inputPassword3" class="col-sm-2 control-label">Adres</label>
                        <div class="col-sm-7 noaddon">
                            {{ form.render('address') }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Postcode</label>
                        <div class="col-sm-7 noaddon">
                            {{ form.render('postalCode') }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Woonplaats</label>
                        <div class="col-sm-7 noaddon">
                            {{ form.render('city') }}
                        </div>
                    </div>
                    {{ hidden_field('profile-form', 'value': 'true' ) }}

                    {{ form.render('Opslaan') }}
                    {{ endform() }}
                </div>
                <div class="tab-pane" id="change-password">
                    {{ form('account/manage', "method": "post", "name":"password-form", "class":"form-horizontal validate", "role":"form") }}
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Huidige wachtwoord *</label>
                        <div class="col-sm-7 noaddon">
                            {{ chpass.render('currentPassword') }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Wachtwoord *</label>
                        <div class="col-sm-7 noaddon">
                            {{ chpass.render('password') }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Wachtwoord (opnieuw) *</label>
                        <div class="col-sm-7 noaddon">
                            {{ chpass.render('passwordRepeat') }}
                        </div>
                    </div>

                    {{ hidden_field('password-form', 'value': 'true' ) }}

                    {{ chpass.render('Opslaan') }}
                    {{ endform() }}
                </div>
                {#<div class="tab-pane" id="newsletter">...</div>#}
            </div>

        </div>
{% if module == 'website' %}
    </div>
</div>
{% endif %}

{% if module == 'website' %}
<div class="row">
    <div class="container">
{% endif %}
        <div class="col-md-4">
            <span>Velden gemarkeerd met een * zijn verplicht.</span>
        </div>
{% if module == 'website' %}
    </div>
</div>
{% endif %}