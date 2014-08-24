<h2>Gegevens</h2>
{{ form('admin/edituser/' ~ userId, "method": "post", "name":"login", "class":"form-horizontal validate", "role":"form") }}
<div class="form-group">
    <label for="email" class="col-sm-4 control-label">E-mail</label>
    <div class="col-sm-7 textonly noaddon">
        {{ form.render('email') }}
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-sm-4 control-label">Volledige naam *</label>
    <div class="col-sm-7 noaddon">
        {{ form.render('realName') }}
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-sm-4 control-label">Nickname (bijnaam) *</label>
    <div class="col-sm-7 noaddon">
        {{ form.render('nickName') }}
    </div>
</div>
<div class="form-group">
    <label for="inputPassword3" class="col-sm-4 control-label">Telefoonnummer</label>
    <div class="col-sm-7 noaddon">
        {{ form.render('telephone') }}
    </div>
    <div class="form-group">
    </div>
    <label for="inputPassword3" class="col-sm-4 control-label">Adres</label>
    <div class="col-sm-7 noaddon">
        {{ form.render('address') }}
    </div>
</div>
<div class="form-group">
    <label for="inputPassword3" class="col-sm-4 control-label">Postcode</label>
    <div class="col-sm-7 noaddon">
        {{ form.render('postalCode') }}
    </div>
</div>
<div class="form-group">
    <label for="inputPassword3" class="col-sm-4 control-label">Woonplaats</label>
    <div class="col-sm-7 noaddon">
        {{ form.render('city') }}
    </div>
</div>

<h2>Evenement</h2>

<div class="form-group">
    <label for="registered" class="col-sm-4 control-label">Ingeschreven voor volgend evenement</label>
    <div class="col-sm-7 noaddon">
        <p class="form-control-static"><strong>{% if registered %} Ja {% else %} Nee {% endif %}</strong></p>
    </div>
</div>
{% if not registered %}
<div class="alert alert-warning">
    Deze gebruiker kan niet op betaald worden gezet, inschrijven kan alleen door de deelnemer zelf gebeuren aangezien ze <br />
    akkoord dienen te gaan met onze algemene voorwaarden.
</div>
{% else %}
<div class="form-group">
    <label for="payed" class="col-sm-4 control-label">Betaald voor evenement</label>
    <div class="col-sm-7 noaddon">

            {% if payed %}
                <p class="form-control-static"><strong>Ja</strong>
                {% if payedForBuffet %} + Buffet {% endif %}
                </p>
            {% else %}
            <div class="checkbox">
                <input type="checkbox" name="payed" {% if payed %}checked {% endif %} id="paymentcheck" />
                 / Betaling referentie <input type="text" class="form-control" maxlength="15" name="paymentReference" id="paymentReference" />
            </div>
            {% endif %}
    </div>
</div>
    {% if payed and not payedForBuffet or not payed %}
        <div class="form-group">
            <label for="registered" class="col-sm-4 control-label">Betaald voor het buffet</label>
            <div class="col-sm-7 noaddon">
                <div class="checkbox">
                    <input type="checkbox" name="buffet" />
                </div>
            </div>
        </div>

    {% endif %}

{% endif %}
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <a class="btn btn-lg" href="{{ url('admin/users') }}">Terug</a>
        {{ form.render('Opslaan') }}
    </div>
</div>

{{ endform() }}