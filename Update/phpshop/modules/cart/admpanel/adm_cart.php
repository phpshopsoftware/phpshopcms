<?php

$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("readcsv");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
$PHPShopSystem =  new PHPShopSystem();

// Класс чтения из CSV
class ProductCsv extends PHPShopReadCsv {
    var $CsvToArray;
    
    function ProductCsv($file) {
        $this->CsvContent = parent::readFile($file);
        parent::PHPShopReadCsv();
    }
    
    function CreatBase() {
        $CsvToArray = $this->CsvToArray;
        if(is_array($CsvToArray))
            foreach ($CsvToArray as $items) {
                $_PRODUCT[$items[0]]['id']=$items[0];
                $_PRODUCT[$items[0]]['art']=$items[1];
                $_PRODUCT[$items[0]]['name']=$items[2];
                $_PRODUCT[$items[0]]['price']=$items[3];
            }
        return $_PRODUCT;
    }
}



// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cart.cart_system"));
$data = $PHPShopOrm->select();

$_ADMIN['dir']="../../../../UserFiles/Price/";
$DB = $DB = $_ADMIN['dir'].$data['filedir'];
$ProductCsv = new ProductCsv($DB);
$_PRODUCT = $ProductCsv->CreatBase();

$list='<select id="dbItems" style="width:100%;height:80%" multiple>';

if(is_array($_PRODUCT))
    foreach($_PRODUCT as $val)
        if($_GET['item'] == $val['id']) $list.='<option value="'.$val['id'].'" selected>#'.$val['art'].' '.$val['name'].' ('.$val['price'].')</option>';
        else $list.='<option value="'.$val['id'].'">#'.$val['art'].' '.$val['name'].' ('.$val['price'].')</option>';

$list.='</select> ';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
    <head>
        <title>База товаров</title>
        <META http-equiv=Content-Type content="text/html; charset=windows-1251">
        <LINK href="../../../admpanel/editor2.6/editor/skins/silver/fck_dialog.css" type=text/css rel=stylesheet>

        <script>

            function ok(){
                if(window.opener){
                    window.opener.document.getElementById('cmbLinkProtocol').value='';
                    var id = document.getElementById('dbItems').value;
                    if(id>0) window.opener.document.getElementById('txtUrl').value='?item='+id;
                    else alert("Товар в базе не выбран!");
                }
                self.close();
            }

        </script>
    </head>

    <body>
        <? echo $list; ?>
        <p align="right"><input type="button" value="Выбрать" onclick="ok()"> <input type="button" value="Отмена" onclick="self.close()"></p>
    </body>
</html>