<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h1>Wachtwoord vergeten</h1>
            <div class="panel panel-primary">

                <div class="panel-body login-panel">
                    {{ form('forgot-password', "method": "post", "name":"forgotPassword", "class":"form-horizontal", "role":"form") }}
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon icon-mail">&nbsp;</span>
                            {{ form.render('email') }}
                        </div>
                    </div>

                    {{ form.render('Nieuw wachtwoord opvragen') }}

                    {{ form.render('csrf', ['value': security.getToken()]) }}
                    </form>
                </div>
                <div class="panel-footer login-footer">

                    <div class="row">
                        <div class="col-xs-7">
                            <span class="icon-lock"></span> <a href="{{ url('login') }}"> Inloggen</a>
                        </div>
                        <div class="col-xs-5">
                            <span class="icon-user-1"></span> <a href="{{ url('register') }}"> Registeren</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>