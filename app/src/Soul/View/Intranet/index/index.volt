{% set pageTitle = '<i class="icon-home"></i>Home' %}

<div class="row">
    <div class="col-md-12">
        <div class="gutter well">
            <h4>Welkom op het intranet van Soul-Soldiers 2014!</h4>


            <p>
                Op het intranet kun je je inschrijven voor toornooien, games voor de toernooien downloaden en het laatste nieuws lezen.
            </p>

        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-8">
        <div class="gutter well">
            <h4>Nieuws</h4>

            {{ partial("../partials/news") }}
        </div>
    </div>

    <div class="col-md-4">
        <div class="gutter well">
            <h4><span class="icon-help"></span> Help</h4>
        </div>
    </div>
</div>
