<?php

$TitlePage = __("Мастер обновления");
PHPShopObj::loadClass('update');

$PHPShopUpdate = new PHPShopUpdate();


$License = @parse_ini_file_true(PHPShopFile::searchFile("../../license/", 'getLicense'), 1);

define("UPDATE_PATH", "http://www.phpshop.ru/update/updatecms5.php?from=" . $_SERVER['SERVER_NAME'] . "&version=" . $GLOBALS['SysValue']['upload']['version']);

// Функция обновления
function actionUpdate() {
    global $PHPShopUpdate, $update_result, $TitlePage;

    $TitlePage.=' - ' . __('Установка обновления');

    // Проверка обновлений
    $PHPShopUpdate->checkUpdate();


    // Проверка создания/удаления архивов
    if ($PHPShopUpdate->isReady()) {

        // Бекап БД
        //$PHPShopUpdate->checkBD();

        // Соединение с FTP
        $PHPShopUpdate->ftpConnect();

        // Анализ карты обновления
        $PHPShopUpdate->map();

        // Бекап файлов для восстановления
        $PHPShopUpdate->backupFiles();

        // Распаковка архива
        if ($PHPShopUpdate->installFiles() and !$PHPShopUpdate->base_update_enabled) {
            $TitlePage.=' - ' . __('Выполнено');
        }

        // Обновление config.ini
        $PHPShopUpdate->installConfig();

        // Очистка временных файлов /temp/
        $PHPShopUpdate->cleanTemp();

        // Обновление БД
        $PHPShopUpdate->installBD();
    }


    $update_result = true;
    unset($_SESSION['update_check']);
}

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $TitlePage, $PHPShopModules, $result_message, $PHPShopUpdate, $update_result, $help;

    // Проверка обновлений
    $PHPShopUpdate->checkUpdate();

    foreach (str_split($PHPShopUpdate->version) as $w)
        $version.=$w . '.';
    $version = substr($version, 0, strlen($version) - 1);

    if ($PHPShopUpdate->update_status == 'active' and empty($update_result)) {

        if (is_array($PHPShopUpdate->content)) {
            $result_content = '<ul class="list-group">';

            foreach ($PHPShopUpdate->content as $text)
                $result_content.='<li class="list-group-item">' . $text . '</li>';

            $result_content.='</ul>';

            $PHPShopGUI->action_button['Обновление'] = array(
                'name' => 'Установить обновление',
                'class' => $PHPShopUpdate->btn_class,
                'type' => 'button',
                'icon' => 'glyphicon glyphicon-cloud-download'
            );
        }

        $result_message = $PHPShopGUI->setPanel($PHPShopGUI->i('cloud-download') . __('Доступна новая версия') . ' PHPShop ' . $version, $result_content, 'panel-primary', false);
    } elseif ($PHPShopUpdate->update_status == 'no_update') {

        $result_message = $PHPShopGUI->setPanel($PHPShopGUI->i('ok-sign text-success') . __('Вы используете последнюю версию') . ' PHPShop ' . $version, __('Обновление не требуется'), 'panel-default');
        unset($_SESSION['update_check']);
    } elseif ($PHPShopUpdate->update_status == 'passive') {

        $result_message = $PHPShopGUI->setPanel($PHPShopGUI->i('cloud-download') . __('Доступна новая версия') . ' PHPShop ' . $version, __('Для установки обновления необходимо продлить техническую поддержку'), 'panel-danger');

        $PHPShopGUI->action_button['Обновление'] = array(
            'name' => 'Купить обновление',
            'class' => 'btn btn-primary btn-sm navbar-btn btn-action-panel-blank',
            'action' => 'http://phpshop.ru/order/',
            'type' => 'button',
            'icon' => 'glyphicon glyphicon-ruble'
        );
    }

    $PHPShopGUI->action_button['Журнал'] = array(
        'name' => 'Журнал обновлений',
        'class' => 'btn btn-default btn-sm navbar-btn btn-action-panel-blank',
        'action' => 'http://phpshop.ru/docs/update.html',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-gift'
    );

    // Прогресс бар
    $result_message.=$PHPShopGUI->setProgress(__('Создание резервной копии файлов...'), 'hide');


    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./update/gui/update.gui.js');

    $PHPShopGUI->_CODE = $result_message;
    $PHPShopGUI->_CODE.=$PHPShopUpdate->getLog();

    $PHPShopGUI->setActionPanel($TitlePage, false, array('Журнал', 'Обновление'));


    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, false);


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.update.view");
    $PHPShopGUI->setFooter($ContentFooter);

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