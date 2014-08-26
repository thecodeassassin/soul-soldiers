
<div class="row">
    <div class="col-md-12">
        <h1>Beschikbare lijsten</h1>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Beschrijving</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            {% for list,desc in availableLists %}
            <tr>
                <td>{{ list }}</td>
                <td>{{ desc }}</td>
                <td><a href="{{ url('/admin/lists/' ~ list) }}" class="btn btn-primary"><i class="icon-download-cloud"></i> </a> </td>
            </tr>
            {% endfor %}
            </tbody>
        </table>
    </div>
</div>
