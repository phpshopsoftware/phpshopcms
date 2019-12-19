<?php

class PHPShopOrder extends PHPShopCore {

    function __construct() {
        $this->action=array("nav"=>"index","post"=>"order");
        parent::PHPShopCore();

        // ��������� ������� ������
        $this->navigation(false,'���������� ������');
    }


    function index() {
        $dis='';

        // ����� ������
        $this->order_num=substr(abs(crc32(uniqid($_SESSION['sid']))),0,5);

        // ��������� ����������
        $url=parse_url("http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
        $Query=$url["query"];
        $QueryArray=parse_str($Query,$output);

        // ������� �������
        if(isset($output['item'])) {
            $_SESSION['CART'][$output['item']]=$_PRODUCT[$output['item']];
            $_SESSION['CART'][$output['item']]['num']++;
            //session_register('CART');
        }

        // �������� ��� ��������
        switch($_POST['operation']) {

            case("+"): $_SESSION['CART'][$_POST['cart_id']]['num']++;
                break;

            case("-"): {
                    $_SESSION['CART'][$_POST['cart_id']]['num']--;
                    if($_SESSION['CART'][$_POST['cart_id']]['num'] <= 0) unset($_SESSION['CART'][$_POST['cart_id']]);
                }
                break;

            case("X"): {
                    unset($_SESSION['CART'][$_POST['cart_id']]);
                }
                break;

        }




        if(count($_SESSION['CART'])>0) {
            $dis.='
<div align="center"><br><br><h1>����� �'.$this->order_num.' �� '.date("d-m-y").'</h1></div><h4>�������</h4>
<table class="table table-striped table-bordered">
<tr>
    <td><strong>�������</strong></td>
	<td><strong>������������</strong></td>
	<td width="20"><strong>��.</strong></td>
	<td><strong>����</strong></td>
	<td width="100"></td>
</tr>
';

            if(is_array($_SESSION['CART']))
                foreach($_SESSION['CART'] as $key=>$val) {
                    $dis.='<tr>
    <td>'.$val['art'].'</td>
	<td>'.$val['name'].'</td>
	<td>'.$val['num'].'</td>
	<td>'.$val['price'].' '.$GLOBALS['LoadItems']['modules']['cart']['valuta'].'</td>
	<td align="center">
	<form method="post" action="./">
	<input type="hidden" name="cart_id" value="'.$key.'">
	<input type="submit" value="+" name="operation" class="btn btn-success btn-xs" title="�������� 1 ��.">
	<input type="submit" value="-" name="operation" class="btn btn-warning btn-xs" title="������� 1 ��.">
	<input type="submit" value="x" name="operation" class="btn btn-danger btn-xs" title="������� �� �������">
	</form>
	</td>
</tr>';
                    @$sum+=$val['price']*$val['num'];
                    @$num+=$val['num'];
                }

            $dis.='<tr>
    <td colspan="2"><strong>�����</strong></td>
	<td><strong>'.$num.'</strong></td>
	<td colspan="2"><strong>'.$sum.'</strong> '.$GLOBALS['LoadItems']['modules']['cart']['valuta'].'</td>
</tr>
</table>';

        }else $dis.='<h4>�������</h4>���� ������� �����. �������� ������ ����� �� ������� <a href="../price/">�����-����</a>.';


        // ���������� ����������
        $this->set('pageTitle','����� �����');

        // ���������� ������
        $dis.=ParseTemplateReturn($GLOBALS['SysValue']['templates']['cart']['order_forma'],true);

        if(count($_SESSION['CART'])>0)
            $dis.='<p><input type="hidden" value="'.$this->order_num.'" name="order_num">
                <input type="button" value="�����-����" onclick="window.location.replace(\'../price/\')" class="btn btn-warning"> <input type="button" value="���������� �������" onclick="javascript:history.back(1);" class="btn btn-default"> <input type="submit" name="order" value="�������� �����" class="btn btn-primary"></form></p>';
        else $dis.='</form>';




        // ����
        $this->title="����� ������ - ".$this->PHPShopSystem->getValue("name");

        // ���������� ���������
        $this->set('pageContent',$dis);
        $this->set('pageTitle','����� ������');


        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));

    }


    // ��������� � �������� ������
    function order() {


        if(is_array($_SESSION['CART']) and PHPShopSecurity::true_email($_POST['mail'])) {
            $order_total=0;
            $order_content="���������� ������:

";

            // ������ �������
            foreach($_SESSION['CART'] as $val) {
                $order_content.="
�������: ".$val['art']."
������������:  ".$val['name'].".
����: - ".$val['price'].$GLOBALS['LoadItems']['modules']['cart']['valuta']."
���-�� - ".$val['num']." ��.
----------------------------
";
                $order_total+=$val['price']*$val['num'];
            }

            $order_content.="
�����: ".$order_total." ".$GLOBALS['LoadItems']['modules']['cart']['valuta'];

            $order_content.="

���������� �� �������:

";

            // ���������� �� ������������
            foreach($_POST as $key=>$val)
                if($key != "order" and $key != "order_num" and $key != 'order_file') $order_content.=$val."
";

            PHPShopObj::loadClass("mail");
            $zag="����� ".$_POST['order_num']." / ".date("d-m-y")." / ". $this->PHPShopSystem->getValue("name");

            // ��������� �������������� c ������ � ���
            if(!empty($_FILES['order_file']['tmp_name']))
            $PHPShopMailFile = new PHPShopMailFile($GLOBALS['LoadItems']['modules']['cart']['email'],$_POST['mail'],$zag,$order_content,$_FILES['order_file']['name'],$_FILES['order_file']['tmp_name']);
            else
            $PHPShopMail = new PHPShopMail($GLOBALS['LoadItems']['modules']['cart']['email'],$_POST['mail'],$zag,$order_content);

            // ��������� �������
            $PHPShopMail = new PHPShopMail($_POST['mail'],$GLOBALS['LoadItems']['modules']['cart']['email'],$zag,$order_content);

            // ������� �������
            $_SESSION['CART']=null;
            unset($_SESSION['CART']);


            // ��������� ����� ������
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['cart']['cart_system']);
            $Option = $PHPShopOrm->select(array("message"));


            $dis=$Option['message'];

            // ����
            $this->title="����� ������ - ".$this->PHPShopSystem->getValue("name");

            // ���������� ���������
            $this->set('pageContent',$dis);
            $this->set('pageTitle','����� ������');


            // ���������� ������
            $this->parseTemplate($this->getValue('templates.page_page_list'));
        }

    }

}
?>