<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Inloggen</h3>
                </div>
                <div class="panel-body">
                    {{ form("account/login", "method": "post", "name":"login") }}
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">E-mail</label>
                            <div class="col-sm-10">
                                {{ email_field('email', "class":"form-control", "name":"email", "placeholder":"E-Mail") }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Wachtwoord</label>
                            <div class="col-sm-10">
                                {{ password_field('password', "class":"form-control", "name":"password", "placeholder":"Wachtwoord") }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"> Onthoud mij
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-default">Inloggen</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

