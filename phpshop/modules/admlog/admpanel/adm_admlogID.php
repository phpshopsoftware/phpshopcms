<?php

$TitlePage = __('�������������� ������ #' . intval($_GET['id']));

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.admlog.admlog_log"));

// ������� ����������
function actionUpdate() {
    global $PHPShopModules;

    // ����� ������ ��
    $baseMap = array(
        'banner' => 'baners',
        'product' => 'products',
        'catalog' => 'categories'
    );

    // �������
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.admlog.admlog_log"));
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_POST['rowID'])));

    if (is_array($data)) {

        if (!empty($baseMap[$data['file']]))
            $baseName = $baseMap[$data['file']];
        else
            $baseName = $data['file'];

        $contentCode = unserialize($data['content']);

        if (is_array($contentCode)) {

            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base'][$baseName]);
            $PHPShopOrm->debug = false;
            //$PHPShopOrm->trace($contentCode);

            if (!empty($contentCode['delID'])) {
                $action = $PHPShopOrm->insert($contentCode);
                $nameHandler = '����� ��������';
            } else {

                $action = $PHPShopOrm->update($contentCode, array('id' => '=' . intval($_POST['rowID'])));
                $nameHandler = '����� ��������� �� '.PHPShopDate::dataV($data['date'], true);
            }

            // ����� ���
            include_once('writelog.php');
            setLog(false, $nameHandler);
        }
    }
    
    header('Location: ?path='.$_GET['path']);
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $TitlePage, $PHPShopSystem;


    $PHPShopGUI->action_button['������������ ������'] = array(
        'name' => '������������ ������',
        'action' => 'saveID',
        'class' => 'btn btn-default btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-refresh'
    );


    // �������
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));
    $contentTemp = unserialize($data['content']);
    
    if($data['file'] != 'modules')
        $button=array('������������ ������');
    else $button=null;
    
    $PHPShopGUI->setActionPanel('������ ������� ��������� �� '.PHPShopDate::dataV($data['date'], true), false, $button);

    // ���������� �������� 1
    //$Tab1 = $PHPShopGUI->setField("����:", $PHPShopGUI->setInput("text", "name_new", PHPShopDate::dataV($data['date'], true), "left", 150));
    $Tab1.=$PHPShopGUI->setField("������������:", $PHPShopGUI->setInput("text", "name_new", $data['user']));
    $Tab1.=$PHPShopGUI->setField("��������:", $PHPShopGUI->setInput("text", "name_new", $data['title']));

    // �������� ����������
    $titleSearch = array('content_new', 'description_new');
    if (is_array($contentTemp))
        foreach ($contentTemp as $key => $val) {
            if (in_array($key, $titleSearch) and !empty($contentTemp[$key])) {
                $contentMain = $contentTemp[$key];
                break;
            }
        }


    // �������� 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_temp');
    $oFCKeditor->Height = '280';
    $oFCKeditor->Value = $contentMain;

    $Tab2 = $oFCKeditor->AddGUI();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, true), array("����������", $Tab2));

    $ContentFooter.=$PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "saveID", "��������", "right", 70, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>