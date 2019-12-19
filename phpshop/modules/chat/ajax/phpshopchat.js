

function getKey(e){
    if (e == null) { // ie
        key = event.keyCode;
        ctrl=event.ctrlKey;
    } else { // mozilla
        key = e.which;
        ctrl=e.ctrlKey;
    }
    
    // CTRL+ENTER
    if((key=='13') && ctrl) PHPShopChat_write();
}

document.onkeydown = getKey; 

// Смайлики
function emoticon(text) {
    var txtarea = document.getElementById("chat_mod_user_text");
    if (txtarea.createTextRange && txtarea.caretPos) {
        var caretPos = txtarea.caretPos;
        caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
        txtarea.focus();
    } else {
        txtarea.value  += text;
        txtarea.focus();
    }
}


function PHPShopChat_start(){
    window.location.reload('?name');
    if(document.getElementById('chat_mod_user_name').value != ""){
        var url='?name='+document.getElementById('chat_mod_user_name').value;
        return window.location.reload(url);
    }
    else 
        return false;
}

function PHPShopChat_exit(){
    if(confirm("Вы действительно хотите выйти из чата?")){
        var req = new Subsys_JsHttpRequest_Js();
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                if (req.responseJS) {
                    self.close();
                }
            }
        }
        req.caching = false;

        req.open('POST', './ajax/message.php', true);
        req.send({
            close: 1
        });
    }
}



function PHPShopChat_get() {
    var req = new Subsys_JsHttpRequest_Js();
    var time = document.getElementById('chat_mod_time').value;
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                document.getElementById('chat_mod_content').innerHTML+= (req.responseJS.message||'');
                document.getElementById('chat_mod_time').value = (req.responseJS.time||'');
                
                // Звуковой сигнал и прокрутка
                if(req.responseJS.message != null){
                    document.getElementById('chat_mod_content').innerHTML+='<span id="chat_mod_scroll_point_'+req.responseJS.time+'"></span>';
                    document.getElementById('chat_mod_scroll_point_'+req.responseJS.time+'').scrollIntoView(false);
                    document.getElementById('audio').play();
                
                    window.document.title='!!! Новое сообщение в чате !!!';
                    
                    // Чат закрыт менеджером
                    /*
                    var pattern=/.*(Менеджер закрыл чат)/;
                    if(pattern.test(req.responseJS.message))
                        document.getElementById('chat_mod_user_text').disabled='disabled';*/
                }
            }
        }
    }
    req.caching = false;

    req.open('POST', './ajax/message.php', true);
    req.send({
        time: time
    });
}


function PHPShopChat_write(post) {
    if(post != 'disabled'){
        var req = new Subsys_JsHttpRequest_Js();
        var addtext = document.getElementById('chat_mod_user_text').value;
        document.getElementById('chat_mod_user_text').value = '';
        var time = document.getElementById('chat_mod_time').value;
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                if (req.responseJS) {
                    document.getElementById('chat_mod_content').innerHTML+= (req.responseJS.message||'');
                    document.getElementById('chat_mod_time').value = (req.responseJS.time||'');
                    
                    // Прокрутка
                    if(req.responseJS.message != null){
                        document.getElementById('chat_mod_content').innerHTML+='<span id="chat_mod_scroll_point_'+req.responseJS.time+'"></span>';
                        document.getElementById('chat_mod_scroll_point_'+req.responseJS.time+'').scrollIntoView(false);
                        window.document.title='Чат онлайн';
                    }
                }
            }
        }
        req.caching = false;

        req.open('POST', './ajax/message.php', true);
        req.send({
            addtext: addtext,
            time: time
        });
        
        document.getElementById('chat_mod_user_text').focus();
    }
    else{
        window.opener.location.replace('/forma/');
        self.close();  
    }
}


function PHPShopChat_email(){
    if(document.getElementById('chat_mod_user_text').disabled){
        
        if(document.getElementById('chat_mod_product_name').value == 'PHPShop Start')
            document.getElementById('post').style.display='none';

        else{
            document.getElementById('chat_mod_send_button_icon').src='./templates/email.gif';
            document.getElementById('chat_mod_send_button_text').innerHTML='E-mail';
           
        }
    }
}

function PHPShopChat_ping(){
    document.getElementById('chat_mod_user_text').focus();
    setInterval("PHPShopChat_get()",5000);
}

