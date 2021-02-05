<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.googletranslate.googletranslate_system"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm,$PHPShopModules;
    
    // ��������� �������
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);
    
    $_POST['lang_new']= serialize($_POST['lang']);

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id='.$_GET['id']);
    return $action;
}

// ����� �����
function GetLocaleList($lang) {
    global $PHPShopGUI;
    $dir = "../modules/googletranslate/lib/images/lang/";
    
    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                $name = explode(".",$file);
                $name=str_replace("lang__","",$name['0']);
             
                if (@in_array($name,$lang))
                    $sel = "selected";
                else
                    $sel = "";

                if ($file != "." and $file != ".." )
                    $value[] = array($name, $name, $sel, 'data-content="<img src=\'' . $dir  . $file .'\'> ' . ucfirst ($name) . '"');
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('lang[]', $value,300,false, false,false, false, 1, true);
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->title = "��������� ������ JivoSite";

    // �������
    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField('����� ��������', GetLocaleList(unserialize($data['lang'])));

    // ����� �����������
    $Tab3 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1,true), array("� ������", $Tab3));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>