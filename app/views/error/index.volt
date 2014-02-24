<div class="container">
    <div class="row">

        {% if error['message'] %}
        <div class="col-md-6 col-md-offset-3">
            <div class="alert alert-danger">Error {{ error['code'] }}: {{ error['message']}}</div>
        </div>
        {% endif %}

        <div class="col-md-6 col-md-offset-3">

            <article class="boxIcon text-center" style="opacity: 1;">

                {% if code == 404 %}
                <i class="icon-unlink iconBig iconRounded"></i>
                <h1>Pagina niet gevonden</h1>
                <p>De pagina die u zoekt bestaat niet (meer).</p>
                {% elseif code == 403 %}
                <i class="icon-frown iconBig iconRounded"></i>
                <h1>Geen toegang</h1>
                <p>U heeft geen toegang tot deze pagina.</p>
                {% else %}
                    <i class="icon-bug iconBig iconRounded"></i>
                    <h1>Er is een fout opgetreden</h1>
                    <p>
                        Er is iets onverwachts mis gegaan, onze excuses voor het ongemak.
                        <br />
                        {% if error['code'] %}
                            U kunt de volgende code doorgeven als u contact met ons opneemt: {{ error['code'] }}.
                        {% endif %}

                    </p>
                {% endif %}



                <a href="javascript:history.back()" class="btn btn-lg btn-primary mt15">{{ 'Terug naar vorige pagina' }}</a>
            </article>

        </div>

    </div>
</div>
