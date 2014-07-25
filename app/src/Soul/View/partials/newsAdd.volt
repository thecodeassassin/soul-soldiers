<div class="newsAdmin add">
    {{ form('news/add', "method": "post", "name":"additem") }}
    <div class="form-group">
        <label for="title" class="control-label">Titel *</label>
        <div class="noaddon">
            {{ newsaddform.render('title') }}
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="control-label">Content *</label>
        <div class="noaddon">
            {{ newsaddform.render('body') }}
        </div>
    </div>
    <div class="form-group">
        {{ newsaddform.render('Aanmaken') }}
    </div>
    {{ endform() }}
</div>