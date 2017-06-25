{% if submitted %}
    {{ partial('contact/send') }}
{% else %}

<script src="https://www.google.com/recaptcha/api.js?hl=nl"></script>
<div class="row">
    <div class="container">
        <h1>Contact opnemen</h1>
        <div class="col-md-10 color0 pb15 pt15">
            <h4>
                U kunt middels dit formulier gemakkelijk contact met ons opnemen, wij zullen binnen 48 uur reageren op uw
                bericht. Voor dringende zaken kunt u ons ook telefonisch bereiken, ons telefoonnummer vindt u onderaan de pagina.
            </h4><br />

            {{ form('contact', "method": "post", "name":"contact", "class":"validate", "role":"form") }}
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-5 control-label">Uw E-mail adres *</label>
                <div class="col-sm-7 noaddon">
                    {{ form.render('email') }}
                </div>
            </div>
            <div class="form-group">
                <label for="inputRealName" class="col-sm-5 control-label">Volledige naam *</label>
                <div class="col-sm-7 noaddon">
                    {{ form.render('realName') }}
                </div>
            </div>
            <div class="form-group">
                <label for="inputContent" class="col-sm-5 control-label">Uw vraag/opmerking *</label>
                <div class="col-sm-7 noaddon">
                    {{ form.render('content') }}
                </div>
            </div>
            <div class="form-group">
                <label for="inputCaptcha" class="col-sm-5 control-label">Verificatie *</label>
                <div class="col-sm-7 noaddon">
                    <div class="g-recaptcha" data-sitekey="{{ form.getCaptchaSiteKey() }} "></div>
                </div>
            </div

            <div class="form-group">
                <div class="col-md-2 col-md-offset-10">
                    <br />
                    {{ form.render('csrf', ['value': security.getToken()]) }}
                    {{ form.render('Versturen') }}
                </div>
            </div>
            {{ endform() }}
        </div>
    </div>
</div>
<div class="row">
    <div class="container">
        <div class="col-md-4">
            <span>Velden gemarkeerd met een * zijn verplicht.</span>
        </div>
    </div>
</div>
{% endif %}