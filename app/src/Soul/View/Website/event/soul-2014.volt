<div class="jumbotron color0">
    <div class="container">
        <h1>Soul-Soldiers 2014</h1>

        <p>
            Soul-Soldiers 2014 was het 2e evenement dat wij hebben gehouden in de Stip in Nieuw-Vennep. <br />
            Het evenement was opnieuw een succes. Er was een barbeque, de toernooieen liepen op schemta en <br />
            er werd natuurlijk volop gegamed. We zijn trots dat we dit evenement met de deelnemers hebben mogen meemaken.
        </p>
    </div>
</div>
<div class="row color0">
    <div class="{% if archived %} col-md-6 col-md-offset-3 {% else %}col-md-4 col-md-offset-1 {% endif %} pb15 pt15">
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