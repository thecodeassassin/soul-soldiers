<div class="container">
    <div class="row">
        <div class="jumbotron color0 homepage">
            <h1>Soul-Soldiers 2015 is een feit!</h1>

            <p>
                Het Eiland, Bijnsdorp. 22, 23 en 24 Mei 2015, Book it! Soul-Soldiers <br />
                geeft de leukste LAN-Party's in de regio! We organiseren ook dit jaar weer een <br />
                nieuw gezellig LAN Evenement. Unreal Tournament 2004: Instagib zal natuurlijk weer <br />
                een kijharde comeback maken als toernooi en natuurlijk spelen zoals elk jaar de games die <strong>jullie</strong> leuk vinden. <br />
                <br />
                Tournament gaming met prijzen en een fantastische sfeer, waar wacht je nog op? <br /><br />
                Ga naar de <a href="{{ url('event/current') }}">evenements </a> pagina en schrijf je vandaag nog in!
            </p>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8 carousel-parent">
            <div class="gutter">
                <div class="index-caroussel">
                    <div class="p40">
                        <h3>dfs</h3>
                        <p>
                            42dfjsdfjsdfbdsfsmdfnsdf <br />
                            fgdgfdgkndfjgnb
                        </p>
                    </div>
                    <div class="p40">
                        <h3>dfsd234524</h3>
                        <p>
                            dfjsdfjsdfbdsfsmdfnsdf <br />
                            fgdgfdgkndfjgnb
                        </p>
                    </div>
                    <div class="p40">
                        <h3>sdfsdf34</h3>
                        <p>
                            dfjsdfjsdfbdsfsmdfnsdf <br />
                            fgdgfdgkndfjgnb
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 right-container">
            <div class="gutter">
                <h2>Tweets</h2>
                <div class="index-news-wrapper">
                    <div class="index-news">
                        {% for item in twitterPosts %}
                            <div>
                                <div class="news panel">
                                    <div class="panel-heading">
                                        <span class="news-title">{{ item['date'] }}</span>
                                        {#<div class="pull-right news-date">{{ item.published }}</div>#}
                                    </div>
                                    <div class="panel-body news-body">
                                        {{ item['text'] }}
                                    </div>
                                </div>
                            </div>
                        {% endfor %}
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
