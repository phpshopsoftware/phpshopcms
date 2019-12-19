<?php

// Подключение подменю модулей
function modulesSubMenu() {

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['modules']);
    $data = $PHPShopOrm->select(array('*'), array('path' => '="' . $_GET['id'] . '"'), false, array('limit' => 1));
    if (is_array($data)) {
        $path = $data['path'];
        $menu = "../modules/" . $path . "/install/module.xml";
        $db = xml2array($menu, false, true);

        if (is_array($db['adminmenu']['podmenu'][0])) {
            foreach ($db['adminmenu']['podmenu'] as $val) {

                $action_select[$val['podmenu_name']] = array(
                    'name' => $val['podmenu_name'],
                    'url' => '?path=modules.' . $val['podmenu_action'],
                );
            }
        } else {
            $action_select[$db['adminmenu']['podmenu']['podmenu_name']] = array(
                'name' => $db['adminmenu']['podmenu']['podmenu_name'],
                'url' => '?path=modules.' . $db['adminmenu']['podmenu']['podmenu_action'],
            );
        }

        // Инструкция
        if (!empty($db['faqlink']))
            $wikiPath = $db['faqlink'];
        else
            $wikiPath = 'http://wiki.phpshop.ru/index.php/Modules#' . str_replace(' ', '_', $db[0]['name']);
    }

    $action_select['Инструкция'] = array(
        'name' => 'Инструкция',
        'url' => $wikiPath,
        'target' => '_blank'
    );

    $action_select['|'] = array(
        'name' => '|',
        'action' => 'divider',
    );

    $action_select['Выключить'] = array(
        'name' => 'Выключить',
        'action' => 'off'
    );

    return $action_select;
}

$TitlePage = __('Настройка модуля ' . $_GET['id']);
$PHPShopModules->path = $_GET['id'];
$mod_podmenu = modulesSubMenu();
$PHPShopGUI->action_select = $mod_podmenu;

foreach ($mod_podmenu as $title) {
    $select_name[] = $title['name'];
}



$PHPShopGUI->field_col = 2;
$PHPShopGUI->addJSFiles('./modules/gui/modules.gui.js');
$PHPShopGUI->setActionPanel(__("Настройка модуля") . ' <span id="module-name">' . ucfirst($_GET['id']).'</span>', $select_name, array('Сохранить и закрыть'));
$path = '../modules/' . substr($_GET['id'],0,20) . '/admpanel/adm_module.php';
if(file_exists($path))
include_once($path);
else header('Location: ?path=modules&install=check')
?>