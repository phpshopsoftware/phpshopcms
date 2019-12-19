<script>
function checkModChatForma(){
    if(document.getElementById('chat_mod_user_name').value != ""){
        var w=500;
        var h=550;
        var url='phpshop/modules/chat/chat.php?name='+document.getElementById('chat_mod_user_name').value;
        chat=window.open(url,"chat","dependent=1,left=100,top=20,width="+w+",height="+h+",location=0,menubar=0,resizable=1,scrollbars=0,status=0,titlebar=0,toolbar=0");
        chat.focus();
}
    else 
        return false;
}
</script>
<div style="padding:5px">
        <table>
            <tr>
                <td><b>Имя</b>:</td>
                <td><input type="text" name="chat_mod_user_name" id="chat_mod_user_name" style="width:120px"  value=""></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="button" name="chat_mod_start" class="btn btn-primary btn-sm" value="Начать чат" style="width:130px" onclick="checkModChatForma()"></td>
            </tr>
        </table>
</div>