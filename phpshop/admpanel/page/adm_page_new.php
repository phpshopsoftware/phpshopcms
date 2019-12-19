<?php

PHPShopObj::loadClass("page");
PHPShopObj::loadClass("string");

$TitlePage = __('����� ��������');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page']);

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
    global $PHPShopGUI, $PHPShopSystem, $PHPShopModules, $TitlePage;

    // ��������� ������
    $data = array();
    $data['num'] = 1;

    $PHPShopGUI->action_select['����'] = array(
        'name' => '��������',
        'action' => 'presentation',
        'icon' => 'glyphicon glyphicon-education'
    );

    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->setActionPanel($TitlePage, array('����'), array('������� � �������������', '��������� � �������'));
    $PHPShopGUI->addJSFiles('./js/bootstrap-tour.min.js', './page/gui/tour.gui.js', './page/gui/page.gui.js');


    $PHPShopCategoryArray = new PHPShopPageCategoryArray();
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

    $tree_array[0]['sub'][1000] = '������� ���� �����';
    $tree_array[0]['sub'][2000] = '��������� ��������';

    $tree_select.='<option value="0" ' . $data['category'] . ' data-subtext="<span class=\'glyphicon glyphicon-cog\'></span> ���������">���������� ��������</option>';
    if (is_array($tree_array[0]['sub']))
        foreach ($tree_array[0]['sub'] as $k => $v) {
            $check = treegenerator($tree_array[$k], 1, $data['category']);

            if ($k == $data['category'])
                $selected = 'selected';
            else
                $selected = null;

            if (in_array($k, array(1000, 2000)))
                $subtext = 'data-subtext="<span class=\'glyphicon glyphicon-cog\'></span> ���������"';
            else
                $subtext = null;

            $tree_select.='<option value="' . $k . '" ' . $selected . ' ' . $subtext . '>' . $v . '</option>';

            $tree_select.=$check['select'];
        }
    $tree_select.='</select>';


    // �������� 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '550';
    $oFCKeditor->Value = $data['content'];

    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setCollapse(__('����������'), $PHPShopGUI->setField(__("����������:"), $tree_select) .
            $PHPShopGUI->setField("���������:", $PHPShopGUI->setInput("text.requared", "name_new", $data['name'])) .
            $PHPShopGUI->setField("����������:", $PHPShopGUI->setInputText("�", "num_new", $data['num'], 150)) .
            $PHPShopGUI->setField("URL ������:", $PHPShopGUI->setInputText('/page/', "link_new", $data['link'], 300, '.html')));

    $SelectValue[] = array('����� � ��������', 1, $data['enabled']);
    $SelectValue[] = array('�������������', 0, $data['enabled']);
    //$SelectValue[] = array('���������� ��������', 2, $data['enabled']);
    $Tab1.= $PHPShopGUI->setField("����� ������:", $PHPShopGUI->setSelect("enabled_new", $SelectValue, 300));

    // ���������� �������� 3
    $Tab3 = $PHPShopGUI->setField("Title: ", $PHPShopGUI->setTextarea("title_new", $data['title']));
    $Tab3.=$PHPShopGUI->setField("Description: ", $PHPShopGUI->setTextarea("description_new", $data['description']));
    $Tab3.=$PHPShopGUI->setField("Keywords: ", $PHPShopGUI->setTextarea("keywords_new", $data['keywords']));


    $Tab1.=$PHPShopGUI->setCollapse(__('SEO / ����-������'), $Tab3);
    
        // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1), array("����������", $oFCKeditor->AddGUI()));



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

    // ������������� ������ ��������
    $PHPShopOrm->updateZeroVars('enabled_new', 'secure_new');

    if (empty($_POST['link_new']))
        $_POST['link_new'] = PHPShopString::toLatin($_POST['name_new']);

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);

    if ($_POST['saveID'] == '������� � �������������')
        header('Location: ?path=page&return=page.catalog&id=' . $action);
    else
        header('Location: ?path=page.catalog&cat=' . $_POST['category_new']);

    return $action;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>
