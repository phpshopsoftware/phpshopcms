<?php
$TitlePage = __('�������� ���������� �����');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['menu']);

// ��������� �����
function setSelectChek($n) {
    $i = 1;
    while ($i <= 10) {
        if ($n == $i)
            $s = "selected"; else
            $s = "";
        $select[] = array($i, $i, $s);
        $i++;
    }
    return $select;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopOrm, $PHPShopModules;

    // �������
    $data['flag'] = 1;
    $data['name'] = __('����� ����');

    $PHPShopGUI->setActionPanel(__("�������� ������ ���������� �����"), false ,array('��������� � �������'));

    // �������� 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '350';
    $oFCKeditor->Value = $data['content'];

    $Select1 = setSelectChek($data['num']);

    $Select2[] = array("�����", 0, $data['element']);
    $Select2[] = array("������", 1, $data['element']);

    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setField("��������:", $PHPShopGUI->setInput("text", "name_new", $data['name'], "none", 500)) .
             $PHPShopGUI->setField("������:",$PHPShopGUI->setRadio("flag_new", 1, "��������", $data['flag']) . $PHPShopGUI->setRadio("flag_new", 0, "���������", $data['flag'])) .
            $PHPShopGUI->setField("�������:", $PHPShopGUI->setSelect("num_new", $Select1,150)) .
            $PHPShopGUI->setField("�����:", $PHPShopGUI->setSelect("element_new", $Select2,150)) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setField("���������:", $PHPShopGUI->setInput("text", "dir_new", $data['dir']) .
                    $PHPShopGUI->setHelp(__('* ������: /page/,/news/. ����� ������� ��������� ������� ����� �������.')));

    $Tab1.= $PHPShopGUI->setField("����������",$oFCKeditor->AddGUI());
    
        // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1));



    // ����� ������ ��������� � ����� � �����
 $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "��", "right", 70, "", "but", "actionInsert.menu.create");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}


// ������� ������
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
}

// ��������� ������� 
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

?>