<?php

// Подключение подменю модулей
function modulesSubMenu($subpath) {
    global $TitlePage;

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['modules']);
    $data = $PHPShopOrm->select(array('*'), array('path' => '="' . $subpath[2] . '"'), false, array('limit' => 1));
    if (is_array($data)) {
        $path = $data['path'];
        $menu = "../modules/" . $path . "/install/module.xml";
        $db = xml2array($menu, false, true);

        if (is_array($db[0])){
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
        } else  $TitlePage = $db['adminmenu']['podmenu']['podmenu_name'];
        
        $TitlePage.= '  '.$db['name'];
    }

    $action_select['Удалить'] = array(
        'name' => 'Удалить',
        'action' => 'deleteone',
        'url' => '#'
    );

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
$PHPShopGUI->action_select = $mod_podmenu;

foreach ($mod_podmenu as $title) {
    $select_name[] = $title['name'];
}

$PHPShopGUI->field_col = 2;
$PHPShopGUI->setActionPanel($TitlePage, $select_name, array('Сохранить и закрыть'));

// Подраздел [cat.sub]
if (!empty($subpath[2])) {
    if (empty($subpath[3]))
        $path = '../modules/' . $subpath[2] . '/admpanel/adm_' . $subpath[2] . 'ID.php';
    else
        $path = '../modules/' . $subpath[2] . '/admpanel/adm_' . $subpath[3] . 'ID.php';
    include_once($path);
}
?>