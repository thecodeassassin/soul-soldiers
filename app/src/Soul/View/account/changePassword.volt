<div class="container">
    {{ form('register', "method": "post", "name":"login", "class":"form-horizontal validate", "role":"form") }}
    <h1>Nieuw wachtwoord instellen</h1>
    <div class="row">
        <div class="well-large color0 col-md-6">

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

            {{ form.render('Registeren') }}

        </div>
    </div>

    </form>
</div>