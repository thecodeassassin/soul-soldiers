{% set pageTitle = '<i class="icon-gauge"></i> Admin' %}

<div class="row">
    <div class="col-md-12">
        <div class="gutter well">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#home" role="tab" data-toggle="tab">Dashboard</a></li>
                <li><a href="#tournaments" role="tab" data-toggle="tab">Toernooien</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="home">
                    <h3>Nog niets hier</h3>
                </div>
                <div class="tab-pane" id="tournaments">{% include 'admin/tournaments.volt' %}</div>

            </div>
        </div>
    </div>

</div>