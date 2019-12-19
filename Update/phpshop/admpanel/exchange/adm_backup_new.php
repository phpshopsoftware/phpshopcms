<?php

$TitlePage = __('�������� ��������� ����� ����');

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopModules;

    $PHPShopGUI->action_button['�������'] = array(
        'name' => '�������',
        'action' => 'saveID',
        'class' => 'btn  btn-default btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-download-alt'
    );


    // ������ �������� ����
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./exchange/gui/exchange.gui.js');
    $PHPShopGUI->setActionPanel(__("�������� ��������� ����� ����"), false, array('�������'));

    $structure_value[] = array('��������� � ������', '0', 'selected');
    $structure_value[] = array('������ ���������', '1', '');

    // ������ ������
    foreach ($GLOBALS['SysValue']['base'] as $val) {
        if (is_array($val)) {
            foreach ($val as $mod_base)
                $baseArray[$mod_base] = $mod_base;
        }
        else
            $baseArray[$val] = $val;
    }


    foreach ($baseArray as $val) {
        $table.='<option value="' . $val . '" selected class="">' . $val . '</option>';
    }

    // ���������� �������� 1
    $PHPShopGUI->_CODE.= $PHPShopGUI->setCollapse('���������', $PHPShopGUI->setField('�������', '
        <table >
        <tr>
        <td>
        <select id="pattern_table" style="height:300px;width:500px" name="pattern_table[]" multiple class="form-control" required>' . $table . '</select>
        </td>
        <td>&nbsp;</td>
        <td class="text-center"><a class="btn btn-default btn-sm" href="#" id="select-all" data-toggle="tooltip" data-placement="top" title="������� ���"><span class="glyphicon glyphicon-chevron-left"></span></a><br><br>
        <a class="btn btn-default btn-sm" id="select-none" href="#" data-toggle="tooltip" data-placement="top" title="������ ��������� �� ����"><span class="glyphicon glyphicon-chevron-right"></span></a></td>
        </tr>
   </table>
            
' . $PHPShopGUI->setHelp('��� ������ ����� ����� ������ ������� ����� ������� ���� �� ������, ��������� ������� CTRL')) .
            $PHPShopGUI->setField('GZIP ������', $PHPShopGUI->setCheckbox('export_gzip', 1, '��������', 1), 1, '��������� ������ ������������ �����') .
            $PHPShopGUI->setField('�������� �����������', $PHPShopGUI->setSelect('export_structure', $structure_value, 300)));

    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, false);

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "��", "right", 70, "", "but", "actionCreate.exchange.edit");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ������
function actionCreate() {
    global $PHPShopModules;

    include_once('./dumper/dumper.php');
    
    $is_safe_mode = ini_get('safe_mode') == '1' ? 1 : 0;
    if (!$is_safe_mode) set_time_limit(600);

    ob_start();
    mysqlbackup($GLOBALS['SysValue']['connect']['dbase'], $_POST['export_structure'], $_POST['pattern_table']);
    $dump = ob_get_clean();
   

    // ����������
    if (!empty($_REQUEST['update']))
        $file = 'upload_dump.sql';
    else
        $file = 'base_' . date("d_m_y_His") . '.sql';

    $file = "./dumper/backup/" . $file;
    PHPShopFile::write($file, $dump);

    // Gzip
    if (!empty($_REQUEST['export_gzip'])) {
        PHPShopFile::gzcompressfile($file);
    }

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    if (empty($_REQUEST['update']))
        header('Location: ?path=' . $_GET['path']);
    else
        return array('success' => true);
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>