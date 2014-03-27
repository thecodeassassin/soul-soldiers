<div class="row">
    <div class="container">
        <h1>Contact opnemen</h1>
        <div class="col-md-10 color0 pb15 pt15">
            {{ form('contact', "method": "post", "name":"contact", "class":"validate", "role":"form") }}
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-5 control-label">Uw E-mail adres *</label>
                <div class="col-sm-7 noaddon">
                    {{ form.render('email') }}
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-5 control-label">Volledige naam *</label>
                <div class="col-sm-7 noaddon">
                    {{ form.render('realName') }}
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-5 control-label">Uw vraag/opmerking *</label>
                <div class="col-sm-7 noaddon">
                    {{ form.render('content') }}
                </div>
            </div>

            <div class="form-group">
                {{ form.render('csrf', ['value': security.getToken()]) }}
                {{ form.render('Versturen') }}
            </div>
            {{ endform }}
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