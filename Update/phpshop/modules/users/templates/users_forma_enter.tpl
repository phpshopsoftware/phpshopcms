<p>
<h1>����� ����������, @userName@</h1>
    <form method="post" name="user_forma" action="/user/">
    <table cellspacing="5" cellpadding="5">
        <tr>
            <td align="right">	�����:
            </td>
            <td>
                <b>@userName@</b>
            </td>
        </tr>
                <tr>
            <td align="right">	���������������:
            </td>
            <td>
                <b>@userDate@</b>
            </td>
        </tr>
        <tr>
            <td align="right">	E-mail:
            </td>
            <td>
                <input type="text" name="mail" value="@userMail@" size="25">
            </td>
        </tr>
        @userContent@
        <tr>
            <td align="right">	����� ������:
            </td>
            <td>
                <input  type="password" name="password" value="@userPassword@" size="25">
            </td>
        </tr>

        <tr>
            <td align="right">
                <input class="user" type="submit" name="exit_user" value="�����">
            </td>
            <td >
                <input class="user" type="submit" name="update_user" value="�������� ������"> @userMessage@
            </td>
        </tr>
    </table>
</form>

</p>