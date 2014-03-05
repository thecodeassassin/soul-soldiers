<script type="text/javascript">
    $(".validate").validate();
</script>

<div class="container">
    {{ form('register', "method": "post", "name":"login", "class":"form-horizontal validate", "role":"form") }}
    <h1>Registeren</h1>
    <div class="row">
        <div class="well-large color0 col-md-12">
            <div class="col-md-6">

                <h2>Account gegevens</h2>

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-5 control-label">E-mail *</label>
                    <div class="col-sm-7">
                        {{ form.render('email') }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-5 control-label">Volledige naam *</label>
                    <div class="col-sm-7">
                        {{ form.render('realName') }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-5 control-label">Nickname (bijnaam) *</label>
                    <div class="col-sm-7">
                        {{ form.render('nickName') }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-5 control-label">Wachtwoord *</label>
                    <div class="col-sm-7">
                        {{ form.render('password') }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-5 control-label">Wachtwoord (opnieuw) *</label>
                    <div class="col-sm-7">
                        {{ form.render('passwordRepeat') }}
                    </div>
                </div>

                <br />
                <span>Velden gemarkeerd met een * zijn verplicht.</span>

            </div>
            <div class="col-md-6">

                <h2>Persoons gegevens</h2>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-5 control-label">Telefoonnummer</label>
                    <div class="col-sm-7">
                        {{ form.render('telephone') }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-5 control-label">Adres</label>
                    <div class="col-sm-7">
                        {{ form.render('address') }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-5 control-label">Postcode</label>
                    <div class="col-sm-7">
                        {{ form.render('postalCode') }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-5 control-label">Woonplaats</label>
                    <div class="col-sm-7">
                        {{ form.render('city') }}
                    </div>
                </div>

                <br />
                <div class="checkbox pull-right">
                    {{ form.render('terms') }} Ik ga akkoord met de algemene voorwaarden
                </div>
                <br />
                <br />
                {{ form.render('csrf', ['value': security.getToken()]) }}
                <div class="register-submit">
                    {{ form.render('Registeren') }}
                </div>
            </div>
        </div>
    </div>

    </form>
</div>