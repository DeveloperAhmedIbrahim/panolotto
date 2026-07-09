/* Variables */
var user;
var timer;
var socket;
var oldname;
var username;
var typeTimer;
var clients = [];
var usersTyping = [];
var nmr = 0;
var dev = true;
var key=null;
var unread = 0;
var focus = true;
var typing = false;
var connected = false;
var version = '1.0.6';

var blop = '';
var regex = /(&zwj;|&nbsp;)/g;

var settings = {
    'name': null,
    'emoji': true,
    'greentext': true,
    'inline': true,
    'sound': true,
    'desktop': false,
    'synthesis': false,
    'recognition': false
};


/* Config */
emojione.ascii = true;
emojione.imageType = 'png';
emojione.unicodeAlt = false;

var wpstream_close_socket=function(){
     socket.close();
};
/* Connection */
var connect = function(chat_uri) {
    var reject=false;
    
    if(chat_uri==='' || chat_uri==='undefined'){
        message=chat-js-vars.we_are_not_live;
        //showChat('light', null, message);
        reject=true;
    }
    
    if(username===''){
        message="Please login if you want to use the chat.";
        showChat('light', null, message);
        reject=true;
    }
 
    var protocol;
    protocol = 'wss://';
    if(chat_uri!==''){
        socket = new WebSocket(chat_uri);
        socket.addEventListener('error', function (event) {
            //message="We are not live at this moment.Please check back later.";
            message=chat-js-vars.we_are_not_live;
            showChat('light', null, message);
            reject=true;
        });
    }


    if(reject===true){
        return;
    }
    
    

    socket.error =function(){
      console.log('socket error');  
    };
 
    socket.onopen = function() {
        var ping = setInterval(function(){
            socket.send(JSON.stringify({type: 'ping'}));
        }, 50 * 1000);

        updateInfo();
    };

    socket.onclose = function() {
        clearTimeout(typeTimer);
        jQuery('#admin').hide();
        typing = false;
        clients = [];

        if(connected) {
            updateBar('mdi-action-autorenew spin', 'Connection lost, reconnecting...', true);

            timer = setTimeout(function() {
                console.warn('Connection lost, reconnecting...');
                connect();
            }, 1500);
        }
    };

    socket.onmessage = function(e) {
        var data = JSON.parse(e.data);
        
        
        if(data.type == 'delete') {
            return jQuery('div[data-mid="' + data.message + '"]').remove();
        }

        if(data.type == 'typing') {
            var string;
            if(data.user != username) {
                if(data.typing) {
                    usersTyping.push(data.user);
                } else {
                    usersTyping.splice(usersTyping.indexOf(data.user), 1);
                }
            }

            
            if(usersTyping.length == 1) {
                string = usersTyping + ' is writing...';
            } else if(usersTyping.length > 4) {
                string = 'Several people are writing...';
            } else if(usersTyping.length > 1) {
                var lastUser = usersTyping.pop();
                string = usersTyping.join(', ') + ' and ' + lastUser + ' are writing...';
                usersTyping.push(lastUser);
            } else {
                string = '<br>';
            }

            return document.getElementById('typing').innerHTML = string;
        }

        if(data.type == 'server') {
            switch(data.info) {
                case 'rejected':
                    var message;

                    if(data.reason == 'length') {
                        message = 'Your username must have at least 3 characters and no more than 16 characters';
                    }

                    if(data.reason == 'format') {
                        message = 'Your username must only contain alphanumeric characters (numbers, letters and underscores)';
                    }

                    if(data.reason == 'taken') {
                        message = 'This username is already taken';
                    }

                    if(data.reason == 'banned') {
                        message = 'You have been banned from the server for ' + data.time / 60 / 1000 + ' minutes. You have to wait until you get unbanned to be able to connect again';
                    }

                    showChat('light', null, message);

                    if(!data.keep) {
                        username = undefined;
                        connected = false;
                    } else {
                        username = oldname;
                    }
                    break;

                case 'success':
                    document.getElementById('send').childNodes[0].nodeValue = 'Send';
                    updateBar('mdi-content-send', 'Enter your message here', false);
                    connected = true;
                    settings.name = username;
                    localStorage.settings = JSON.stringify(settings);
                    break;

                case 'update':
                    showChat('info', null, data.user.oldun + ' changed its name to ' + data.user.un);
                    clients[data.user.id] = data.user;
                    break;

                case 'connection':
                    var userip = data.user.ip ? ' [' + data.user.ip + ']' : '';
                    showChat('info', null, data.user.un + userip + ' connected to the server');

                    clients[data.user.id] = data.user;
                    document.getElementById('users').innerHTML = Object.keys(clients).length + ' USERS';
                    break;

                case 'disconnection':
                    var userip = data.user.ip ? ' [' + data.user.ip + ']' : '';

                    if(data.user.un != null) {
                        showChat('info', null, data.user.un + userip + ' disconnected from the server');
                    }

                    delete clients[data.user.id];
                    document.getElementById('users').innerHTML = Object.keys(clients).length + ' USERS';
                    break;

                case 'spam':
                    showChat('global', null, 'You have to wait 1 second between messages. Continuing on spamming the servers may get you banned. Warning ' + data.warn + ' of 5');
                    break;

                case 'clients':
                    clients = data.clients;
                    document.getElementById('users').innerHTML = Object.keys(clients).length + ' USERS';
                    break;

                case 'user':
                    user = data.client.id;
                    break;
            }
        } else if((data.type == 'kick' || data.type == 'ban') && data.extra == username) {
            location.reload();
        } else {
            if(data.message.indexOf('@' + username) > -1) {
                data.type = 'mention';
            }

            if(settings.synthesis) {
                textToSpeech.text = data.message;
                speechSynthesis.speak(textToSpeech);
            }
           
            showChat(data.type, data.user, data.message, data.subtxt, data.mid);
        }

        if(data.type == 'role') {
            if(getUserByName(data.extra) != undefined) {
                if(data.extra == username && data.role > 0) {
                    jQuery('#admin').show();
                    jQuery('#menu-admin').show();
                }

                if(data.extra == username && data.role == 0) {
                    jQuery('#admin').hide();
                    jQuery('#menu-admin').hide();
                }

                clients[getUserByName(data.extra).id].role = data.role;
            }
        }

        if(data.type == 'global' || data.type == 'pm' || data.type == 'mention') {
            if(!focus) {
                unread++;
                if(settings.sound) {
                    blop.play();
                }

                if(settings.desktop) {
                    desktopNotif(data.user + ': ' + data.message);
                }
            }
        }
    }
};


/* Functions */
function sendSocket(value, method, other, txt) {
    socket.send(JSON.stringify({
        type: method,
        message: value,
        subtxt: txt,
        extra: other
    }));

    jQuery('#message').focus();
}

function updateInfo() {
    socket.send(JSON.stringify({
        user: username,
        type: 'update',
        key:key
    }));
}

function getUserByName(name) {
    for(client in clients) {
        if(clients[client].un == name) {
            return clients[client];
        }
    }
}






function updateBar(icon, placeholder, disable) {
//    document.getElementById('icon').className = 'mdi ' + icon;
    jQuery('#message').attr('placeholder', placeholder);
    jQuery('#message').prop('disabled', disable);
    jQuery('#send').prop('disabled', disable);
}

function showChat(type, user, message, subtxt, mid) {
    var nameclass = '';

    if(type == 'global' || type == 'kick' || type == 'ban' || type == 'info' || type == 'light' || type == 'help' || type == 'role') {
        user = 'System';
    }

    if(type == 'me' || type == 'em') {
        type = 'emote';
    }

    if(!mid) {
        mid == 'system';
    }

    if(type == 'emote' || type == 'message') {
        if(user == username && getUserByName(user).role == 0) {
            nameclass = 'self';
        } else {
            if(getUserByName(user).role == 1) nameclass = 'helper';
            if(getUserByName(user).role == 2) nameclass = 'moderator';
            if(getUserByName(user).role == 3) nameclass = 'administrator';
        }
    }

    if(!subtxt) {
        jQuery('#panel').append('<div data-mid="' + mid + '" class="' + type + '""><span class="name ' + nameclass + '"><b><a class="namelink" href="javascript:void(0)">' + user + ':</a></b></span><span class="delete"><a href="javascript:void(0)">DELETE</a></span><span class="timestamp">' + getTime() + '</span><span class="msg">' + message + '</span></div>');
    } else {
        jQuery('#panel').append('<div data-mid="' + mid + '" class="' + type + '""><span class="name ' + nameclass + '"><b><a class="namelink" href="javascript:void(0)">' + user + ':</a></b></span><span class="timestamp">(' + subtxt + ') ' + getTime() + '</span><span class="msg">' + message + '</span></div>');
    }
    
    jQuery('#panel').animate({scrollTop: jQuery('#panel').prop('scrollHeight')}, 500);
    updateStyle();
    jQuery('#message').focus();
    
    nmr++;
    
    if(settings.inline) {
        var m = message.match(/(https?|ftp):\/\/[^\s/jQuery.?#].[^\s]*/gmi);

        if(m) {
            m.forEach(function(e, i, a) {
                // Gfycat Support
                if(e.indexOf('//gfycat') !== -1) {
                    var oldUrl = e;
                    e = e.replace('//gfycat.com', '//gfycat.com/cajax/get').replace('http://', 'https://');

                    jQuery.getJSON(e, function(data) {
                        testImage(data.gfyItem.gifUrl.replace('http://', 'https://'), mid, oldUrl);
                    });
                } else {
                    testImage(e, mid, e);
                }
            });
        }
    }
}

function testImage(url, mid, oldUrl) {
    var img = new Image();

    img.onload = function() {
        jQuery('div[data-mid=' + mid + '] .msg a[href="' + oldUrl.replace('https://', 'http://') + '"]').html(img);
        jQuery('#panel').animate({scrollTop: jQuery('#panel').prop('scrollHeight')}, 500);
    };

    img.src = url;
}


function handleInput() {
    var value = jQuery('#message').val().replace(regex, ' ').trim();
    jQuery('#message').focus();
    if(value.length > 0) {
     
        if(username === undefined) {
//            username = value;
//            connect();
        } else if(value.charAt(0) == '/') {
            var command = value.substring(1).split(' ');

            switch(command[0].toLowerCase()) {
                case 'pm': case 'msg': case 'role': case 'kick': case 'ban': case 'name': case 'alert': case 'me': case 'em':
                    if(value.substring(command[0].length).length > 1) {
                        if((command[0] == 'pm' || command[0] == 'msg') && value.substring(command[0].concat(command[1]).length).length > 2) {
                            sendSocket(value.substring(command[0].concat(command[1]).length + 2), 'pm', command[1], 'PM');
                        } else if(command[0] == 'pm' || command[0] == 'msg') {
                            showChat('light', 'Error', 'Use /' + command[0] + ' [user] [message]');
                        }

                        if(command[0] == 'ban' && value.substring(command[0].concat(command[1]).length).length > 2) {
                            sendSocket(command[1], 'ban', command[2]);
                        } else if(command[0] == 'ban') {
                            showChat('light', 'Error', 'Use /ban [user] [minutes]');
                        }

                        if(command[0] == 'alert') {
                            sendSocket(value.substring(command[0].length + 2), 'global', null, username);
                        }

                        if((command[0] == 'role') && value.substring(command[0].concat(command[1]).length).length > 3) {
                            sendSocket(command[1], 'role', value.substring(command[0].concat(command[1]).length + 3));
                        } else if(command[0] == 'role') {
                            showChat('light', 'Error', 'Use /' + command[0] + ' [user] [0-3]');
                        }

                        if(command[0] == 'kick' || command[0] == 'me' || command[0] == 'em') {
                            sendSocket(value.substring(command[0].length + 2), command[0]);
                        }

                        if(command[0] == 'name') {
                            oldname = username;
                            username = value.substring(command[0].length + 2);
                            updateInfo();
                        }
                    } else {
                        var variables;
                        if(command[0] == 'alert' || command[0] == 'me' || command[0] == 'em') {
                            variables = ' [message]';
                        }

                        if(command[0] == 'role') {
                            variables = ' [user] [0-3]';
                        }

                        if(command[0] == 'ban') {
                            variables = ' [user] [minutes]';
                        }

                        if(command[0] == 'pm') {
                            variables = ' [user] [message]';
                        }

                        if(command[0] == 'kick') {
                            variables = ' [user]';
                        }

                        if(command[0] == 'name') {
                            variables = ' [name]';
                        }

                        showChat('light', 'Error', 'Use /' + command[0] + variables);
                    }
                    break; 

                case 'clear':
                    nmr = 0;
                    document.getElementById('panel').innerHTML = '';
                    showChat('light', 'System', 'Messages cleared');
                    break;

                case 'shrug':
                    sendSocket(value.substring(6) + ' ¯\\_(ツ)_/¯', 'message');
                    break;

                case 'help':
                    jQuery('#help-dialog').modal('show');
                    break;

                case 'users':
                    jQuery('#user').click();
                    break;

                case 'reconnect':
                    socket.close();
                    break;

                default:
                    showChat('light', 'Error', 'Unknown command, use /help to get a list of the available commands');
                    break;
            }
        } else {
            sendSocket(value, 'message');
        }
     
        jQuery('#message').val('');
        jQuery('#message').focus();

    }
}

function getTime() {
    var now = new Date();
    var time = [now.getHours(), now.getMinutes(), now.getSeconds()];
 
    for(var i = 0; i < 3; i++) {
        if(time[i] < 10) {
            time[i] = '0' + time[i];
        }
    }
 
    return time.join(':');
}

function updateStyle() {
    jQuery('#panel').linkify();
    var element = document.getElementsByClassName('msg')[nmr];

    if(element.innerHTML != undefined) {
        if(settings.greentext && element.innerHTML.indexOf('&gt;') == 0) {
            element.style.color = '#689f38';
        }

        if(settings.emoji) {
            var input = element.innerHTML;
            var output = emojione.shortnameToImage(element.innerHTML);
            element.innerHTML = output;
        }
    }
}


/* Binds */
jQuery(document).ready(function() {
    jQuery('#close-users-dialog').on('click',function(){
        jQuery('#users-dialog').hide();
    });
    
    jQuery('#user').bind('click', function() {
        var content = '';
        var userip = '';
        var admin;

        for(var i in clients) {
            if(clients[i] != undefined) {
                if(clients[i].ip) {
                    userip = '(' + clients[i].ip + ')';
                }

                if(clients[i].role === 0) {
                    admin = '</li>';
                }
                
                if(clients[i].role === 1) {
                    admin = ' - <b>Helper</b></li>';
                }

                if(clients[i].role === 2) {
                    admin = ' - <b>Moderator</b></li>';
                }

                if(clients[i].role === 3) {
                    admin = ' - <b>Administrator</b></li>';
                }

                content += '<li>' + '<b>#' + clients[i].id + '</b> ' + userip + ' - ' + clients[i].un + admin;
            }
        }

        document.getElementById('users-content').innerHTML = content;
        jQuery('#users-dialog').show();
    });

    jQuery('#panel').on('mouseenter', '.message', function() {
        if(clients[user].role > 0) {
            jQuery(this).find('span:eq(1)').show();
            jQuery(this).find('span:eq(2)').hide();
        }
    });

    jQuery('#panel').on('mouseleave', '.message',function() {
        if(clients[user].role > 0) {
            jQuery(this).find('span:eq(1)').hide();
            jQuery(this).find('span:eq(2)').show();
        }
    });

    jQuery('#panel').on('mouseenter', '.emote', function() {
        if(clients[user].role > 0) {
            jQuery(this).find('span:eq(1)').show();
            jQuery(this).find('span:eq(2)').hide();
        }
    });

    jQuery('#panel').on('mouseleave', '.emote', function() {
        if(clients[user].role > 0) {
            jQuery(this).find('span:eq(1)').hide();
            jQuery(this).find('span:eq(2)').show();
        }
    });

    jQuery('#panel').on('click', '.delete', function(e) {
        var value = jQuery(this)[0].parentElement.attributes[0].value;
        sendSocket(value, 'delete');
    });

    jQuery('#panel').on('click', '.name', function(e) {
        jQuery('#message').val('@' + jQuery(this)[0].children[0].children[0].innerHTML + ' ');
        jQuery('#message').focus();
    });

    jQuery('#send').bind('click', function() {
        handleInput();
    });

    jQuery('#menu-admin').bind('click', function() {
        jQuery('#admin-help-dialog').modal('show');
    });

    jQuery('#help').bind('click', function() {
        jQuery('#help-dialog').modal('show');
    });

    jQuery('#options').bind('click', function() {
        jQuery('#options-dialog').modal('show');
    });

    jQuery('#menu-options').bind('click', function() {
        jQuery('#options-dialog').modal('show');
    });

    jQuery('#audio').bind('click', function() {
        speechToText.start();
        updateBar('mdi-av-mic', 'Start speaking', true);
    });

    jQuery('#emoji').bind('change', function() {
        settings.emoji = document.getElementById('emoji').checked;
        localStorage.settings = JSON.stringify(settings);
    });

    jQuery('#greentext').bind('change', function() {
        settings.greentext = document.getElementById('greentext').checked;
        localStorage.settings = JSON.stringify(settings);
    });

    jQuery('#sound').bind('change', function() {
        settings.sound = document.getElementById('sound').checked;
        localStorage.settings = JSON.stringify(settings);
    });

    jQuery('#synthesis').bind('change', function() {
        settings.synthesis = document.getElementById('synthesis').checked;
        localStorage.settings = JSON.stringify(settings);
    });
    
    jQuery('#inline').bind('change', function() {
        settings.inline = document.getElementById('inline').checked;
        localStorage.settings = JSON.stringify(settings);
    });
        
    jQuery('#desktop').bind('change', function() {
        settings.desktop = document.getElementById('desktop').checked;
        localStorage.settings = JSON.stringify(settings);

        if(Notification.permission !== 'granted') {
            Notification.requestPermission();
        }
    });

    jQuery('#recognition').bind('change', function() {
        settings.recognition = document.getElementById('recognition').checked;
        localStorage.settings = JSON.stringify(settings);

        if(settings.recognition)
            jQuery('#audio').show();
        else {
            jQuery('#audio').hide();
        }
    });

    jQuery('#message').keypress(function(e) {
        if(e.which == 13) {
            if(connected && typing) {
                typing = false;
                clearTimeout(typeTimer);
                socket.send(JSON.stringify({type:'typing', typing:false}));
            }
         
            handleInput();
        } else if(connected) {
            if(!typing) {
                typing = true;
                socket.send(JSON.stringify({type:'typing', typing:true}));
            }

            clearTimeout(typeTimer);
            typeTimer = setTimeout(function() {
                typing = false;
                socket.send(JSON.stringify({type:'typing', typing:false}));
            }, 2000);
        }
    });

    //addition keypress binding for handling autocompletion
    jQuery("#message").keypress( function(event) {
        // don't navigate away from the field on tab when selecting an item
        if (event.keyCode === jQuery.ui.keyCode.TAB )
            event.preventDefault();
    })
    .autocomplete({
        minLength: 0,
        source: function(request, response) {
            var term = request.term;
            var results = [];
            term = term.split(/ \s*/).pop();

            if (term.length > 0 && term[0] === '@') {
                var names = jQuery.map( clients, function( val ) { return val.un; });
                results = jQuery.ui.autocomplete.filter(names, term.substr(1));
            }
            response(results);
        },
        focus: function() {
            return false; // prevent value inserted on focus
        },
        select: function(event, ui) {
            var terms = this.value.split(/ \s*/);
            var old = terms.pop();  //get old word
            var ins = "@" + ui.item.value + " "; //new word to insert
            var ind = this.value.lastIndexOf(old); //location to insert at
            this.value = this.value.slice(0,ind) + ins;
            return false;
        }
    });
});

/* Internal */
if(Notification) {
    jQuery('#toggle-desktop').show();
}

if('speechSynthesis' in window) {
    jQuery('#toggle-synthesis').show();
    textToSpeech = new SpeechSynthesisUtterance();
    textToSpeech.lang = 'en';
}

if('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
    jQuery('#toggle-recognition').show();
    var speechToText = new webkitSpeechRecognition();
    speechToText.interimResults = true;
    speechToText.continuous = true;
    speechToText.lang = 'en-US';
}

if(speechToText) {
    speechToText.onresult = function(event) {
        jQuery('#message').val('');
    
        for (var i = event.resultIndex; i < event.results.length; ++i) {
            if (event.results[i].isFinal) {
                jQuery('#message').val(event.results[i][0].transcript);
                updateBar('mdi-content-send', 'Enter your message here', false);
                speechToText.stop();
                handleInput();
            } else {
                var oldval = jQuery('#message').val();
                jQuery('#message').val(oldval + event.results[i][0].transcript);
            }
        }
    }
    
    speechToText.onerror = function(event) {
        updateBar('mdi-content-send', 'Enter your message here', false);
    }
   
}

function desktopNotif(message) {
    if(!Notification) {
        return;
    }

    var notification = new Notification('You\'ve got a new message', {
        icon: 'http://i.imgur.com/ehB0QcM.png',
        body: message
    });
}

if(typeof(Storage) !== 'undefined') {
    if(!localStorage.settings) {
        localStorage.settings = JSON.stringify(settings);
    } else {
        settings = JSON.parse(localStorage.settings);
         if(settings.recognition) {
            jQuery('#audio').show();
        }
    }
}

window.onfocus = function() {
   // document.title = 'Node.JS Chat';
    focus = true;
    unread = 0;
};


window.onblur = function() {
    focus = false;
};