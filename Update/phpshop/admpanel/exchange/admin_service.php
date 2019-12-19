<?php

$TitlePage = __("������������ � �����������");

// ����������� �������
$check_array = array('phpshop_1c_jurnal', 'phpshop_search_jurnal', 'phpshop_rssgraber_jurnal', 'phpshop_jurnal', 'phpshop_modules_guard_log', 'phpshop_modules_guard_crc', 'phpshop_modules_stat_visitors', 'phpshop_modules_productlastview_memory', 'phpshop_modules_admlog_log', 'phpshop_modules_errorlog_log', 'phpshop_modules_visualcart_memory', 'phpshop_modules_cron_log');

// ������� ����������
function actionSave() {
    global $check_array;

    $PHPShopOrm = new PHPShopOrm();
    $PHPShopOrm->debug = false;
    $result = false;
    if (in_array( $_REQUEST['table'], $check_array)) {
        $result = $PHPShopOrm->query('TRUNCATE `' . $_REQUEST['table'] . '`;');
    }

    exit(json_encode(array('success' => $result)));
}

// ��������� ���
function actionStart() {
    global $PHPShopInterface, $TitlePage, $PHPShopModules, $check_array;

    // ����� �����
    $check_rows = 3000;

    // ����� �������
    $check_size = 1024*10;

    $PHPShopInterface->action_select['��������������'] = array(
        'name' => '�������������� ��',
        'action' => 'sql-optim enabled'
    );


    $PHPShopInterface->action_select['�������� ���������'] = array(
        'name' => '�������� ���������',
        'action' => 'sql-clean',
        'class' => 'disabled'
    );

    $PHPShopInterface->addJSFiles('./exchange/gui/exchange.gui.js');
    $PHPShopInterface->setActionPanel($TitlePage, array('��������������', '|', '�������� ���������'), false);
    $PHPShopInterface->setCaption(array(null, "4%"), array("��� ��������� �������", "30%"), array("������", "20%"), array("��������", "15%"), array("������", "15%", array('align' => 'right')));

    // ������� � �������
    $PHPShopOrm = new PHPShopOrm();
    $PHPShopOrm->sql = 'SELECT table_name AS `table`, table_rows as `num`, 
round(((data_length + index_length) / 1024), 2) `size`  
FROM information_schema.TABLES
WHERE table_schema = "' . $GLOBALS['SysValue']['connect']['dbase'] . '" order by table_rows desc';
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select();
    if (is_array($data))
        foreach ($data as $row) {
            if (in_array($row['table'], $check_array)) {

                if ($row['num'] > $check_rows or intval($row['size']) > $check_size){
                    $num = array('name'=>$row['num'],'class'=>'text-danger');
                    $size = array('name'=>$row['size']. ' KB','class'=>'text-danger', 'align' => 'right');
                    $status = '<a href="#" class="clean-base"><span class="glyphicon glyphicon-trash hidden-xs"></span>��������</a>';
                }
                else{
                    $status = null;
                    $num = array('name'=>$row['num'],'class'=>'text-success');
                    $size = array('name'=>$row['size']. ' KB','class'=>'text-success', 'align' => 'right');
                }
                

                $PHPShopInterface->setRow($row['id'], array('name' => $row['table'], 'align' => 'left'), $num, array('name' => $status), $size);
            }
        }

    $help = '<p class="text-muted">��������� ������� ��������� � ������� � ��������. ��� ���������� �������� ����� ����� ������������ ������� � �������������� ��.</p><p class="text-muted">��� ������� ��������� ������� � ������ ������� ������ ������ <kbd>��������</kbd></p> <p class="text-muted">��� ���������� ������������������ ����� ������� SQL ������� <kbd>�������������� ��</kbd></p>';


    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, false);

    $sidebarleft[] = array('title' => '���������', 'content' => $PHPShopInterface->loadLib('tab_menu_service', false, './exchange/'));
    $sidebarleft[] = array('title' => '���������', 'content' => $help);
    $PHPShopInterface->setSidebarLeft($sidebarleft, 2);

    // �����
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
$PHPShopGUI->getAction();
?>