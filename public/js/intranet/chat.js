(function (){

    ajaxLoad(true);
    
    var conn = null;
    var nickname = __CHAT_USER_NICKNAME;
    var realName = __CHAT_USER_NAME;
    var imageHash = __CHAT_IMAGE_HASH;
    var token = null;
    var notifyEnabled = false;
    
    setupWebSocket();
    
    // check if the chat is working
    setTimeout(function() {
        if (conn.readyState !== 1) {
            alert('De chat is niet beschikbaar op dit moment :(');
            window.location = '/home';
        }
    }, 5000)
    
    var messages = [];
    var messages_template = Handlebars.compile($('#messages-template').html());
    var users_template = Handlebars.compile($('#users-template').html());
    
    registerHandlebarFn();
    
    conn.onopen = function(e) {
        
        setInterval(function() {
            conn.send(JSON.stringify({event: 'ping', author: nickname}))
        }, 10000)
        
        $('#chatinput').show();
        setTimeout(function() {
           $('#chatinput').focus();
           ajaxLoad(false);
           $(".soul-chat .chat-messages h5").show();
           $(".soul-chat .chat-people").show("slow");
           
        }, 50);
    };

    conn.onmessage = function(e) {
        var msg = JSON.parse(e.data);
        
        if (typeof msg.users !== 'undefined') {
            updateUsers(msg.users);
        }
        
        if (typeof msg.stored_messages !== 'undefined') {
            messages = msg.stored_messages;
            renderMessages();
        }
        
        if (typeof msg.userToken !== 'undefined') {
            token = msg.userToken;
            conn.send(JSON.stringify({user: { nickname: nickname, realName: realName, imageHash: imageHash }, token: token, event: 'join'}))
        }
        
        if (typeof msg.message !== 'undefined') {
            if (msg.author == 'system') {
                showSystemMessage(msg.message, msg.type)
            } else {
                updateMessages(msg);
            }
        }
    };
    
    conn.onclose = function(e) {
        
        // user left, reload the page
        window.location.reload(1);
    }
    
    
    $('#chatinput').keydown(function(event) {
        if (event.keyCode == 13) {
            var message = $(this).val();
            var dt = new Date();
            var time = addZero(dt.getHours()) + ":" + addZero(dt.getMinutes()) + ":" + addZero(dt.getSeconds());
            var msg = { message: message, author: nickname, imageHash: imageHash, time: time, token: token };
            
            conn.send(JSON.stringify(msg));
            // updateMessages(msg);
            $(this).val('');
            
            return false;
        }
    });
    
    $('#notify').click(function() {
       if ($(this).prop('checked') ) {
           console.log('Enable');
           
        //   if (!notifyEnabled) {
           if (!Notify.isSupported()) {
               alert('Notificaties werken niet in jou browser :(');
           } else {
           
               if (Notify.needsPermission) {
                    Notify.requestPermission(function() {
                        notifyEnabled = true;
                        console.log(notifyEnabled);
                    }); 
               } else {
                   notifyEnabled = true;
               }
           }
           
       } else {
           notifyEnabled = false;
       }
       
    });
    
    function setupWebSocket() {
        conn = new WebSocket(__CHAT_HOST);
    }

    function addMessage(message, author, time) {
        console.log('Received chat: ' + message);
    }
    
    function addZero(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }
    
    function updateMessages(msg) {
        messages.push(msg);
        renderMessages();
    }
    
    function renderMessages() {
        
        if (messages.length > 0) {
            var messages_html = messages_template({'messages': messages});
            $('#chat-messages').html(messages_html);
            $(".chat-messages").animate({ scrollTop: $('.chat-messages')[0].scrollHeight}, 1000);
        }
    }
    
    function registerHandlebarFn() {
        Handlebars.registerHelper('if_me', function(a, opts) {
            if (a == __CHAT_USER_NICKNAME) {
                return opts.fn(this);
            } else {
                return opts.inverse(this);
            }
        });
    }
    
    function showSystemMessage(message, type) {
        // $("#system-message").html(message);
        // $(".system-message").css({top: $('.chat-messages').scrollTop()})
        // $( ".system-message" ).show("slow");
        // setTimeout(function() {
        //     $( ".system-message" ).slideUp();
        // }, 6000)
        
        if (type == 'warning') {
            icon = '<i class="icon icon-attention"></i>';
        } else {
            icon = '<i class="icon icon-info-circled"></i>';
        }
        
        noty({text: icon + message, layout: 'topCenter', timeout: 5000, type: type});
        
    }
    
    function updateUsers(users) {
       var users_html = users_template({'users': users}); 
       $("#user-list").html(users_html);
       $("#usercount").html(users.length);
       
    }
    
})();