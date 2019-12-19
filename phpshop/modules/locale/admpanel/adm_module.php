<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.locale.locale_system"));


// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    if(empty($_POST['skin_enabled_new'])) $_POST['skin_enabled_new']=0;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
}


// ����� �������
function GetSkins($skin) {
    global $PHPShopGUI;
    $dir="../templates";
    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                if($skin == $file)
                    $sel="selected";
                else $sel="";

                if($file!="." and $file!=".." and $file!="index.html")
                    $value[]=array($file,$file,$sel);
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('skin_new',$value);
}


function actionStart() {
    global $PHPShopGUI,$PHPShopOrm;

    // �������
    $data = $PHPShopOrm->select();

    
    // ���������� �������� 1
    $Tab1=$PHPShopGUI->setField("�������� �����:",$PHPShopGUI->setInputText('','name_new',$data['name']));
    $Tab1.=$PHPShopGUI->setField("������ 2-�� �����:",GetSkins($data['skin']).
            $PHPShopGUI->setLine().
            $PHPShopGUI->setCheckbox('skin_enabled_new',1,'������������',$data['skin_enabled']));


    $Tab3=$PHPShopGUI->setPay();
    
    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������",$Tab1,true),array("� ������",$Tab3,270));
    
    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", 1) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");
    
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>