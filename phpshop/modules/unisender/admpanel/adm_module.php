<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.unisender.unisender_system"));

function actionBase() {
    global $_classPath;

    @set_time_limit(10000);

    $apikey = $_POST['key_new'];

    // ����� �������������
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['send_mail']);
    $data = $PHPShopOrm->select(array('*'), array('subscribe' => "='1'"), false, array('limit' => 100));
    if (is_array($data))
        foreach ($data as $user) {

            // ����� ����������
            $key[] = $user['id'];
            $new_emails[] = $user['mail'];
            $new_names[] = $user['name'];
            $new_phone[] = $user['tel'];
        }


    if (!empty($new_emails)) {

        // ������ POST-������
        $query_array = array(
            'api_key' => $apikey,
            'field_names[0]' => 'email',
            'field_names[1]' => 'Name',
            'field_names[2]' => 'phone',
            'field_names[21]' => 'email_list_ids',
            'platform' => 'phpshop',
            'format' => 'json'
        );
        for ($i = 0; $i < count($new_emails); $i++) {
            $query_array['data[' . $i . '][0]'] = $new_emails[$i];
            $query_array['data[' . $i . '][1]'] = iconv('cp1251', 'utf-8', $new_names[$i]);
            $query_array['data[' . $i . '][2]'] = $new_phone[$i];
        }

        // ������������� ����������

        $fp = fsockopen("ssl://api.unisender.com", 443, $errno, $errstr, 30);
        $get_string = http_build_query($query_array);
        
        if (!$fp) {
            $api_uri = 'https://api.unisender.com/ru/api/importContacts';
            $result = file_get_contents($api_uri . '?' . $get_string);
        } else {

            $out = "GET /ru/api/importContacts?$get_string    HTTP/1.1\r\n";
            $out .= "Host: api.unisender.com\r\n";
            $out .= "Connection: Close\r\n\r\n";

            fwrite($fp, $out);
            $res = null;
            while (!feof($fp)) {
                $res.=fgets($fp, 128);
            }
            fclose($fp);

            $response = split("\r\n\r\n", $res);
            $header = $response[0];
            $responsecontent = $response[1];
            if (!(strpos($header, "Transfer-Encoding: chunked") === false)) {
                $aux = split("\r\n", $responsecontent);
                for ($i = 0; $i < count($aux); $i++)
                    if ($i == 0 || ($i % 2 == 0))
                        $aux[$i] = "";
                $responsecontent = implode("", $aux);
            }
            $result = chop($responsecontent);
        }

        if ($result) {
            // ����������� ����� API-�������
            $jsonObj = json_decode($result);

            if (null === $jsonObj) {

                // ������ � ���������� ������
                echo '<div class="alert alert-danger" id="rules-message"  role="alert">Invalid JSON</div>';
            } elseif (!empty($jsonObj->error)) {

                // ������ �������
                echo '<div class="alert alert-danger" id="rules-message"  role="alert">An error occured: ' . $jsonObj->error . '(code: ' . $jsonObj->code . ')</div>';
            } else {

                // ��������� ������������� �� �������������
                $PHPShopOrm->clean();
                $PHPShopOrm->debug = false;
                $id_list = implode(',', $key);
                if (!empty($id_list))
                    $PHPShopOrm->update(array('subscribe_new' => 2), array('id' => ' IN (' . $id_list . ')'));

                // ����� ���������� ������� ���������
                echo '<div class="alert alert-success" id="rules-message"  role="alert">���������. ��������� ' . $jsonObj->result->new_emails . ' ����� e-mail �������</div>';
            }
        } else {
            // ������ ���������� � API-��������
            echo '<div class="alert alert-danger" id="rules-message"  role="alert">������ API</div>';
        }
    }
    else
        echo '<div class="alert alert-info" id="rules-message"  role="alert">��� ����� ��������� ��� ��������</div>';
}

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules, $TitlePage, $select_name;

    // ����� ������������
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['send_mail']);
    $data_user = $PHPShopOrm->select(array('*'), array('subscribe' => "='1'"), false, array('limit' => 1000));
    $num_new_user = count($data_user);
    if ($num_new_user > 0)
        $new_user = '<span class=badge>' . $num_new_user . '</span>';
    else
        $new_user = false;


    if ($new_user) {
        $PHPShopGUI->action_button['�������������'] = array(
            'name' => '��������� ������������� ' . $new_user,
            'action' => 'loadBase',
            'class' => 'btn  btn-info btn-sm navbar-btn',
            'type' => 'submit',
            'icon' => 'glyphicon glyphicon-open'
        );
    }

    $PHPShopGUI->setActionPanel($TitlePage, $select_name, array('�������������', '��������� � �������'));

    // �������
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.unisender.unisender_system"));
    $data = $PHPShopOrm->select();

    $Tab1.=$PHPShopGUI->setField('���� ������� � API ', $PHPShopGUI->setInput('text.required', "key_new", $data['key'], false, 300));

    $Tab2 = $PHPShopGUI->setInfo('<p>������ ��������� ������������� ��������� ������ ����������� �� ��������-�������� � ������ ���������� ��������� ������ ����� ����� �������� <a href="http://unisender.com" target="_blank">UniSender.com</a>.</p>
<p>    
���� ������� � API ��� ������������� �������� ������ ����� �������� � ���������� �������� Unisender � �������� <kbd>���������� � API</kbd>.<br>����� <code>������� ������ API</code> ������ ���� � ������ <kbd>�������</kbd>.</p>');

    // ����� �����������
    $Tab3 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, true), array("����������", $Tab2), array("� ������", $Tab3,));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "loadBase", "���������", "right", 80, "", "but", "actionBase.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>