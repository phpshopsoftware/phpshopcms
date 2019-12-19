<?php

// Заголовок
$TitlePage = __("Фотогалерея");
PHPShopObj::loadClass('category');

/**
 * Вывод товаров
 */
function actionStart() {
    global $PHPShopInterface, $TitlePage;

    $PHPShopCategoryArray = new PHPShopPhotoCategoryArray();
    $CategoryArray = $PHPShopCategoryArray->getArray();

    if (!empty($CategoryArray[$_GET['cat']]['name']))
        $catname = " / " . $CategoryArray[$_GET['cat']]['name'];


    $PHPShopInterface->action_select['Редактировать каталог'] = array(
        'name' => 'Редактировать каталог',
        'url' => '?path=' . $_GET['path'] . '&id=' . intval($_GET['cat'])
    );

    $PHPShopInterface->action_select['Новый каталог'] = array(
        'name' => 'Новый каталог',
        'url' => '?path=' . $_GET['path'] . '&action=new',
        'class' => 'enabled'
    );

    if (empty($_GET['cat']))
        $PHPShopInterface->action_select['Редактировать каталог']['class'] = 'disabled';


    $PHPShopInterface->action_title['copy'] = 'Сделать копию';

    $PHPShopInterface->action_button['Добавить фото'] = array(
        'name' => '',
        'action' => 'addNew',
        'class' => 'btn btn-default btn-sm navbar-btn',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-plus',
        'tooltip' => 'data-toggle="tooltip" data-placement="left" title="Добавить фото" data-cat="' . $_GET['cat'] . '"'
    );


    $PHPShopInterface->setActionPanel($TitlePage . $catname, array('Новый каталог', 'Редактировать каталог', '|', 'Удалить выбранные'), array('Добавить фото'));
    $PHPShopInterface->setCaption(
            array(null, "3%"), array("Иконка", "10%"), array("Название", "60%"), array("", "7%"), array("Статус" . "", "7%", array('align' => 'right'))
    );

    $PHPShopInterface->addJSFiles('./js/jquery.treegrid.js', './photo/gui/photo.gui.js');


    $where = false;
    if (!empty($_GET['cat'])) {
        $where = array('category' => '=' . intval($_GET['cat']));
    }


    // Таблица с данными
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['photo']);
    $PHPShopOrm->Option['where'] = ' or ';
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {

            if (!empty($row['name']))
                $icon = '<img src="' . $row['name'] . '" onerror="imgerror(this)" class="media-object" lowsrc="./images/no_photo.gif">';
            else
                $icon = '<img class="media-object" src="./images/no_photo.gif">';

            // Enabled
            if (empty($row['enabled']))
                $enabled = 'text-muted';
            else
                $enabled = null;

            $PHPShopInterface->path = 'photo&return=photo.catalog';
            $PHPShopInterface->setRow(
                    $row['id'], array('name' => $icon, 'link' => '?path=photo&return=' . $_GET['path'] . '&id=' . $row['id'], 'align' => 'left', 'class' => 'page-url ' . $enabled), array('name' => $row['info'], 'link' => '?path=photo&return=' . $_GET['path'] . '&id=' . $row['id'], 'align' => 'left', 'class' => $enabled), array('action' => array('edit', '|', 'delete', 'id' => $row['id']), 'align' => 'center'), array('status' => array('enable' => intval($row['enabled']), 'align' => 'right', 'caption' => array('Выкл', 'Вкл')))
            );
        }

    // Левый сайдбар дерева категорий
    $CategoryArray[0]['name'] = 'Корень';
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

    $PHPShopInterface->path = 'photo';

    $tree = '<table class="tree table table-hover">
         <tr class="treegrid-0">
           <td><a href="?path=' . $_GET['path'] . '">Все фотогалереи</a></td>
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
</table>
  <script>
    var cat="' . intval($_GET['cat']) . '";
    </script>';




    $sidebarleft[] = array('title' => 'Категории', 'content' => $tree, 'title-icon' => '<span class="glyphicon glyphicon-plus new" data-toggle="tooltip" data-placement="top" title="Добавить каталог"></span>&nbsp;<span class="glyphicon glyphicon-chevron-down" data-toggle="tooltip" data-placement="top" title="Развернуть все"></span>&nbsp;<span class="glyphicon glyphicon-chevron-up" data-toggle="tooltip" data-placement="top" title="Свернуть"></span>');
    $PHPShopInterface->setSidebarLeft($sidebarleft, 3);

    $PHPShopInterface->Compile(3);
}

// Построение дерева категорий
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