<!DOCTYPE html>
<HTML>
    <HEAD>
        <TITLE>Чат онлайн</TITLE>
        <META http-equiv="Content-Type" content="text-html; charset=windows-1251">

        <!-- Bootstrap -->
        <LINK href="templates/skin/bootstrap-theme-@chat_mod_skin@.css" type="text/css" rel="stylesheet">

        <SCRIPT src="./lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <SCRIPT src="./ajax/phpshopchat.js"></SCRIPT>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <style>

            #chat_mod_content{
                height: 350px;
                overflow: auto;
                padding: 15px 0px 0px 0px;
                font-size: 12px;
 
            }

            #chat_mod_user_text{
                margin-bottom:20px;
            }

            .panel{
                margin-left: 15px;
                margin-right: 15px;
            }

            .avatar{
                max-width: 50px;
                margin-left:10px;
            }

        </style>
        <script>

            function imgerror(obj) {
                obj.src = '//placehold.it/50x50';
            }

            $().ready(function() {


                PHPShopChat_ping();
                PHPShopChat_email();


                $("#exit").on('click', function() {
                    if (confirm("Вы действительно хотите выйти из чата?")) {
                        var req = new Subsys_JsHttpRequest_Js();
                        req.onreadystatechange = function() {
                            if (req.readyState == 4) {
                                if (req.responseJS) {
                                    if (parent.window.$('#chatModal').length)
                                        parent.window.$('#chatModal').modal('hide');
                                    else
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
                });

                $("#send").on('click', function() {
                    var post = '@chat_mod_disable@';
                    if (post != 'disabled') {
                        PHPShopChat_write(post);
                    }
                    else {

                        if (parent.window.$('#chatModal').length) {
                            parent.window.$('#chatModal').modal('hide');
                            parent.window.location.replace('//@serverName@/forma/');
                        }
                        else {
                            window.opener.location.replace('//@serverName@/forma/');
                            self.close();
                        }
                    }
                });

                $("#no").on('click', function() {
                    if (parent.window.$('#chatModal').length)
                        parent.window.$('#chatModal').modal('hide');
                    else
                        self.close();
                });

                $("#yes").on('click', function() {
                    if (parent.window.$('#chatModal').length) {
                        parent.window.$('#chatModal').modal('hide');
                        parent.window.location.replace('//@serverName@/forma/');
                    }
                    else {
                        window.opener.location.replace('//@serverName@/forma/');
                        self.close();
                    }
                });
               
            });



        </script>


    </HEAD>
    <BODY>
        <audio id="audio" src="http://@serverName@/phpshop/modules/chat/@chat_mod_sound@"></audio>
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12 well" id="chat_mod_content">
                    @chat_mod_content@</div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <textarea name="chat_mod_user_text" id="chat_mod_user_text" @chat_mod_disable@ class="form-control" placeholder="Текст..."></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 pull-left">
                    <button class="btn btn-warning" id="exit">
                        <span class="glyphicon glyphicon-remove"></span> Выход
                    </button>
                </div>
                <div class="col-md-6 pull-right">
                    <button class="btn btn-success" id="send">
                        <span class="glyphicon glyphicon-comment" id="chat_mod_send_button_icon"></span> <span id="chat_mod_send_button_text">Отправить</span>
                    </button>
                    <input type="hidden" value="@chat_mod_product_name@" id="chat_mod_product_name" name="chat_mod_product_name">
                    <input type="hidden" value="@chat_mod_dir@" id="chat_mod_dir" name="chat_mod_dir">
                    <input type="hidden"  value="@chat_mod_time@" id="chat_mod_time" name="chat_mod_time">
                </div>
            </div>
        </div>
    </BODY>
</HTML>