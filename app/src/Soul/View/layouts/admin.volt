
<div class="row">
    <div class="container admin-container">
        <h1>Admin panel</h1>
        <div class="col-md-3 color0 pb15 pt15">
            <ul class="nav nav-pills nav-stacked">
                <li {% if page is 'index' %} class="active" {% endif %}>
                    <a href="{{ url('admin') }}">
                        Dashboard
                    </a>
                </li>
                <li {% if page is 'massmail' %} class="active" {% endif %}>
                    <a href="{{ url('admin/massmail') }}">
                        Mass mail
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-md-9 color0 pb15 pt15">
            {{ content() }}
        </div>
    </div>
</div>

