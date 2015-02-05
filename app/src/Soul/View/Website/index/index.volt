<div class="container">
    <div class="row">
        <div class="jumbotron color0 homepage">
            <h1>Welkom bij Soul-Soldiers!</h1>

            <p>
                Wij organiseren sinds 2007 LAN-party's met als enige doel: zo veel mogelijk fun! <br><br>

                Wij hebben geen commerciële doelstelling en onze specialisatie is het organiseren <br>
                van kleinschalige maar vooral gezellige LAN-party's.
            </p>
        </div>
    </div>


    <div class="row mb30">
        <div class="col-md-8 carousel-parent color0">
            <div class="index-caroussel">
                <div class="p40 slide1">
                    <div class="text">
                        <h3>Soul-Soldiers 2015 is een feit!</h3>

                        <p>
                            Het Eiland, Bijnsdorp. 22, 23 en 24 Mei 2015, Book it! Soul-Soldiers geeft de leukste
                            LAN-Party's in de regio! <br/>
                            We organiseren ook dit jaar weer een nieuwe gezellige LAN-Party. <br/>Unreal Tournament
                            2004: Instagib
                            zal natuurlijk weer een kijharde comeback maken als toernooi en natuurlijk spelen zoals elk
                            jaar de games die <strong>jullie</strong> leuk vinden. <br/>
                            <br/>
                            Tournament gaming met prijzen en een fantastische sfeer, waar wacht je nog op? <br/><br/>
                            Ga naar de <a href="{{ url('event/current') }}">evenements </a> pagina en schrijf je vandaag
                            nog in!
                        </p>
                    </div>
                </div>
                {#<div class="p40 slide2">#}
                {##}
                {#</div>#}
            </div>

        </div>
        <div class="col-md-4 right-container">
            <div class="gutter">
                <h2>Tweets</h2>

                <div class="index-news-wrapper">
                    <div class="index-tweet-container">
                        {% for item in twitterPosts %}
                            <div>
                                <div class="news panel">
                                    <div class="panel-heading">
                                        <span class="news-title">{{ item.date }}</span>
                                        {#<div class="pull-right news-date">{{ item.published }}</div>#}
                                    </div>
                                    <div class="panel-body news-body">
                                        {{ item.text }}
                                    </div>
                                </div>
                            </div>
                        {% endfor %}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="jumbotron color0">
            <div class="gutter color0">
                <h3>Nieuws</h3>

                {{ partial("../partials/news") }}
            </div>
        </div>
    </div>
</div>