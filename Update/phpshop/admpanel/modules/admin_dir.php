<?php

// Подключение подменю модулей
function modulesSubMenu($subpath) {
    global $TitlePage;

    $action_select['Удалить выбранные'] = array(
        'name' => 'Удалить выбранные',
        'action' => 'select',
        'class' => 'disabled',
        'url' => '#'
    );

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['modules']);
    $data = $PHPShopOrm->select(array('*'), array('path' => '="' . $subpath[2] . '"'), false, array('limit' => 1));

    if (is_array($data)) {
        $path = $data['path'];
        $menu = "../modules/" . $path . "/install/module.xml";
        $db = xml2array($menu, false, true);

        if (is_array($db['adminmenu']['podmenu'][0])) {
            foreach ($db['adminmenu']['podmenu'] as $val) {

                if (empty($subpath[3]) and $val['podmenu_action'] == $subpath[1] . '.' . $subpath[2])
                    $TitlePage = $val['podmenu_name'];
                elseif ($val['podmenu_action'] == $subpath[1] . '.' . $subpath[2] . '.' . $subpath[3])
                    $TitlePage = $val['podmenu_name'];
                else {
                    $action_select[$val['podmenu_name']] = array(
                        'name' => $val['podmenu_name'],
                        'url' => '?path=modules.' . $val['podmenu_action'],
                    );
                }
            }
        } else {
            $TitlePage = $db['adminmenu']['podmenu']['podmenu_name'];
        }

        $TitlePage.= '  ' . $db['name'];
    }


    $action_select['|'] = array(
        'name' => '|',
        'action' => 'divider',
    );

    $action_select['Настройки'] = array(
        'name' => 'Настройки',
        'url' => '?path=modules&id=' . $subpath[2]
    );

    return $action_select;
}

// Подраздел [cat.sub]
if (strpos($_GET['path'], '.')) {
    $subpath = explode(".", $_GET['path']);
}

$PHPShopModules->path = $subpath[2];
$mod_podmenu = modulesSubMenu($subpath);
$PHPShopInterface->action_select = $mod_podmenu;

foreach ($mod_podmenu as $title) {
    $select_name[] = $title['name'];
}

$PHPShopInterface->action_button['Добавить +'] = array(
    'name' => '',
    'action' => 'addNew',
    'class' => 'btn btn-default btn-sm navbar-btn',
    'type' => 'button',
    'icon' => 'glyphicon glyphicon-plus',
    'tooltip' => 'data-toggle="tooltip" data-placement="left" title="Добавить"'
);

$PHPShopInterface->field_col = 2;
$PHPShopInterface->setActionPanel($TitlePage, $select_name, array('Добавить +',));

// Подраздел [cat.sub]
if (!empty($subpath[2])) {
    if (empty($subpath[3]))
        $path = '../modules/' . $subpath[2] . '/admpanel/admin_' . $subpath[2] . '.php';
    else
        $path = '../modules/' . $subpath[2] . '/admpanel/admin_' . $subpath[3] . '.php';

    if (file_exists($path))
        include_once($path);
    else {
        header('Location: ./admin.php?path=modules');
        exit;
    }
}
?>