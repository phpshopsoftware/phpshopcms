<p><font color="#FF0000">@usersError@</font></p>
<form method="post" name="user_forma" id="user_forma" action="/user/">
    <table cellspacing="5" cellpadding="5">
        <tr>
            <td align="right">	�����:
            </td>
            <td>
                <input type="text"  name="login" value="" size="25"> * �� ����� 2 ��������
            </td>
        </tr>
        <tr>
            <td align="right">	������:
            </td>
            <td>
                <input  type="password" name="password" value="" size="25"> * �� ����� 4 ��������
            </td>
        </tr>
        <tr>
            <td align="right">	E-mail:
            </td>
            <td>
                <input type="text" name="mail" value="" size="25"> * ��������� ��������� ������������
            </td>
        </tr>
        <tr>
            <td align="right">	���:
            </td>
            <td>
                <input  type="text" name="dop_���" size="25">
            </td>
        </tr>
        <tr>
            <td align="right">	�����:
            </td>
            <td>
                <textarea cols="20" rows="5" name="dop_�����"></textarea>                           
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
                        <td><strong>*</strong> ����������� �����<br>
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
                <input class="user" type="submit" name="add_user" value="�����������">
            </td>
        </tr>
    </table>
</form>