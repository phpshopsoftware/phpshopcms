<script>
    function ModuleCartChekOrder()
    {
        var s1=document.getElementById('orderName').value;
        var s2=document.getElementById('orderMail').value;
        if (s1=="" || s2=="")
            alert("������ ���������� ����� ������!");
        else
            document.forma_order.submit();
    }
</script>

<form method="post" action="./" name=forma_order" onsubmit="ModuleCartChekOrder();" enctype="multipart/form-data">
    <h4>������������ ������</h4>
    <table class="table table-bordered">
        <tr>
            <td>���:</td>
            <td><input type="text" name="name" id="orderName"></td>
        </tr>
        <tr>
            <td>E-mail:</td>
            <td><input type="text" name="mail" id="orderMail"></td>
        </tr>
        <tr>
            <td>���:</td>
            <td><input type="text" name="tel"></td>
        </tr>
        <tr>
            <td>��� ������:</td>
            <td><select name="oplata" class="form-control">
                    <option value="������" SELECTED>�������� �������</option>
                    <option value="Webmoney">Webmoney</option>
                    <option value="Visa, Mastercard">Visa, Mastercard</option>
                    <option value="����� ������">����� ������</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>�����:</td>
            <td><textarea cols="20" rows="5" name="adres"></textarea></td>
        </tr>
        <tr>
            <td align="right">	��������:
            </td>
            <td>
                <input type="file" name="order_file" value="" size="25">
            </td>
        </tr>
    </table>