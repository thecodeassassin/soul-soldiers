{% set price = '%01.2f'|format(event.product.cost) %}
<div class="row color0">
    <div class="col-md-12">
        <div class="container pt30 pb30">
            <h1>Entreeticket voor {{ event.name }}</h1>

            <div class="pull-left">
                <span class="price">Prijs: &euro; {{ price }}</span>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <hr class="lineLines">
                </div>
            </div>

            <div class="row">
                {{ image('img/ideal-logo.png',  "class":"img-responsive pull-right", "width":"100px", "height":"40px") }}
                <div class="col-md-4 col-md-offset-4">
                    {#<div class="container">#}
                    <h3>Betalen met iDeal</h3>

                    <p>
                        Je kunt bij ons gemakkelijk je entreeticket betalen met iDeal.
                        Tevens kun je er voor kiezen om het buffet op zaterdag bij te wonen.
                    </p>
                    {{ form('event/pay/' ~ event.systemName, "method": "post", "name":"payment", "class":"form-inline validate", "role":"form") }}

                    <div class="mt15 mb30">
                        {% if dinerAvailable %}
                        <div class="checkbox form-group">
                            <label data-toggle="tooltip" data-placement="top" title="Het eten is altijd een huisgemaakte maaltijd bereid door de crew van Soul-Soldiers.">
                                <input type="checkbox" value="yes" class="input-control" name="dinner_option" />
                                <strong> Avondeten op zaterdagavond (&euro; {{ '%01.2f'|format(dinnerPrice) }} extra)</strong>
                            </label>
                        </div>
                        {% else %}
                        <strong><i class="icon-attention-circle" style="color: yellow;"></i> Avondeten optie niet langer beschikbaar</strong>
                        {% endif %}
                    </div>


                    <div class="input-group">
                        <span class="input-group-addon icon-credit-card">&nbsp;</span>
                        <select id="issuer" name="issuer" class="form-control" required>
                            <option value="">Selecteer uw bank</option>
                            {% for issuer in issuers %}
                                <option value="{{ issuer['id'] }}">{{ issuer['name'] }}</option>
                            {% endfor %}
                        </select>
                    </div>

                    <div class="mt15">
                        <button type="submit" class="btn btn-primary btn-block">Start iDeal betaling</button>
                    </div>
                    {{ endform() }}
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <hr class="lineLines">
                </div>
            </div>

            <div class="row">
                <div class="col-md-5 col-md-offset-4">
                    <h3>Zelf overmaken</h3>

                    <p>
                        Je kunt het totaalbedrag (&euro; {{ price }}) ook naar ons overmaken.
                        <br />
                        <br />
                        Betaalgegevens:
                        <br /><br />
                        - IBAN:  NL63 INGB 0003 7831 47 <br />
                        - T.n.v Soul-Soldiers te Hoofddorp <br />
                        - Omschrijving: Betaling {{ user.getRealName() }} - {{ event.name }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
