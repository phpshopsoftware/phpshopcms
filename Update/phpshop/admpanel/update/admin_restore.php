<?php

$TitlePage = __("Мастер восстановления");
PHPShopObj::loadClass('update');

$PHPShopRestore = new PHPShopRestore();

// Функция обновления
function actionRestore() {
    global $PHPShopRestore, $TitlePage;

    $TitlePage.=' - ' . __('Восстановление файлов');

    // Проверка обновлений
    $PHPShopRestore->checkRestore($_REQUEST['version']);

    // Проверка создания/удаления архивов
    if ($PHPShopRestore->isReady()) {

        // Бекап файлов для восстановления
        $PHPShopRestore->restoreFiles();

        // Обновление config.ini
        $PHPShopRestore->restoreConfig();

        // Обновление БД
        $PHPShopRestore->restoreBD();
    }

    unset($_SESSION['update_check']);
    return true;
}

function getFileInfo($file) {
    global $PHPShopInterface;
    static $i;

    $i++;
    $stat = stat("../../backup/backups/" . $file . '/files.zip');
    $stat_bd = stat("../../backup/backups/" . $file . '/base.sql.gz');

    foreach (str_split($file) as $w)
        $version.=$w . '.';
    $version = __('Версия') . ' ' . substr($version, 0, strlen($version) - 1);


    if ($GLOBALS['SysValue']['upload']['version'] > $file)
        $menu = array('restore', 'log', 'id' => $file);
    else {
        $menu = array('log', 'id' => $file);
        $version = '<span class="text-danger">' . $version . '</span>';
    }


    $PHPShopInterface->setRow(array('name' => $version, 'align' => 'left'), PHPShopDate::get($stat['mtime'], true), array('name' => number_format($stat['size'], 0, ',', ' ') . ' ' . __('байт')), array('action' => $menu, 'align' => 'right'), array('name' => number_format($stat_bd['size'], 0, ',', ' ') . ' ' . __('байт'), 'align' => 'right'));
}

// Стартовый вид
function actionStart() {
    global $PHPShopInterface, $TitlePage, $PHPShopModules, $PHPShopGUI, $PHPShopRestore, $help;

    $PHPShopGUI->addJSFiles('./update/gui/update.gui.js');
   
    $PHPShopGUI->action_button['Журнал'] = array(
        'name' => 'Журнал обновлений',
        'class' => 'btn btn-default btn-sm navbar-btn btn-action-panel-blank',
        'action' => 'http://phpshop.ru/docs/update.html',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-gift'
    );
    
     $PHPShopGUI->setActionPanel($TitlePage, false, array('Журнал'));

    if (!empty($_GET['version']))
        $restore_result = actionRestore();

    if (empty($restore_result)) {
        $PHPShopInterface->action_title['load'] = 'Скачать';
        $PHPShopInterface->action_title['restore'] = 'Восстановить';
        $PHPShopInterface->action_title['restorebd'] = 'Восстановить базу данных';
        $PHPShopInterface->action_title['deletefile'] = 'Удалить файл';
        $PHPShopInterface->action_title['log'] = 'Журнал';
        $PHPShopInterface->checkbox_action = false;


        $PHPShopInterface->setCaption(array("Имя файла", "35%"), array("Дата", "15%"), array("Размер файлов", "15%"), array("", "7%", array('align' => 'right')), array("Размер БД", "15%", array('align' => 'right')));
        PHPShopFile::searchFile("../../backup/backups/", 'getFileInfo');


        $PHPShopGUI->_CODE.='<table class="table table-hover" id="data">' . $PHPShopInterface->getContent() . '</table>';
    } else {
        $PHPShopGUI->_CODE.=$PHPShopRestore->getLog();
    }

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "version", "Применить", "right", 80, "", "but", "actionRestore");
    $PHPShopGUI->setFooter($ContentFooter);

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, false);

    // Футер
    $sidebarleft[] = array('title' => 'Мастер', 'content' => $PHPShopGUI->loadLib('tab_menu', false, './update/'));
    $sidebarleft[] = array('title' => 'Подсказка', 'content' => $help);
    $PHPShopGUI->setSidebarLeft($sidebarleft, 2);
    $PHPShopGUI->Compile(2);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();
?>