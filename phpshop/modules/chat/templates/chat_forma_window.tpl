<script>
    function checkModChatForma() {
        if (document.getElementById('chat_mod_user_name').value != "") {
            var w = 500;
            var h = 550;
            var url = 'phpshop/modules/chat/chat.php?name=' + document.getElementById('chat_mod_user_name').value;
            chat = window.open(url, "chat", "dependent=1,left=100,top=20,width=" + w + ",height=" + h + ",location=0,menubar=0,resizable=1,scrollbars=0,status=0,titlebar=0,toolbar=0");
            chat.focus();
        }
        else
            return false;
    }
</script>


<div style="position:relative">
    <div id="mod_chat_forma" style="display: none;width:215px;position:absolute;z-index:100">
        <div>
            <div style="position:relative; border:1px solid #ccc; background:#ebf1f6 ;border-radius:5px;">
                    <table style="margin:0px 8px 0px 19px" border="0" cellpadding="2" cellspacing="0">
                        <tbody>
                            <tr>
                                <td colspan="2" style=" padding:10px;" align="right"><a style="padding:0px; font-size:11px;margin:0px 0px 0px 14px;color:#086ebd" href="javascript:void(0)" onclick="document.getElementById('mod_chat_forma').style.display = 'none';"><img src="phpshop/modules/chat/templates/close.png" alt="" border="0" align="absmiddle"></a></td>
                            </tr>
                            <tr>
                                <td><b>Имя</b>:</td>
                                <td><input type="text" name="chat_mod_user_name" id="chat_mod_user_name" style="width:120px"  value=""></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input type="button" name="chat_mod_start" value="Начать чат" style="width:130px" onclick="checkModChatForma()"></td>
                            </tr>
                        </tbody>
                    </table>
            </div>

        </div>

    </div>
</div>
<div style="padding-top:5px"><img src="phpshop/modules/chat/templates/chat.png" alt="" border="0" align="absmiddle"> <a href="javascript:void(0)" onclick="document.getElementById('mod_chat_forma').style.display = 'block';">Начать чат!</a></div>




