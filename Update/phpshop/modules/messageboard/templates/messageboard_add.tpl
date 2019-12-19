<SCRIPT language="JavaScript" src="phpshop/modules/messageboard/templates/board.js"></SCRIPT>

<div class="art-post">
    <div class="art-post-body">
        <div class="art-post-inner">
            <div class="art-postcontent">
                <!-- article-content -->
                <span class="breadcrumbs pathway"><a class="pathway" href="/">Главная</a> / <a class="pathway" href="/board/">Доска объявлений</a> / Форма объявления</span>
                <!-- /article-content -->
            </div>
            <div class="cleared"></div>
        </div>
        <div class="cleared"></div>
    </div>
</div>
<div class="art-post">
    <div class="art-post-body">
        <div class="art-post-inner">
            <h2 class="art-postheader">Форма объявления</h2>
            <div class="art-postmetadataheader">
                <div class="art-postheadericons art-metadata-icons"> </div>
            </div>
            <div class="art-postcontent">
                <!-- article-content -->
                <form method="post" name="forma_gbook">
                    <table cellpadding="5" cellspacing="1" border="0" class="standart">
                        <tr>
                            <td align="right"> Имя </td>
                            <td><input type="text" name="name_new" value="@userName@" maxlength="45" style="width:300px;">
                                <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0"> </td>
                        </tr>
                        <tr >
                            <td align="right"> E-mail </td>
                            <td><input  class=s type="text" name="mail_new" value="@userMail@" maxlength="30" style="width:300px;">
                            </td>
                        </tr>
                        <tr >
                            <td align="right"> Телефон </td>
                            <td><input  class=s type="text" name="tel_new" maxlength="30" style="width:300px;">
                            <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0">
                            </td>
                        </tr>
                        <tr>
                            <td align="right"> Тема сообщения </td>
                            <td><textarea style="width:300px;" name="tema_new" maxlength="60"></textarea>
                                <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0"> </td>
                        </tr>
                        <tr>
                            <td align="right"> Объявление </td>
                            <td valign="top"><textarea style="width:300px;height:300px" name="content_new" maxlength="100" ></textarea>
                                <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0"> </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center"><DIV class="gbook_otvet"><IMG height=16 alt="" hspace=5 src="images/shop/comment.gif" width=16 align=absMiddle border=0>Данные, отмеченные <B>флажками</B> обязательны для заполнения. <br>
                                    <font color="#FF0000"><strong>@Error@</strong></font> </DIV>
                                <p><br>
                                </p>
                                <table>
                                    <tr>
                                        <td><img src="phpshop/captcha.php" alt="" border="0"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>*</strong> Введите текст с картинки<br>
                                            <input type="text" name="key" size="20"></td>
                                    </tr>
                                </table>
                                <p><br>
                                </p>
                                <table align="center">
                                    <tr>
                                        <td><img src="images/shop/brick_error.gif" alt="" width="16" height="16" border="0"> <a href="javascript:forma_gbook.reset();" class="standart"><u class=style1>Очистить форму</u></a></td>
                                        <td width="20"></td>
                                        <td><img src="images/shop/brick_go.gif" alt="" width="16" height="16" border="0"> <a href="javascript:modBoardCheck();" class="standart"><u class=style1>Добавить объявление</u></a></td>
                                    </tr>
                                </table>
                                <input type="hidden" name="send_gb" value="ok" >
                            </td>
                        </tr>
                    </table>
                </form>
                <span class="article_separator">&nbsp;</span>
                <!-- /article-content -->
            </div>
            <div class="cleared"></div>
        </div>
        <div class="cleared"></div>
    </div>
</div>
