<div class="container">
    <div class="row pt30">
        <div id="messages" class="col-md-12">
            {{ flash.output() }}
            {{ flashSession.output() }}
        </div>
    </div>
</div>
<!-- Login Container -->
<div id="login-container">
    <div id="login-logo">
        <a href="">
            <img src="img/logo-only.png" alt="logo" width="100">
        </a>
        <h3>Intranet</h3>
    </div>

    {{ form('login', "method": "post", "name":"login", "class":"form-horizontal", "role":"form") }}
    <div class="form-group">
        <div class="col-xs-12">
            <div class="input-group">
                <span class="input-group-addon icon-mail">&nbsp;</span>
                {{ form.render('email') }}
            </div>
        </div>
        <div class="col-xs-12">
            <div class="input-group">
                <span class="input-group-addon icon-lock">&nbsp;</span>
                {{ form.render('password') }}
            </div>
        </div>
    </div>

    <div class="clearfix">
        <div class="btn-group btn-group-sm pull-right">
            {#<a href="{{ url('forgot-password') }}" class="btn btn-warning" data-toggle="tooltip" title="Forgot pass?"><i class="icon-lock"></i>Wachtwoord vergeten</a>#}
            <button type="submit" id="login" class="btn btn-success"><i class="icon-login"></i>Inloggen</button>

        </div>
    </div>

    {{ form.render('csrf', ['value': security.getToken()]) }}
    {{ endform() }}
    <!-- END Login Form -->
</div>
<!-- END Login Container -->
