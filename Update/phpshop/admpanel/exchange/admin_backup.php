<?php

$TitlePage = __("��������� ����������� � ��������������");

// ������� ����������
function actionSave() {
    global $PHPShopGUI, $PHPShopOrm, $result_message, $result_error_tracert, $link_db;

    $PHPShopOrm->debug = false;

    // ���������� ������ �� �����
    if (!empty($_POST['sql_text'])) {
        $sql_query = explode(";\r", $_POST['sql_text']);

        foreach ($sql_query as $v)
            $result = mysqli_query($link_db, $v);

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

    // ������ csv �� URL
    elseif (!empty($_POST['furl'])) {
        $csv_file = $_POST['furl'];
        $path_parts = pathinfo($csv_file);
        $csv_file_name = $path_parts['basename'];
    }

    // ������ csv �� ��������� ���������
    elseif (!empty($_POST['lfile'])) {
        $csv_file = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $_POST['lfile'];
        $path_parts = pathinfo($csv_file);
        $csv_file_name = $path_parts['basename'];
    }


    // ��������� csv
    if (!empty($csv_file)) {
        $result_error_tracert = null;

        $sql_file_content = file_get_contents($csv_file);

        $sql_query = explode(";\r", $sql_file_content);

        foreach ($sql_query as $v) {

            if (strlen($v) > 10)
                $result = mysqli_query($link_db, $v);

            if (!$result)
                $result_error_tracert.= '������: ' . $v . '
������: ' . mysqli_error($link_db);
        }

        // ��������� �������
        if (empty($result_error_tracert))
            $result_message = $PHPShopGUI->setAlert('SQL ������ ������� ��������');
        else {
            $result_message = $PHPShopGUI->setAlert('SQL ������: ' . mysqli_error($link_db), 'danger');
        }
    }




    //return true;
}

function getFileInfo($file) {
    global $PHPShopInterface;
    static $i;
    $i++;
    $stat = stat("./dumper/backup/" . $file);
    $PHPShopInterface->setRow($i, array('name' => $file, 'link' => '?path=exchange.backup&file=' . $file, 'align' => 'left'), array('name' => PHPShopDate::get($stat['mtime'], true), 'order' => $stat['mtime']), '<span class="hide">' . $stat['mtime'] . '</span>', array('action' => array('load', 'restore', '|', 'delete', 'id' => $i), 'align' => 'center'), array('name' => number_format($stat['size']/ 1024/ 1024, 2, ',', ' ') . ' MB', 'align' => 'right', 'order' => $stat['size']));
}

// ��������� ���
function actionStart() {
    global $PHPShopInterface, $TitlePage, $PHPShopModules;

    $PHPShopInterface->action_select['��������������'] = array(
        'name' => '�������������� ��',
        'action' => 'sql-optim'
    );

    $PHPShopInterface->action_select['�������'] = array(
        'name' => '������� ����',
        'action' => 'load'
    );

    $PHPShopInterface->action_button['�������� �����'] = array(
        'name' => '',
        'action' => 'addNew',
        'class' => 'btn btn-default btn-sm navbar-btn',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-plus',
        'tooltip' => 'data-toggle="tooltip" data-placement="left" title="������� ��������� �����"'
    );

    $PHPShopInterface->action_title['load'] = '�������';
    $PHPShopInterface->action_title['restore'] = '������������';
    $PHPShopInterface->action_title['deletefile'] = '������� ����';


    $PHPShopInterface->addJSFiles('./exchange/gui/exchange.gui.js');
    $PHPShopInterface->setActionPanel($TitlePage, array('��������������', '�������', '|', '������� ���������'), array('�������� �����'));
    $PHPShopInterface->setCaption(array(null, "4%"), array("��� �����", "40%"), array("����", "15%"), array('', '1%', array('sort' => 'none')), array("", "7%"), array("������ �����", "15%", array('align' => 'right')));
    PHPShopFile::searchFile("./dumper/backup/", 'getFileInfo');

    $help = '<p class="text-muted">���� ������ ����� ��������� ����� �� ����� 100 MB, �� ������������� ���������� ������� ��������� ������ � ��������� ����������� ���� ������ ����� ������ <kbd>������������</kbd>.</p>';

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, false);

    $sidebarleft[] = array('title' => '���������', 'content' => $PHPShopInterface->loadLib('tab_menu_service', false, './exchange/'));
    $sidebarleft[] = array('title' => '���������', 'content' => $help);

    $PHPShopInterface->setSidebarLeft($sidebarleft, 2);

    $PHPShopInterface->Compile(2);
    return true;
}

// �������� �����
if (isset($_GET['file']) and PHPShopSecurity::true_skin($_GET['file'])) {
    header("Content-Description: File Transfer");
    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename=' . $_GET['file']);
    header("Content-Transfer-Encoding: binary");
    header('Content-Length: ' . filesize("./dumper/backup/" . $_GET['file']));
    readfile("./dumper/backup/" . $_GET['file']);
}

// ��������� �������
$PHPShopInterface->getAction();
?>