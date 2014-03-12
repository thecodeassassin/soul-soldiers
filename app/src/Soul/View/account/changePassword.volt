<div class="container">
    {{ form('change-password/' ~ confirmKey, "method": "post", "name":"login", "class":"form-horizontal validate", "role":"form") }}

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h1>Nieuw wachtwoord instellen</h1>
        </div>
    </div>

    <div class="row">
        <div class="well-large color0 col-md-6 col-md-offset-3">

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

            <div class="pull-right">
                {{ form.render('Opslaan') }}
            </div>

        </div>
    </div>
    {{ form.render('csrf', ['value': security.getToken()]) }}

    </form>
</div>