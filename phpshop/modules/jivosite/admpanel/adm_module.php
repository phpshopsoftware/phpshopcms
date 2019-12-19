<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.jivosite.jivosite_system"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm,$PHPShopModules;
    
    // ��������� �������
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    $PHPShopOrm->debug = false;

    if(isset($_POST['email_new']) && isset($_POST['userPassword_new'])) {

        $path = 'https://admin.jivosite.com/integration/install';

        $query['email'] = $_POST['email_new'];
        $query['userDisplayName'] = $_POST['display_name_new'];
        $query['userPassword'] = $_POST['userPassword_new'];
        $query['siteUrl'] = 'http://' . $_SERVER['SERVER_NAME'];
        $query['partnerId'] = "17863";
        $query['pricelist_id'] = '105';
        $authToken = md5(time() . 'http://' . $_SERVER['SERVER_NAME']);
        $query['authToken'] = $authToken;
        if (!$query['agent_id']) {
            $query['agent_id'] = 0;
        }
        $query['lang'] = 'ru';
        $content = http_build_query($query);

        if ( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $path);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
            $responce = curl_exec($curl);
            curl_close($curl);
        }
        if ($responce) {
            $_POST['widget_id_new'] = $responce;
        }
    }

    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id='.$_GET['id']);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->title = "��������� ������ JivoSite";

    // �������
    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField('�����', $PHPShopGUI->setInputText(false, 'email_new', $data['email'], 300));
    $Tab1 .= $PHPShopGUI->setField('������', $PHPShopGUI->setInput("password", 'userPassword_new', $data['userPassword'], false, 300));
    $Tab1 .= $PHPShopGUI->setField('��� ������������ � ����', $PHPShopGUI->setInputText(false, 'display_name_new', $data['display_name'], 300));

    $Info = '<h4>��� ������� ������� ������ �������� ����������:</h4>
        <ol>
        <li> ����������������� �� ����� <a href="https://www.jivosite.ru/?partner_id=17863&lang=ru&pricelist_id=105" target="_blank"> jivosite.com</a></li>
		<li> �������� �� ����� ������ � ���������������� �������.</li>
		<li> ���������� ��� ����� � �������� ��� � ���� "�����" �� ������� "��������" �������� ���� ��������� ������.
		<li> ���������� ��� ������ � �������� ��� � ���� "������" �� ������� "��������" �������� ���� ��������� ������.
		<li> ��������� ��������� ������.</li>
		</ol>';
    $Tab2 = $PHPShopGUI->setInfo($Info, '200px', '100%');

    // ����� �����������
    $Tab3 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1,true), array("����������", $Tab2), array("� ������", $Tab3));

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