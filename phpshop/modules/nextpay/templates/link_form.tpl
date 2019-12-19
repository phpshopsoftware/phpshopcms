<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        p {color:#000000;}
        small {color:#999999;}
        small a {color:#fafafa;}

        .note {color:#999999;}
        .note p {color:#999999;}
        .note a {color:#cccccc;}

        #header p, #header a {color:#000000;}
        #header h1 {color:#000000;}
        #number h1 {color:#000000;}

        #page a {color:#999999;}
        #page a:hover {color:#fafafa;}

        #topBox h1 {color:#fafafa;}
        #topBox small {color:#999999;}

        #bottomBox h1 {color:#fafafa;}
        #bottomBox h4 {color:#666666;}
        #bottomBox small {color:#999999;}

        #page h1 {color:#fafafa;}
        #page h4 {color:#666666;}
        #page h5 {color:#666666;}
        #page p {color:#999999;}
        #page small {color:#cccccc;}
        #page small a {color:#fafafa;}
        #page small b {color:#666666;}

        #footer h1 {color:#FFFFFF;}
        #footer h4 {color:#FFFFFF;}
        #footer p, #footer a, #footer .email a, #footer .email {color:#000000;}

            /* Reset */
        body {background-color:white; background-position:top center; background-repeat:repeat; margin:0; padding:0;}
        table {font-family:Arial,Verdana,sans-serif;}
        ul, li, tr, td, p, h2 {margin-left:15px; padding-right:15px; padding-top:3px;}
        img {border:none;}

            /* Misc */
        .clear{clear:both;}

            /* Basic */
        h1, h2, h3, h4, h5 {font-weight:normal;}
        small {font-size:10px;}
        small a {text-decoration:none;}
        small a:hover {text-decoration:underline;}

            /* Top and Bottom Notes */
        .note {font-size:10px; }
        .note a {text-decoration:none;}
        .note a:hover {text-decoration:underline;}
        .note img, .note p {margin-top:0; margin-bottom:0; margin-left:0; margin-right:0;}

            /* Header */
        #header{height:auto; height:100px; width:660px;}
        #header p {font-size:17px; margin-top:0; margin-bottom:0;}
        #header h1 {font-size:28px; margin-bottom:0;}
        #logo {padding-left:14px; padding-bottom:12px;}
        #logo h1 {line-height:22px;}
        #date {padding-bottom:12px;}
        #number {padding-bottom:12px;}
        #number h1 {font-size:78px; line-height:64px; margin-bottom:0;}

            /* Top Author Box */
        #topBox td {padding:15px;}
        #topBox h1 {font-size:18px; margin-top:0; margin-bottom:5px;}
        #topBox p {font-size:17px; line-height:30px; margin-top:0;}
        #topBox a {text-decoration:none;}
        #topBox a:hover {text-decoration:underline;}
        #topBox small {font-size:10px; }
        #topBox img {margin-right:15px;}

            /* Bottom Box */
        #bottomBox h1 {font-size:20px; margin-top:0;}
        #bottomBox h4 {font-size:13px; line-height:10px; margin-top:10px; margin-bottom:5px;}
        #bottomBox p {font-size:12px; margin-top:0; line-height:1.5em}
        #bottomBox img {margin:0; padding:0;}
        #bottomBox small {font-size:10px;}

            /* Page */
        #page p {font-size:12px; line-height:18px; margin-top:0; margin-bottom:0;}
        #page h1 {font-size:18px; margin:0;}
        #page h4 {font-size:13px; line-height:10px; margin-top:0; margin-bottom:4px;}
        #page h5 {font-size:12px; line-height:10px; margin-top:0; margin-bottom:0px;}
        #page small {font-size:10px;}
        #page small a {text-decoration:none;}
        #page small a:hover {text-decoration:underline;}

            /* Content */
        #content p {margin-left:15px; padding-right:15px; padding-top:3px;}
        #content .hr {padding-top:15px; margin-bottom:15px;}
        #content .alignRight {margin-right:15px; margin-left:30px; margin-bottom:5px; display:inline; float:right;}
        #content .alignLeft {margin-right:30px; margin-left:15px; margin-bottom:5px; display:inline; float:left;}

            /* Footer */
        #footer {height:auto; height:125px; width:660px;}
        #footer h4 {font-size:13px; margin-bottom:0;}
        #footer p {font-size:12px; line-height:18px; margin-top:0; margin-bottom:0; text-decoration:none;}
        #footer .logo {padding-left:14px; padding-top:12px;}
        #footer .logo h1 {font-size:22px; line-height:17px; margin-bottom:0;}
        #footer .contact {padding-right:14px; padding-top:12px;}
        #footer .social a {line-height:0;}
    </style>
</head>
<body><table width="660" valign="top" align="center" cellspacing="0" cellpadding="0">
    <tbody>
    <tr bgcolor="#fafafa" id="header" valign="top">
        <td id="logo" align="left" valign="bottom">
            <div align="center" style="padding-top:30px;"><a href="@shopLink@" target="_blank"><img src="@shopLogo@" alt="@shopName@" border="0" style="display: block;" title="@shopName@"></a>
                <p><h1>@shopName@</h1></p></div>
        </td>
    </tr>

    <tr height="10"><td></td></tr>

    </tbody>
</table>


<table id="topBox" width="660" valign="top" align="center" cellspacing="0" cellpadding="10">
    <tbody>

    <tr bgcolor="#eaeaea">
        <td  >

            <h2>Cпасибо за Ваш заказ!</h2>
            <p> Ваша ссылка для оплаты заказа, номер заказа @orderUid@ на сумму @orderSum@ руб., через платёжный агрегатор NextPay.ru: </p>

            <h3>@payLink@</h3>



        </td>
    </tr>

    </tbody>
</table>


<table width="660" align="center" cellspacing="0" cellpadding="10">
    <tbody>


    <tr bgcolor="#fafafa" id="footer" height="100%">


        <td align="left" valign="top" height="100%">
            <p><b>С уважением, @shopName@.</b></p>
            <p>@shopName@<br>
                Интернет-магазин <a href="@shopLink@">@shopName@</a><br>

    </tr>

    </tbody>
</table>



</body>
</html>