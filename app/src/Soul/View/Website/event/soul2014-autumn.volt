<div class="jumbotron color0">
    <div class="container">
        <h1>Soul-Soldiers 2014: Autumn Edition</h1>

        <p>
            Dit evenement was het 3e success op een rij. We hebben flink geinvesteerd in het professionaliseren van ons <br />
            netwerk en diensten. We hoorde vanuit de deelnemers dat dit een grote verbetering was ten opzichte van de vorige LAN. <br />
            Natuurlijk gaan we gewoon weer in 2015 verder met het organiseren van een van de leukste LAN-Parties van Nederland!
        </p>
    </div>
</div>
<div class="row color0">
    <div class="col-md-6 col-md-offset-3 pb15 pt15">
        <h2>Media</h2>
        <div class="imgHover clearfix portfolioMosaic mosaic5">
            {% for img in media['images'] %}
                <article>
                    <figure class="minimalBox">
                        <a href="{{ url(img['url']) }}" class="image-link">
                            {{ image(img['thumb'], "class" : "img-responsive") }}
                        </a>
                    </figure>
                </article>
            {% else %}
                <span>Er is nog geen media voor dit evenement beschikbaar</span>
            {% endfor %}
        </div>
    </div>
</div>