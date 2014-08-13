<div class="row">
    <div class="container">
        <h1>Forum</h1>

        <div class="col-md-4 color0 pb15 pt15">
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
</div>
