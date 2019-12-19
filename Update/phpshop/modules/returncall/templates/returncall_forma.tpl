<script>
    function checkModReturnCallForma(){
        if(document.getElementById('returncall_mod_name').value == "" || document.getElementById('returncall_mod_tel').value == "")
            return false;
    }
</script>
<div style="padding:5px">
    <form action="@ShopDir@/returncall/" method="post" onsubmit="return checkModReturnCallForma();" >

        <table>
            <tr>
                <td><b>Имя</b>:</td>
                <td><input type="text" name="returncall_mod_name" id="returncall_mod_name" ></td>
            </tr>
            <tr>
                <td><b>Телефон</b>:</td>
                <td><input type="text" name="returncall_mod_tel" id="returncall_mod_tel" > </td>
            </tr>
            <tr>
                <td>Время звонка:</td>
                <td>от <input type="text" name="returncall_mod_time_start" id="returncall_mod_time_start" size="2"> до 
                    <input type="text" name="returncall_mod_time_end" id="returncall_mod_time_end" size="2" ></td>
            </tr>
            <tr>
                <td>Сообщение:</td>
                <td><textarea name="returncall_mod_message" cols="12" rows="3"></textarea></td>
            </tr>
            
            <tr>
                <td colspan="2">@returncall_captcha@</td>
            </tr>
            
            <tr>
                <td></td>
                <td><input type="submit" name="returncall_mod_send" class="returncall_mod_send" value="Перезвоните мне"></td>
            </tr>
        </table>
    </form>
</div>