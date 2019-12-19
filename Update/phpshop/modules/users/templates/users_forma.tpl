<p>
@activationNotice@
<form method="post" name="user_forma" action="/user/">
    <table cellspacing="5">
        <tr>
            <td align="right">	Логин:
            </td>
            <td>
                <input type="text" name="login"  size="10">
            </td>
        </tr>
        <tr>
            <td align="right">	Пароль:
            </td>
            <td>
                <input  type="password" name="password" size="10">
            </td>
        </tr>
        <tr>
            <td align="right">
            </td>
            <td >
                <input  type="submit" name="send" value="Авторизация">
            </td>
        </tr>
    </table>
    <input type="hidden" value="1" name="enter_user">
</form>
<p><a href="/user/register_user.html" title="Регистрация">Регистрация</a><br><a href="/user/sendpassword_user.html"  title="Забыли пароль?">Забыли пароль?</a></p>
</p>