<?php

$TitlePage = __("SQL ������ � ����");

// �������� �����
$sqlHelper = array(
    'phpshop_system' => '��������� �����',
    "phpshop_gbook" => "������ � ����� �� �������� �����",
    "phpshop_news" => '�������',
    "phpshop_jurnal" => '������ ����������� ���������������',
    "phpshop_pages" => '�������� ����� (������� ����, �������� � �.�.)',
    "phpshop_menu" => '��������� �������������� �����',
    "phpshop_banners" => '��������� �������',
    "phpshop_links" => '�������� ������',
    "phpshop_search_jurnal" => '������ ������ �� �����',
    "phpshop_users" => '�������������� �����',
    "phpshop_page_categories" => '��������� �������',
    "phpshop_photo" => '�����������',
    "phpshop_modules" => '������������ �������������� ������',
    "phpshop_newsletter" => '������ ��������',
    "phpshop_slider" => '������� �� ������� ��������'
);

// ������� ����������
function actionSave() {
    global $PHPShopGUI, $result_message, $result_error_tracert, $link_db;

    // ���������� ������ �� �����
    if (!empty($_POST['sql_text'])) {
        $sql_query = explode(";\r", trim($_POST['sql_text']));

        foreach ($sql_query as $v)
            $result = mysqli_query($link_db, trim($v));

        // ��������� �������
        if ($result)
            $result_message = $PHPShopGUI->setAlert('SQL ������ ������� ��������');
        else {
            $result_message = $PHPShopGUI->setAlert('SQL ������: ' . mysqli_error($link_db), 'danger');
            $result_error_tracert = $_POST['sql_text'];
        }
    }

    // �������� csv �� ������������
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if ($_FILES['file']['ext'] == "sql") {
            if (move_uploaded_file($_FILES['file']['tmp_name'], "csv/" . $_FILES['file']['name'])) {
                $csv_file = "csv/" . $_FILES['file']['name'];
                $csv_file_name = $_FILES['file']['name'];
            }
            else
                $result_message = $PHPShopGUI->setAlert('������ ���������� ����� <strong>' . $csv_file_name . '</strong> � ����� phpshop/admpanel/csv', 'danger');
        }
    }

    // ������ ���� �� URL
    elseif (!empty($_POST['furl'])) {
        $csv_file = $_POST['furl'];
        $path_parts = pathinfo($csv_file);
        $csv_file_name = $path_parts['basename'];
    }

    // ������ ���� �� ��������� ���������
    elseif (!empty($_POST['lfile'])) {
        $csv_file = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $_POST['lfile'];
        $path_parts = pathinfo($csv_file);
        $csv_file_name = $path_parts['basename'];
    }


    // ��������� sql
    if (!empty($csv_file)) {
        $result_error_tracer = $error_line = null;

        // GZIP
        if ($path_parts['extension'] == 'gz') {
            $zd = gzopen($csv_file, "r");
            $sql_file_content = gzread($zd, 1024 * 512);
            gzclose($zd);
        }
        else
            $sql_file_content = file_get_contents($csv_file);

        $sql_query = explode(";\r", $sql_file_content);
        $count = count($sql_query);
        if ($count < 1)
            $sql_query = explode(";", $sql_file_content);


        foreach ($sql_query as $k => $v) {

            if (strlen($v) > 10)
                $result = mysqli_query($link_db, $v);

            if (!$result) {
                $error_line.='[Line ' . $k . '] ';
                $result_error_tracert.= '������: ' . $v . '
������: ' . mysqli_error($link_db);
            }
        }

        // ��������� �������
        if (empty($result_error_tracert)) {
            if (!empty($_POST['ajax']))
                return array("success" => true);
            else
                $result_message = $PHPShopGUI->setAlert('SQL ������ ������� ��������');
        }
        else {
            if (!empty($_POST['ajax']))
                return array("success" => false, "error" => mysqli_error($link_db) . ' -> ' . $error_line);
            else
                $result_message = $PHPShopGUI->setAlert('SQL ������: ' . mysqli_error($link_db), 'danger');
        }
    }
}

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $TitlePage, $PHPShopModules, $result_message, $result_error_tracert, $PHPShopSystem, $selectModalBody, $sqlHelper;

    $PHPShopGUI->action_button['���������'] = array(
        'name' => '���������',
        'class' => 'btn btn-primary btn-sm navbar-btn ace-save',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-ok'
    );

    $bases = $DROP = $TRUNCATE = $selectModal = null;
    $baseArray = array();

    foreach ($GLOBALS['SysValue']['base'] as $val) {
        if (is_array($val)) {
            foreach ($val as $mod_base)
                $baseArray[$mod_base] = $mod_base;
        }
        else
            $baseArray[$val] = $val;
    }

    foreach ($baseArray as $val) {
        if (!empty($val)) {
            $bases.="`" . $val . "`, ";
            $DROP.='DROP TABLE ' . $val . ';
';
            if(!empty($sqlHelper[$val]))
            $selectModal.='<tr><td><kbd>' . $val . '</kbd></td><td>' . $sqlHelper[$val] . '</td></tr>';
        }
    }

    unset($baseArray['phpshop_system']);
    unset($baseArray['phpshop_users']);
    unset($baseArray['phpshop_valuta']);
    unset($baseArray['phpshop_citylist_country']);
    unset($baseArray['phpshop_citylist_region']);
    unset($baseArray['phpshop_citylist_city']);
    unset($baseArray['phpshop_modules_key']);


    $TRUNCATE = null;

    foreach ($baseArray as $val) {
        $TRUNCATE.='TRUNCATE `' . $val . '`;
';
    }

    $bases = substr($bases, 0, strlen($bases) - 2) . ';';

    // ������ �������� ����
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./exchange/gui/exchange.gui.js', './tpleditor/gui/ace/ace.js');

    $PHPShopGUI->_CODE = $result_message;
    $help = '<p class="text-muted">��� ������� ����-���� � ����-������� ������� ������� SQL ������� <kbd>�������� ����</kbd></p> <p class="text-muted">��� ���������� ������������������ ����� ������� SQL ������� <kbd>�������������� ����</kbd></p> <p class="text-muted">���������� �������� SQL ������ ��� �������� ��������� ������� �������� � <a href="https://help.phpshop.ru/knowledgebase/article/398" target="_blank" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-book"></span> ���� ������</a></p>';


    $PHPShopGUI->setActionPanel($TitlePage, false, array('���������'));

    if ($_GET['query'] == 'optimize')
        $optimize_sel = 'selected';
    else
        $optimize_sel = null;

    $query_value[] = array('������� SQL �������', 0, '');
    $query_value[] = array('�������������� ����', 'OPTIMIZE TABLE ' . $bases, $optimize_sel);
    $query_value[] = array('�������� ����', 'REPAIR TABLE ' . $bases, '');
    $query_value[] = array('������� ������� �������', 'DELETE FROM ' . $GLOBALS['SysValue']['base']['page_categories'] . ' WHERE ID=', '');
    $query_value[] = array('������� ��� �������� �������', 'TRUNCATE ' . $GLOBALS['SysValue']['base']['page_categories'], '');
    $query_value[] = array('������� ��� ��������', 'TRUNCATE ' . $GLOBALS['SysValue']['base']['page'], '');
    $query_value[] = array('������� �������� � ��������', 'DELETE FROM ' . $GLOBALS['SysValue']['base']['page'] . ' WHERE category=', '');
    $query_value[] = array('������� ��������', 'DELETE FROM ' . $GLOBALS['SysValue']['base']['page'] . ' WHERE ID=', '');
    $query_value[] = array('�������� ����', $TRUNCATE, '');
    $query_value[] = array('���������� ����', $DROP, '');

    // ����������� �� ������
    if ($_GET['query'] == 'optimize')
        $result_error_tracert = 'OPTIMIZE TABLE ' . $bases;

    // ����
    $theme = $PHPShopSystem->getSerilizeParam('admoption.ace_theme');
    if (empty($theme))
        $theme = 'dawn';

    $PHPShopGUI->_CODE.= '<textarea class="hide hidden-edit" id="editor_src" name="sql_text" data-mod="sql" data-theme="' . $theme . '">' . $result_error_tracert . '</textarea><pre id="editor">��������...</pre>';

    $PHPShopGUI->_CODE.= '<div class="text-right data-row"><a href="#" id="vartable" data-toggle="modal" data-target="#selectModal" data-title="�������� �������"><span class="glyphicon glyphicon-question-sign"></span>�������� ������</a></div>';

    // ��������� ���� ������� �������� ���������
    $selectModalBody = '<table class="table table-striped"><tr><th>�������</th><th>��������</th></tr>' . $selectModal . '</table>';

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('���������', $PHPShopGUI->setField('�������', $PHPShopGUI->setSelect('sql_query', $query_value)) .
            $PHPShopGUI->setField(__("����"), $PHPShopGUI->setFile()), 'in', false, true
    );

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, false);


    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("hidden", "saveID", "���������", "right", 80, "", "but", "actionSave.system.edit");

    $PHPShopGUI->setFooter($ContentFooter);

    // �����
    $sidebarleft[] = array('title' => '���������', 'content' => $PHPShopGUI->loadLib('tab_menu_service', false, './exchange/'));
    $sidebarleft[] = array('title' => '���������', 'content' => $help);
    $PHPShopGUI->setSidebarLeft($sidebarleft, 2);
    $PHPShopGUI->Compile(2);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();
?>