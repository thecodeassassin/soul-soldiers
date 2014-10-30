
<div class="row">
    <div class="col-md-12">
        <h4 class="title">Team naam aanpassen</h4>
        {{ form('admin/editTeamName/' ~ teamId, "method": "post", "name":"editTeamForm", "id":"editTeamForm", "class":"validate", "role":"form") }}

        <input type="text" name="teamName" class="form-control" value="{{ team.name }}">
        {{ endform() }}

    </div>
</div>
