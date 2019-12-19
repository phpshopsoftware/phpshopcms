<?php

// ���������
$TitlePage = __("��������");
PHPShopObj::loadClass('page');

/**
 * ����� �������
 */
function actionStart() {
    global $PHPShopInterface, $TitlePage;

    $PHPShopCategoryArray = new PHPShopPageCategoryArray();
    $CategoryArray = $PHPShopCategoryArray->getArray();

    if (!empty($CategoryArray[$_GET['cat']]['name']))
        $catname = " / " . $CategoryArray[$_GET['cat']]['name'];


    $PHPShopInterface->action_select['������������� �������'] = array(
        'name' => '������������� �������',
        'url' => '?path=' . $_GET['path'] . '&id=' . intval($_GET['cat'])
    );

    $PHPShopInterface->action_select['����� �������'] = array(
        'name' => '����� �������',
        'url' => '?path=' . $_GET['path'] . '&action=new',
        'class' => 'enabled'
    );

    if (empty($_GET['cat']))
        $PHPShopInterface->action_select['������������� �������']['class'] = 'disabled';


    $PHPShopInterface->action_title['copy'] = '������� �����';
    $PHPShopInterface->action_title['url'] = '������� URL';

    $PHPShopInterface->action_button['�������� ��������'] = array(
        'name' => '',
        'action' => 'addNew',
        'class' => 'btn btn-default btn-sm navbar-btn',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-plus',
        'tooltip' => 'data-toggle="tooltip" data-placement="left" title="�������� ��������" data-cat="' . $_GET['cat'] . '"'
    );


    $PHPShopInterface->setActionPanel($TitlePage . $catname, array('����� �������', '������������� �������', '|', '������� ���������'), array('�������� ��������'));
    $PHPShopInterface->setCaption(
            array(null, "3%"), array("������", "15%"), array("��������", "40%"), array("", "7%"), array("������" . "", "7%", array('align' => 'right'))
    );

    $PHPShopInterface->addJSFiles('./js/jquery.treegrid.js', './page/gui/page.gui.js');


    $where = false;
    if (!empty($_GET['cat'])) {
        $where = array('category' => '=' . intval($_GET['cat']));
    }

    // ����������� �����
    if (is_array($_GET['where'])) {
        foreach ($_GET['where'] as $k => $v) {

            if (isset($v) and $v != '') {

                $where[PHPShopSecurity::TotalClean($k)] = " LIKE '%". PHPShopSecurity::TotalClean($v) . "%'";
            }
        }
    }


    // ������� � �������
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page']);
    $PHPShopOrm->Option['where'] = ' or ';
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {

            // Enabled
            if (empty($row['enabled']))
                $enabled = 'text-muted';
            else
                $enabled = null;

            $PHPShopInterface->path = 'page&return=page.catalog';
            $PHPShopInterface->setRow(
                    $row['id'], array('name' => $row['link'], 'link' => '?path=page&return=' . $_GET['path'] . '&id=' . $row['id'], 'align' => 'left', 'class' => 'page-url ' . $enabled), array('name' => $row['name'], 'link' => '?path=page&return=' . $_GET['path'] . '&id=' . $row['id'], 'align' => 'left', 'class' => $enabled), array('action' => array('edit', 'url', '|', 'delete', 'id' => $row['id']), 'align' => 'center'), array('status' => array('enable' => intval($row['enabled']), 'align' => 'right', 'caption' => array('����', '���')))
            );
        }

    // ����� ������� ������ ���������
    $CategoryArray[0]['name'] = '������';
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

    $PHPShopInterface->path = 'page';

    $tree = '<table class="tree table table-hover">
         <tr class="treegrid-0">
           <td><a href="?path=' . $_GET['path'] . '">��� ��������</a></td>
	</tr>';
    if (is_array($tree_array[0]['sub']))
        foreach ($tree_array[0]['sub'] as $k => $v) {
            $check = treegenerator($tree_array[$k], $k);
            if (empty($check))
                $tree.='<tr class="treegrid-' . $k . ' data-tree">
		<td><a href="?path=' . $_GET['path'] . '&cat=' . $k . '">' . $v . '</a><span class="pull-right">' . $PHPShopInterface->setDropdownAction(array('edit', 'delete', 'id' => $k)) . '</span></td>
	</tr>';
            else
                $tree.='<tr class="treegrid-' . $k . ' data-tree">
		<td><a href="#" class="treegrid-parent" data-parent="treegrid-' . $k . '">' . $v . '</a><span class="pull-right">' . $PHPShopInterface->setDropdownAction(array('edit', 'delete', 'id' => $k)) . '</span></td>
	</tr>';
            $tree.=$check;
        }
    $tree.='
        <tr class="treegrid-100000">
           <td><a href="#" class="treegrid-parent" data-parent="treegrid-100000">����</a></td>
	</tr>
         <tr class="treegrid-1000 treegrid-parent-100000 data-row">
           <td><a href="?path=' . $_GET['path'] . '&cat=1000">������� ���� �����</a></td>
	</tr>
        <tr class="treegrid-2000 treegrid-parent-100000">
           <td><a href="?path=' . $_GET['path'] . '&cat=2000">��������� ��������</a></td>
	</tr>
</table>
  <script>
    var cat="' . intval($_GET['cat']) . '";
    </script>';




    $sidebarleft[] = array('title' => '���������', 'content' => $tree, 'title-icon' => '<span class="glyphicon glyphicon-plus new" data-toggle="tooltip" data-placement="top" title="�������� �������"></span>&nbsp;<span class="glyphicon glyphicon-chevron-down" data-toggle="tooltip" data-placement="top" title="���������� ���"></span>&nbsp;<span class="glyphicon glyphicon-chevron-up" data-toggle="tooltip" data-placement="top" title="��������"></span>');
    $PHPShopInterface->setSidebarLeft($sidebarleft, 3);

    $PHPShopInterface->Compile(3);
}

// ���������� ������ ���������
function treegenerator($array, $parent) {
    global $PHPShopInterface, $tree_array;
    $tree = $check = false;
    $PHPShopInterface->path = $_GET['path'];
    if (is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {
            $check = treegenerator($tree_array[$k], $k);

            if (empty($check))
                $tree.='<tr class="treegrid-' . $k . ' treegrid-parent-' . $parent . ' data-tree">
		<td><a href="?path=' . $_GET['path'] . '&cat=' . $k . '">' . $v . '</a><span class="pull-right">' . $PHPShopInterface->setDropdownAction(array('edit', 'delete', 'id' => $k)) . '</span></td>
	</tr>';
            else
                $tree.='<tr class="treegrid-' . $k . ' treegrid-parent-' . $parent . ' data-tree">
		<td><a href="#" class="treegrid-parent" data-parent="treegrid-' . $k . '">' . $v . '</a><span class="pull-right">' . $PHPShopInterface->setDropdownAction(array('edit', 'delete', 'id' => $k)) . '</span></td>
	</tr>';

            $tree.=$check;
        }
    }
    return $tree;
}

?>