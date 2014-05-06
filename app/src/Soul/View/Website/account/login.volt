<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h1>Inloggen</h1>
            <div class="panel panel-primary">

                <div class="panel-body login-panel">
                    {{ form('login', "method": "post", "name":"login", "class":"form-horizontal", "role":"form") }}
                    <div class="form-group">
                        {#<label for="inputEmail3" class="col-sm-2 control-label">E-mail</label>#}
                        <div class="input-group">
                            <span class="input-group-addon icon-mail">&nbsp;</span>
                            {{ form.render('email') }}
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon icon-lock">&nbsp;</span>
                            {{ form.render('password') }}
                        </div>
                    </div>

                    {{ form.render('Inloggen') }}

                    {{ form.render('csrf', ['value': security.getToken()]) }}
                    {{ endform() }}
                </div>
                <div class="panel-footer login-footer">

                    <div class="row">
                        <div class="col-xs-7">
                            <span class="icon-lock-open-alt"></span> <a href="{{ url('forgot-password') }}"> Wachtwoord vergeten?</a>
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