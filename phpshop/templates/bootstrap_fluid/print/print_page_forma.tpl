<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
    <HEAD>
        <TITLE>@pageTitl@</TITLE>
        <META http-equiv="Content-Type" content="text-html; charset=windows-1251">
        <META name="description" content="@pageDesc@">
        <META name="keywords" content="@pageKeyw@">
        <META name="copyright" content="@pageReg@">
        <style type="text/css">
            <!-- 
            body {
                font-family: Tahoma;
            }

            P {
                font: normal 11px Verdana, Arial, Helvetica, sans-serif;
                word-spacing: normal;
                white-space: normal;
                margin: 5px 5px 5px 5px;
                letter-spacing : normal;
            }
            TABLE {
                font: normal 11px Verdana, Arial, Helvetica, sans-serif;
            }
            .sort_name_bg{
                background-color: #F0F1F1;
            }
            .sort_table{
                margin-top: 10px;
                background-color: White;
                BORDER-RIGHT: #d3d3d3 1px dashed;
                PADDING-RIGHT: 5px;
                BORDER-TOP: #d3d3d3 1px dashed;
                PADDING-LEFT: 5px;
                PADDING-BOTTOM: 5px;
                BORDER-LEFT: #d3d3d3 1px dashed;
                PADDING-TOP: 5px;
                BORDER-BOTTOM: #d3d3d3 1px dashed;
            }
            button{
                font-size: 8pt;
                border: solid 1px #CCC;
                -webkit-border-radius:5px;
                -moz-border-radius:5px;
                border-radius:5px;
                background: -moz-linear-gradient(#FFF, #F0F0F0);
                background: -ms-linear-gradient(#FFF, #F0F0F0);
                background: -o-linear-gradient(#FFF, #F0F0F0);
                background: -webkit-linear-gradient(#FFF, #F0F0F0);
                cursor: pointer;
            }
            --></style>
        <STYLE media="print" type="text/css"><!-- 
            .nonprint {
                display: none;
            }
            --></STYLE>
    </HEAD>
    <BODY>
        <table border="0" cellpadding="5" cellspacing="5" width="100%">
            <TR>
                <TD>
                    <a href="http://@serverShop@"><IMG src="http://@serverShop@@logoShop@" border="0"></a>
                </TD>
                <TD><H3>@nameShop@</H3>
                    @descripShop@
                </TD>
                <td>
                    <div align="right" class="nonprint">
                        <button onclick="window.print()">
                            <img border=0 align=absmiddle hspace=3 vspace=3 src="http://@php echo $_SERVER['SERVER_NAME'].$GLOBALS['SysValue']['dir']['dir']; php@/phpshop/admpanel/img/action_print.gif">Распечатать
                        </button> 
                        <br><br>
                    </div>
                </td>
            </TR>
        </table>
        <TABLE border="0" cellpadding="5" cellspacing="5" width="100%">

            <TR>
                <TD colspan="2">
                    <HR>
                </TD>
            </TR>
            <TR>
                <TD>
                    <a href="http://@serverShop@/shop/UID_@productId@.html"><IMG src="http://@serverShop@@productImg@" alt="@productName@" title="@productName@" border="0" hspace="10"></a>
                </TD>
                <TD valign="top"><H1>@productName@ / @productPrice@ @productValutaName@</H1>
                    <div class="nonprint">
                        Ссылка: <a href="http://@serverShop@/shop/UID_@productId@.html" title="Перейти по ссылке: @productName@">http://@serverShop@/shop/UID_@productId@.html</a>
                    </div>
                    @vendorDisp@
                </TD>
            </TR>
            <TR>
                <TD style="TEXT-ALIGN: justify" colspan="2"><BR><BR><B>Дополнительно:</B>
                    <P>@productDes@</P>
                    <P><BR></P>
                </TD>
            </TR>
        </TABLE>
        <HR>
<div class="nonprint">
