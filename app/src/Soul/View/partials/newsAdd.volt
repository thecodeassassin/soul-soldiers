<div class="newsAdmin">
    {{ form('news/add', "method": "post", "name":"additem", "class":"validate", "role":"form") }}
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
    {{ newsaddform.render('Aanmaken') }}
    {{ endform() }}
</div>