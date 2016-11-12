{% extends 'layout.volt' %}

{% block pageTitle %}<i class="icon-chat"></i>&nbsp; Chat {% endblock %}
{% block content %}
<script type="text/javascript">
__CHAT_HOST = '{{ chatHost }}';
__CHAT_USER_NICKNAME = '{{ user.getNickName() }}';
__CHAT_USER_NAME = '{{ user.getRealName() }}';
__CHAT_IMAGE_HASH = '{{ chatImageHash }}';
</script>

{% if user and user.userType == 3 %}
{% endif %}


<div class="chat-container soul-chat clearfix nano">
    <div class="chat-people nano-content">
        <ul>
          <li class="active">
                <div>
                <span class="chat-name">Soul-Soldiers LAN Chat</span><br>
                <span class="chat-info"><span id="usercount"></span> gebruikers</span>
                <!-- <span class="chat-notify">
                    <label><input type="checkbox" id="notify"><span>Notificaties</span></label>
                </span> -->
                </div>
            </li>
        </ul>
        <ul id="user-list">
       
        </ul>
    </div>
    <div class="chat-messages nano-content">
        <div class="system-message">
            <i class="icon icon-info-circled"></i>
            <span  id="system-message"></span>
        </div>
        <ul id="chat-messages">
            <h5>Nog geen chat berichten :(</h5>
        </ul>
    </div>
    <div class="chat-input">
        <div class="chat-input-inner">
            <input type="text" maxlength="300" id="chatinput" class="no-resize-bar form-control" placeholder="Typ hier je bericht en druk op enter om te versturen"></textarea>
        </div>
    </div>
</div>

<script id="messages-template" type="text/x-handlebars-template">
    {{'{{'}}#each messages{{'}}'}}
    
    <li class="{{'{{'}}#if_me author {{'}}'}}chat-msg-left{{'{{'}}else{{'}}'}}chat-msg-right{{'{{'}}/if_me{{'}}'}}">
        <span class="label chat-msg-time"><em>{{'{{'}}time{{'}}'}}</em> / <em>{{'{{'}}author{{'}}'}}</em></span>
        <img src="https://www.gravatar.com/avatar/{{'{{'}}imageHash{{'}}'}}?s=64&r=x&default=https%3A%2F%2Fsoul-soldiers.nl%2Fimg%2Fgravatar_default.jpg" alt="avatar"/>
        <p>{{'{{{'}}message{{'}}}'}}</p>
    </li>
    {{'{{'}}/each{{'}}'}}
</script>


<script id="users-template" type="text/x-handlebars-template">
 {{'{{'}}#each users{{'}}'}}
   <li>
        <div data-placement="left" title="">
         <img src="https://www.gravatar.com/avatar/{{'{{'}}imageHash{{'}}'}}?s=32&r=x&default=https%3A%2F%2Fsoul-soldiers.nl%2Fimg%2Fgravatar_default_32.jpg" alt="avatar"/>
        <span class="chat-name">{{'{{'}}nickname{{'}}'}}</span><br>
        <span class="chat-info">{{'{{'}}realName{{'}}'}}</span>
        </div>
    </li>    
 {{'{{'}}/each{{'}}'}}
</script>


{% endblock %}
