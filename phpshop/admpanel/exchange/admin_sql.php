<?php

$TitlePage = __("SQL запрос к базе");

// Описание полей
$sqlHelper = array(
    'phpshop_system' => 'Настройки сайта',
    "phpshop_gbook" => "Отзывы о сайте из гостевой книги",
    "phpshop_news" => 'Новости',
    "phpshop_jurnal" => 'Журнал авторизации администраторов',
    "phpshop_pages" => 'Страницы сайта (главное меню, контакты и т.д.)',
    "phpshop_menu" => 'Текстовые информационные блоки',
    "phpshop_banners" => 'Рекламные баннеры',
    "phpshop_links" => 'Полезные ссылки',
    "phpshop_search_jurnal" => 'Журнал поиска по сайту',
    "phpshop_users" => 'Администраторы сайта',
    "phpshop_page_categories" => 'Категории страниц',
    "phpshop_photo" => 'Фотогалерея',
    "phpshop_modules" => 'Подключенные дополнительные модули',
    "phpshop_newsletter" => 'Тексты рассылок',
    "phpshop_slider" => 'Слайдер на главной странице'
);

// Функция обновления
function actionSave() {
    global $PHPShopGUI, $result_message, $result_error_tracert, $link_db;

    // Выполнение команд из формы
    if (!empty($_POST['sql_text'])) {
        $sql_query = explode(";\r", trim($_POST['sql_text']));

        foreach ($sql_query as $v)
            $result = mysqli_query($link_db, trim($v));

        // Выполнено успешно
        if ($result)
            $result_message = $PHPShopGUI->setAlert('SQL запрос успешно выполнен');
        else {
            $result_message = $PHPShopGUI->setAlert('SQL ошибка: ' . mysqli_error($link_db), 'danger');
            $result_error_tracert = $_POST['sql_text'];
        }
    }

    // Копируем csv от пользователя
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if ($_FILES['file']['ext'] == "sql") {
            if (move_uploaded_file($_FILES['file']['tmp_name'], "csv/" . $_FILES['file']['name'])) {
                $csv_file = "csv/" . $_FILES['file']['name'];
                $csv_file_name = $_FILES['file']['name'];
            }
            else
                $result_message = $PHPShopGUI->setAlert('Ошибка сохранения файла <strong>' . $csv_file_name . '</strong> в папке phpshop/admpanel/csv', 'danger');
        }
    }

    // Читаем файл из URL
    elseif (!empty($_POST['furl'])) {
        $csv_file = $_POST['furl'];
        $path_parts = pathinfo($csv_file);
        $csv_file_name = $path_parts['basename'];
    }

    // Читаем файл из файлового менеджера
    elseif (!empty($_POST['lfile'])) {
        $csv_file = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $_POST['lfile'];
        $path_parts = pathinfo($csv_file);
        $csv_file_name = $path_parts['basename'];
    }


    // Обработка sql
    if (!empty($csv_file)) {
        $result_error_tracer = $error_line = null;

        // GZIP
        if ($path_parts['extension'] == 'gz') {
            $zd = gzopen($csv_file, "r");
            $sql_file_content = gzread($zd, 1024 * 512);
            gzclose($zd);
        }
        else
            $sql_file_content = file_get_contents($csv_file);

        $sql_query = explode(";\r", $sql_file_content);
        $count = count($sql_query);
        if ($count < 1)
            $sql_query = explode(";", $sql_file_content);


        foreach ($sql_query as $k => $v) {

            if (strlen($v) > 10)
                $result = mysqli_query($link_db, $v);

            if (!$result) {
                $error_line.='[Line ' . $k . '] ';
                $result_error_tracert.= 'Запрос: ' . $v . '
Ошибка: ' . mysqli_error($link_db);
            }
        }

        // Выполнено успешно
        if (empty($result_error_tracert)) {
            if (!empty($_POST['ajax']))
                return array("success" => true);
            else
                $result_message = $PHPShopGUI->setAlert('SQL запрос успешно выполнен');
        }
        else {
            if (!empty($_POST['ajax']))
                return array("success" => false, "error" => mysqli_error($link_db) . ' -> ' . $error_line);
            else
                $result_message = $PHPShopGUI->setAlert('SQL ошибка: ' . mysqli_error($link_db), 'danger');
        }
    }
}

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $TitlePage, $PHPShopModules, $result_message, $result_error_tracert, $PHPShopSystem, $selectModalBody, $sqlHelper;

    $PHPShopGUI->action_button['Выполнить'] = array(
        'name' => 'Выполнить',
        'class' => 'btn btn-primary btn-sm navbar-btn ace-save',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-ok'
    );

    $bases = $DROP = $TRUNCATE = $selectModal = null;
    $baseArray = array();

    foreach ($GLOBALS['SysValue']['base'] as $val) {
        if (is_array($val)) {
            foreach ($val as $mod_base)
                $baseArray[$mod_base] = $mod_base;
        }
        else
            $baseArray[$val] = $val;
    }

    foreach ($baseArray as $val) {
        if (!empty($val)) {
            $bases.="`" . $val . "`, ";
            $DROP.='DROP TABLE ' . $val . ';
';
            if(!empty($sqlHelper[$val]))
            $selectModal.='<tr><td><kbd>' . $val . '</kbd></td><td>' . $sqlHelper[$val] . '</td></tr>';
        }
    }

    unset($baseArray['phpshop_system']);
    unset($baseArray['phpshop_users']);
    unset($baseArray['phpshop_valuta']);
    unset($baseArray['phpshop_citylist_country']);
    unset($baseArray['phpshop_citylist_region']);
    unset($baseArray['phpshop_citylist_city']);
    unset($baseArray['phpshop_modules_key']);


    $TRUNCATE = null;

    foreach ($baseArray as $val) {
        $TRUNCATE.='TRUNCATE `' . $val . '`;
';
    }

    $bases = substr($bases, 0, strlen($bases) - 2) . ';';

    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./exchange/gui/exchange.gui.js', './tpleditor/gui/ace/ace.js');

    $PHPShopGUI->_CODE = $result_message;
    $help = '<p class="text-muted">Для очистки демо-базы и демо-товаров следует выбрать SQL команду <kbd>Очистить базу</kbd></p> <p class="text-muted">Для увелечения производительности сайта вызвать SQL команду <kbd>Оптимизировать базу</kbd></p> <p class="text-muted">Справочник полезных SQL команд для пакетной обработки товаров доступен в <a href="https://help.phpshop.ru/knowledgebase/article/398" target="_blank" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-book"></span> Базе знаний</a></p>';


    $PHPShopGUI->setActionPanel($TitlePage, false, array('Выполнить'));

    if ($_GET['query'] == 'optimize')
        $optimize_sel = 'selected';
    else
        $optimize_sel = null;

    $query_value[] = array('Выбрать SQL команду', 0, '');
    $query_value[] = array('Оптимизировать базу', 'OPTIMIZE TABLE ' . $bases, $optimize_sel);
    $query_value[] = array('Починить базу', 'REPAIR TABLE ' . $bases, '');
    $query_value[] = array('Удалить каталог страниц', 'DELETE FROM ' . $GLOBALS['SysValue']['base']['page_categories'] . ' WHERE ID=', '');
    $query_value[] = array('Удалить все каталоги страниц', 'TRUNCATE ' . $GLOBALS['SysValue']['base']['page_categories'], '');
    $query_value[] = array('Удалить все страницы', 'TRUNCATE ' . $GLOBALS['SysValue']['base']['page'], '');
    $query_value[] = array('Удалить страницы в каталоге', 'DELETE FROM ' . $GLOBALS['SysValue']['base']['page'] . ' WHERE category=', '');
    $query_value[] = array('Удалить страницу', 'DELETE FROM ' . $GLOBALS['SysValue']['base']['page'] . ' WHERE ID=', '');
    $query_value[] = array('Очистить базу', $TRUNCATE, '');
    $query_value[] = array('Уничтожить базу', $DROP, '');

    // Оптимизация по ссылке
    if ($_GET['query'] == 'optimize')
        $result_error_tracert = 'OPTIMIZE TABLE ' . $bases;

    // Тема
    $theme = $PHPShopSystem->getSerilizeParam('admoption.ace_theme');
    if (empty($theme))
        $theme = 'dawn';

    $PHPShopGUI->_CODE.= '<textarea class="hide hidden-edit" id="editor_src" name="sql_text" data-mod="sql" data-theme="' . $theme . '">' . $result_error_tracert . '</textarea><pre id="editor">Загрузка...</pre>';

    $PHPShopGUI->_CODE.= '<div class="text-right data-row"><a href="#" id="vartable" data-toggle="modal" data-target="#selectModal" data-title="Основные таблицы"><span class="glyphicon glyphicon-question-sign"></span>Описание таблиц</a></div>';

    // Модальное окно таблицы описаний перменных
    $selectModalBody = '<table class="table table-striped"><tr><th>Таблица</th><th>Описание</th></tr>' . $selectModal . '</table>';

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройки', $PHPShopGUI->setField('Команда', $PHPShopGUI->setSelect('sql_query', $query_value)) .
            $PHPShopGUI->setField(__("Файл"), $PHPShopGUI->setFile()), 'in', false, true
    );

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, false);


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("hidden", "saveID", "Применить", "right", 80, "", "but", "actionSave.system.edit");

    $PHPShopGUI->setFooter($ContentFooter);

    // Футер
    $sidebarleft[] = array('title' => 'Категории', 'content' => $PHPShopGUI->loadLib('tab_menu_service', false, './exchange/'));
    $sidebarleft[] = array('title' => 'Подсказка', 'content' => $help);
    $PHPShopGUI->setSidebarLeft($sidebarleft, 2);
    $PHPShopGUI->Compile(2);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();
?>