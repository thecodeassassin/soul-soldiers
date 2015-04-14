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