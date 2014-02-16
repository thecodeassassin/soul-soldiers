

{% if errorMessage %}

{% endif %}<section id="content" class="mt30 pb30 color2">
    <div class="container">
        <div class="row">

            {% if error['message'] %}
            <div class="col-md-6 col-md-offset-3">
                <h1>Error</h1>
                    <table class="table">
                        <thead>
                          <tr>
                            <th>Code</th>
                            <th>Message</th>
                          </tr>
                        </thead>
                        <tbody>
                           <tr>
                            <td>{{ error['code'] }}</td>
                            <td>{{ error['message']}}</td>
                           </tr>
                        </tbody>
                    </table>
            </div>
            {% endif %}

            <div class="col-md-6 col-md-offset-3">

                <article class="boxIcon text-center" style="opacity: 1;">

                    <i class="icon-unlink iconBig iconRounded"></i>
                    {% if code == 404 %}
                    <h1>Pagina niet gevonden</h1>
                    <p>De pagina die u zoekt bestaat niet (meer).</p>
                    {% else %}
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
</section>