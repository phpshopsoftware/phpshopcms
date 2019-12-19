<?php

PHPShopObj::loadClass("category");


$TitlePage = __('����� ��������� �����������');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['photo_categories']);

// ���������� ������ ���������
function treegenerator($array, $i, $parent) {
    global $tree_array;
    $del = '�&nbsp;&nbsp;&nbsp;&nbsp;';
    $tree = $tree_select = $check = false;
    $del = str_repeat($del, $i);
    if (is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {

            $check = treegenerator($tree_array[$k], $i + 1, $k);


            if ($k == $_GET['parent_to'])
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


            $tree.='<tr class="treegrid-' . $k . ' treegrid-parent-' . $parent . ' data-tree">
		<td><a href="?path=photo.catalog&id=' . $k . '">' . $v . '</a></td>
                    </tr>';

            $tree_select.=$check['select'];
            $tree.=$check['tree'];
        }
    }



    return array('select' => $tree_select, 'tree' => $tree);
}

/**
 * ����� �������� ���� ��������������
 */
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopSystem;

    // ������ �������� ����
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./js/jquery.treegrid.js', './photo/gui/photo.gui.js');

    // ��������� ������
    $data = array();
    $data['num'] = 6;
    $data['enabled'] = 1;
    $data['name'] = __('����� �������');

    $PHPShopGUI->setActionPanel($TitlePage, false, array('������� � �������������', '��������� � �������'));

    // ������������
    $Tab_info = $PHPShopGUI->setField(__("��������:"), $PHPShopGUI->setInputArg(array('name' => 'name_new', 'type' => 'text.requared', 'value' => $data['name'])));


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
            if ($k == $data['parent_to'])
                $tree_array[$k]['selected'] = true;
        }



    $GLOBALS['tree_array'] = &$tree_array;
    $_GET['parent_to'] = $data['parent_to'];

    $tree_select = '<select class="selectpicker show-menu-arrow hidden-edit" data-container=""  data-style="btn btn-default btn-sm" name="parent_to_new" data-width="100%"><option value="0">' . $CategoryArray[0]['name'] . '</option>';
    $tree = '<table class="tree table table-hover">';
    if ($k == $data['parent_to'])
        $selected = 'selected';
    if (is_array($tree_array[0]['sub']))
        foreach ($tree_array[0]['sub'] as $k => $v) {
            $check = treegenerator($tree_array[$k], 1, $k);

            $tree.='<tr class="treegrid-' . $k . ' data-tree">
		<td><a href="?path=photo.catalog&id=' . $k . '">' . $v . '</a></td>
                    </tr>';

            if ($k == $data['parent_to'])
                $selected = 'selected';
            else
                $selected = null;

            $tree_select.='<option value="' . $k . '"  ' . $selected . '>' . $v . '</option>';

            $tree_select.=$check['select'];
            $tree.=$check['tree'];
        }
    $tree_select.='</select>';
    $tree.='</table>
        <script>
    var cat="' . intval($_GET['cat']) . '";
    </script>';


    // ����� ��������
    $Tab_info.= $PHPShopGUI->setField(__("����������"), $tree_select);

    $Tab_info.=$PHPShopGUI->setField(__("���������� ���� � ������"), $PHPShopGUI->setInputText(false, 'num_new', $data['num'], '100'));
    $Tab_info.=$PHPShopGUI->setField("���������:", $PHPShopGUI->setInput("text", "page_new", $data['page']) .
                    $PHPShopGUI->setHelp(__('* ������: /,/page/,/shop/UID_1.html. ����� ������� ��������� ������� ����� �������.')));

    $Tab1 = $PHPShopGUI->setCollapse(__('����������'), $Tab_info);

    $SelectValue[] = array('����� � ��������', 1, $data['enabled']);
    $SelectValue[] = array('�������������', 0, $data['enabled']);

    $Tab1.= $PHPShopGUI->setField("����� ������:", $PHPShopGUI->setSelect("enabled_new", $SelectValue, 300));

    // ��������
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $editor = new Editor('content_new');
    $editor->Height = '550';
    $editor->ToolbarSet = 'Normal';
    $editor->Value = $data['content'];
    $Tab2 = $editor->AddGUI();

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array(__("��������"), $Tab1), array(__("��������"), $Tab2));


    // ����� �������
    $sidebarleft[] = array('title' => '���������', 'content' => $tree, 'title-icon' => '<span class="glyphicon glyphicon-plus new" data-toggle="tooltip" data-placement="top" title="�������� �������"></span>&nbsp;<span class="glyphicon glyphicon-chevron-down" data-toggle="tooltip" data-placement="top" title="����������"></span>&nbsp;<span class="glyphicon glyphicon-chevron-up" data-toggle="tooltip" data-placement="top" title="��������"></span>');
    $PHPShopGUI->setSidebarLeft($sidebarleft, 3);
    $PHPShopGUI->sidebarLeftCell = 3;



    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "��", "right", 70, "", "but", "actionInsert.page.create");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * ����� ������
 * @return bool 
 */
function actionInsert() {
    global $PHPShopModules, $PHPShopOrm;


    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);

    if ($_POST['saveID'] == '������� � �������������')
        header('Location: ?path=photo.catalog&id=' . $action);
    else
        header('Location: ?path=photo.catalog&cat=' . $_POST['category_new']);

    return $action;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>