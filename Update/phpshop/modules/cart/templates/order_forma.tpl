<script>
    function ModuleCartChekOrder()
    {
        var s1=document.getElementById('orderName').value;
        var s2=document.getElementById('orderMail').value;
        if (s1=="" || s2=="")
            alert("Ошибка заполнения формы заказа!");
        else
            document.forma_order.submit();
    }
</script>

<form method="post" action="./" name=forma_order" onsubmit="ModuleCartChekOrder();" enctype="multipart/form-data">
    <h4>Персональные данные</h4>
    <table class="table table-bordered">
        <tr>
            <td>ФИО:</td>
            <td><input type="text" name="name" id="orderName"></td>
        </tr>
        <tr>
            <td>E-mail:</td>
            <td><input type="text" name="mail" id="orderMail"></td>
        </tr>
        <tr>
            <td>Тел:</td>
            <td><input type="text" name="tel"></td>
        </tr>
        <tr>
            <td>Тип оплаты:</td>
            <td><select name="oplata" class="form-control">
                    <option value="курьер" SELECTED>Наличные курьеру</option>
                    <option value="Webmoney">Webmoney</option>
                    <option value="Visa, Mastercard">Visa, Mastercard</option>
                    <option value="Почта России">Почта России</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Адрес:</td>
            <td><textarea cols="20" rows="5" name="adres"></textarea></td>
        </tr>
        <tr>
            <td align="right">	Вложение:
            </td>
            <td>
                <input type="file" name="order_file" value="" size="25">
            </td>
        </tr>
    </table>