{% set pageTitle = '<i class="icon-gauge"></i> <a href="'~url('admin/index')~'">Admin</a> / <a href="'~url('admin/tournaments')~'">Toernooien</a> / Nieuw toernooi' %}

<div class="row">
    <div class="col-md-6">
        <div class="well gutter">
           {% include 'admin/partials/tournamentForm.volt' %}
        </div>
    </div>
</div>
