<p><font color="#FF0000">@usersError@</font></p>
<form method="post" name="user_forma" id="user_forma" action="/user/">
    <table cellspacing="5" cellpadding="5">
        <tr>
            <td align="right">	Логин:
            </td>
            <td>
                <input type="text"  name="login" value="" size="25"> * не менее 2 символов
            </td>
        </tr>
        <tr>
            <td align="right">	Пароль:
            </td>
            <td>
                <input  type="password" name="password" value="" size="25"> * не менее 4 символов
            </td>
        </tr>
        <tr>
            <td align="right">	E-mail:
            </td>
            <td>
                <input type="text" name="mail" value="" size="25"> * требуется активация пользователя
            </td>
        </tr>
        <tr>
            <td align="right">	Имя:
            </td>
            <td>
                <input  type="text" name="dop_ФИО" size="25">
            </td>
        </tr>
        <tr>
            <td align="right">	Адрес:
            </td>
            <td>
                <textarea cols="20" rows="5" name="dop_Адрес"></textarea>                           
            </td>
        </tr>
        @captchaCommentStart@
        <tr>
            <td align="right">
            </td>
            <td>
                <table>
                    <tr>
                        <td><img src="phpshop/captcha2.php" alt="" border="0"></td>
                    </tr>
                    <tr>
                        <td><strong>*</strong> Проверочный текст<br>
                            <input type="text" name="key" size="20"></td>
                    </tr>
                </table>
            </td>
        </tr>
        @captchaCommentEnd@
        <tr>
            <td align="right">
            </td>
            <td >
                <input class="user" type="submit" name="add_user" value="Регистрация">
            </td>
        </tr>
    </table>
</form>