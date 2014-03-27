<div class="row">
    <div class="container">
        <div class="col-md-12">

            <div class="jumbotron color0 lan-info">

                <h1>{{ event.name }}</h1>

                {{ event.details }}

                {% if user %}

                    {% if not registered %}
                        <p><a class="btn btn-primary btn-lg" id="registerEvent" eventName="{{ event.name }}" systemName="{{ event.systemName }}" role="button" href="{{ url('event/register' ~ event.systemName) }}">Schrijf je nu in!</a></p>
                    {% else %}

                        {% if not payed %}
                            <p><a class="btn btn-primary btn-lg" role="button" href="{{ url('event/pay/' ~ event.systemName) }}">
                                    <span class="icon-euro"></span>
                                    Entreeticket betalen
                                </a>
                            </p>
                        {% else %}
                            <p><a class="btn btn-primary btn-lg disabled" role="button" href="javascript:void(0)">Bedankt voor je betaling!</a></p>
                        {% endif %}


                    {% endif %}
                {% else %}
                    <p><a class="btn btn-primary btn-lg" role="button" href="{{ url('register') }}">Maak nu je account aan!</a></p>
                {% endif %}

            </div>

        </div>
    </div>
</div>