{% extends 'layout.volt' %}

{% block body %}
    <div class="container">
        <div class="row pt30">
            <div id="messages" class="col-md-12">
                {{ flash.output() }}
                {{ flashSession.output() }}
            </div>
        </div>
    </div>
    <!-- Login Container -->
    <div id="login-container" class="col-md-2">
        <div id="login-logo">
            <a href="">
                <img src="img/main-logo.png" alt="logo">
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
                <button type="button" id="login-button-pass" class="btn btn-warning" data-toggle="tooltip" title="Forgot pass?"><i class="icon-lock"></i>Wachtwoord vergeten</button>
                {{ form.render('Inloggen') }}
            </div>
        </div>

        {{ form.render('csrf', ['value': security.getToken()]) }}
        {{ endform() }}
        <!-- END Login Form -->
    </div>
    <!-- END Login Container -->
{% endblock %}