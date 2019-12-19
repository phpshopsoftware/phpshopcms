<?php

PHPShopObj::loadClass("category");

$TitlePage = __('�������������� ���� #' . $_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['photo']);

// ���������� ������ ���������
function treegenerator($array, $i, $curent) {
    global $tree_array;
    $del = '�&nbsp;&nbsp;&nbsp;&nbsp;';
    $tree_select = $check = false;

    $del = str_repeat($del, $i);
    if (is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {

            $check = treegenerator($tree_array[$k], $i + 1, $curent);

            if ($k == $curent)
                $selected = 'selected';
            else
                $selected = null;

            if (empty($check['select'])) {
                $tree_select.='<option value="' . $k . '" ' . $selected . '>' . $del . $v . '</option>';
                $i = 1;
            } else {
                $tree_select.='<option value="' . $k . '" ' . $selected . '>' . $del . $v . '</option>';
                //$i++;
            }

            $tree_select.=$check['select'];
        }
    }
    return array('select' => $tree_select);
}

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopModules, $PHPShopOrm;

    // �������
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    // ��� ������
    if (!is_array($data)) {
        header('Location: ?path=' . $_GET['return']);
    }

    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->setActionPanel(__("����") . ': ' . $data['info'], array('�������', '|', '�������'), array('���������', '��������� � �������'));

    $PHPShopCategoryArray = new PHPShopPhotoCategoryArray();
    $CategoryArray = $PHPShopCategoryArray->getArray();

    $CategoryArray[0]['name'] = '- �������� ������� -';

    $tree_array = array();

    $PHPShopCategoryArrayKey = $PHPShopCategoryArray->getKey('parent_to.id', true);
    if (is_array($PHPShopCategoryArrayKey))
        foreach ($PHPShopCategoryArrayKey as $k => $v) {
            foreach ($v as $cat) {
                $tree_array[$k]['sub'][$cat] = $CategoryArray[$cat]['name'];
            }
            $tree_array[$k]['name'] = $CategoryArray[$k]['name'];
            $tree_array[$k]['id'] = $k;
        }


    $GLOBALS['tree_array'] = &$tree_array;

    $tree_select = '<select class="selectpicker show-menu-arrow hidden-edit" data-container=""  data-style="btn btn-default btn-sm" name="category_new" data-width="100%">';

    if (is_array($tree_array[0]['sub']))
        foreach ($tree_array[0]['sub'] as $k => $v) {
            $check = treegenerator($tree_array[$k], 1, $data['category']);

            if ($k == $data['category'])
                $selected = 'selected';
            else
                $selected = null;


            $tree_select.='<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';

            $tree_select.=$check['select'];
        }
    $tree_select.='</select>';


    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setField(__("����������:"), $tree_select) .
            $PHPShopGUI->setField(__("�����������"), $PHPShopGUI->setIcon($data['name'], "name_new", false, array('load' => false, 'server' => true, 'url' => false)), 1, '��������� ���� ����. ������ ���� ����� ������� �������������.') .
            $PHPShopGUI->setField("��������:", $PHPShopGUI->setInput("text", "info_new", $data['info'])) .
            $PHPShopGUI->setField("����������:", $PHPShopGUI->setInputText("�", "num_new", $data['num'], 150));

    $SelectValue[] = array('����� � ��������', 1, $data['enabled']);
    $SelectValue[] = array('�������������', 0, $data['enabled']);

    $Tab1.= $PHPShopGUI->setField("����� ������:", $PHPShopGUI->setSelect("enabled_new", $SelectValue, 300));

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1));

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "�������", "right", 70, "", "but", "actionDelete.page.edit") .
            $PHPShopGUI->setInput("submit", "editID", "���������", "right", 70, "", "but", "actionUpdate.page.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionSave.page.edit");


    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ����������
function actionUpdate() {
    global $PHPShopModules, $PHPShopOrm;

    $PHPShopOrm->debug = false;

    // ������������� ������ ��������
    $PHPShopOrm->updateZeroVars('enabled_new');

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));

    return array("success" => $action);
}

/**
 * ����� ����������
 */
function actionSave() {

    // ���������� ������
    actionUpdate();

    header('Location: ?path=photo.catalog');
}

// �������� ������ �����������
function fotoDelete($where = null) {

    if (!is_array($where))
        $where = array('id' => '=' . intval($_POST['rowID']));

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['photo']);
    $data = $PHPShopOrm->select(array('*'), $where, false, array('limit' => 100));
    if (is_array($data)) {
        foreach ($data as $row) {
            $name = $row['name'];
            $pathinfo = pathinfo($name);
            $oldWD = getcwd();
            $dirWhereRenameeIs = $_SERVER['DOCUMENT_ROOT'] . $pathinfo['dirname'];
            $oldFilename = $pathinfo['basename'];

            @chdir($dirWhereRenameeIs);
            @unlink($oldFilename);
            $oldFilename_s = str_replace(".", "s.", $oldFilename);
            @unlink($oldFilename_s);
            $oldFilename_big = str_replace(".", "_big.", $oldFilename);
            @unlink($oldFilename_big);
            @chdir($oldWD);
        }
    }
}

// ������� ��������
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    // �������� ������ �����������
    fotoDelete();

    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));

    return array("success" => $action);
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>
