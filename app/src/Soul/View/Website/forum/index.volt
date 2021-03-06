<div class="container">
    <div class="row">
        <h1>Forum</h1>

        <div class="col-md-3 pb15 pt15 forum-container">
            <div class="gutter color0">
                <h2>Categorieen</h2>

                <div class="list-group" id="forum-categories">
                    {% for category in categories %}
                        <a href="#" class="list-group-item" data-category-id="{{ category.categoryId }}">
                            {{ category.name }}
                        </a>
                    {% endfor %}
                </div>
            </div>
        </div>

        <div class="col-md-9 pb15 pt15 forum-container">
            <div class="gutter color0">
                <noscript>
                    <h4>Ons forum is afhankelijk van javascript. Om het forum te kunnen gebruik dien je javascript aan te zetten.</h4>
                </noscript>

                <div class="mb15">
                    <button class="btn btn-primary"><i class="icon-plus-circled"></i> Nieuw onderwerp</button>
                </div>

                <table class="table" id="topics">
                    <thead>
                        <tr>
                            <th class="col-md-3">Onderwerp</th>
                            <th class="col-md-1"># Bekeken</th>
                            <th class="col-md-2">Gepost</th>
                            <th class="col-md-2">Laatste post</th>
                        </tr>
                    </thead>
                    <tbody>
                    {% include 'forum/topics.volt' %}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
