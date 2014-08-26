<div class="row">

    <div class="col-md-12">
        <h2>Gebruikers administratie</h2>

        <form action="{{ url('admin/users' ) }}" method="post">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon"><i class="icon-search"></i> </div>
                    <input class="form-control" type="search" name="search" placeholder="Search" value="{{ search }}">
                </div>
            </div>

        </form>

        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Naam</th>
                    <th>Nickname</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            {% for user in users %}
                <tr>
                    <td>{{ user.userId}}</td>
                    <td>{{ user.realName}}</td>
                    <td>{{ user.nickName}}</td>
                    <td>{{ user.email }}</td>

                    {#<td>#}
                        {#{% if entry.payment and entry.payment.confirmed %}#}
                            {#<span class="label label-success">Ja</span>#}
                        {#{% else %}#}
                            {#<span class="label label-warning">Nee</span>#}
                        {#{% endif %}#}
                    {#</td>#}
                    <td>
                        <a class="btn btn-primary" href="{{ url('admin/edituser/' ~ user.userId) }}"><i class="icon-edit"></i> </a>
                        <a href="{{ url('admin/deleteuser/' ~  user.userId) }}">
                            <button type="button" class="btn btn-danger" onclick="return confirm('Zeker weten?')"><i class="icon-trash"></i></button>
                        </a>
                    </td>
                </tr>
            {% else %}
                <tr>
                    <td colspan="3">Geen gebruikers gevonden</td>
                </tr>
            {% endfor %}
            <tr>
                <td colspan="5">
                    <p class="text-center">
                        Totaal aantal gebruikers: {{ users|length }}
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
