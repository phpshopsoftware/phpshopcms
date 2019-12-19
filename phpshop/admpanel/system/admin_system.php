<?php

$TitlePage = __("Основные Настройки");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);

// Выбор html редактора
function GetEditors($editor) {
    global $PHPShopGUI;

    if ($editor == 'tiny_mce')
        $editor = 'default';

    $dir = "./editors/";
    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                if ($editor == $file)
                    $sel = "selected";
                else
                    $sel = "";

                if ($file != "." and $file != ".." and $file != "index.html")
                    $value[] = array($file, $file, $sel);
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('option[editor]', $value);
}

// Выбор шаблона дизайна
function GetSkinList($skin) {
    global $PHPShopGUI;
    $dir = "../templates/";

    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (@file_exists($dir . '/' . $file . "/main/index.tpl")) {

                    if ($skin == $file)
                        $sel = "selected";
                    else
                        $sel = "";

                    if ($file != "." and $file != ".." and !strpos($file, '.'))
                        $value[] = array($file, $file, $sel);
                }
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('skin_new', $value);
}

// Выбор цвета редактора шабонов
function GetAceSkinList($skin) {
    global $PHPShopGUI;
    $dir = "./tpleditor/gui/ace/";

    if (empty($skin))
        $skin = 'dawn';

    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                if (preg_match("/^theme-([a-zA-Z0-9_]{1,30}).js$/", $file, $match)) {

                    $file = str_replace(array('.js', 'theme-'), '', $file);

                    if ($skin == $file)
                        $sel = "selected";
                    else
                        $sel = "";

                    if ($file == 'dawn')
                        $value[] = array('default', 'dawn', $sel);

                    elseif ($file != "." and $file != ".." and !strpos($file, '.'))
                        $value[] = array($file, $file, $sel);
                }
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('option[ace_theme]', $value, 200);
}

// Выбор шаблона панели управления
function GetAdminSkinList($skin) {
    global $PHPShopGUI;
    $dir = "./css/";

    $color = array(
        'default' => '#178ACC',
        'cyborg' => '#000',
        'flatly' => '#D9230F',
        'spacelab' => '#46709D',
        'slate' => '#4E5D6C',
        'yeti' => '#008CBA',
        'simplex' => '#DF691A',
        'sardbirds' => '#45B3AF',
        'wordless' => '#468966',
        'wildspot' => '#564267',
        'loving' => '#FFCAEA',
        'retro' => '#BBBBBB',
        'cake' => '#E3D2BA'
    );

    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                if (preg_match("/^bootstrap-theme-([a-zA-Z0-9_]{1,30}).css$/", $file, $match)) {
                    $icon = $color[$match[1]];

                    $file = str_replace(array('.css', 'bootstrap-theme-'), '', $file);

                    if ($skin == $file)
                        $sel = "selected";
                    else
                        $sel = "";

                    if ($file != "." and $file != ".." and !strpos($file, '.'))
                        $value[] = array($file, $file, $sel, 'data-content="<span class=\'glyphicon glyphicon-text-background\' style=\'color:' . $icon . '\'></span> ' . $file . '"');
                }
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('option[theme]', $value, 200, null, false, false, false, 1, false, 'theme_new');
}

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();
    $option = unserialize($data['admoption']);

    // Размер названия поля
    $PHPShopGUI->field_col = 3;
    $PHPShopGUI->addJSFiles('./js/jquery.waypoints.min.js', './system/gui/system.gui.js');
    $PHPShopGUI->setActionPanel($TitlePage, false, array('Сохранить'));

    // Содержание закладки 1
    $PHPShopGUI->_CODE.= '<p></p>' . $PHPShopGUI->setField(__("Название сайта"), $PHPShopGUI->setInputText(null, "name_new", $data['name']));
    $PHPShopGUI->_CODE .= $PHPShopGUI->setField(__("Компания"), $PHPShopGUI->setInputText(null, "company_new", $data['company']));
    $PHPShopGUI->_CODE .= $PHPShopGUI->setField(__("Телефоны"), $PHPShopGUI->setInputText(null, "tel_new", $data['tel']));
    //$PHPShopGUI->_CODE .= $PHPShopGUI->setField(__("Почта для сообщений"), $PHPShopGUI->setInputText(null, "admin_mail_new", $data['admin_mail']));
    $PHPShopGUI->_CODE .=$PHPShopGUI->setField("Общая пагинация", $PHPShopGUI->setInputText(false, 'num_row_new', $data['num_row'], 50), 1, 'Количество позиций на одной странице сайта (новости и т.д.)');

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройка дизайна', $PHPShopGUI->setField('Дизайн', GetSkinList($data['skin']) . '<br>' . $PHPShopGUI->setCheckbox('skin_choice_new', 1, 'Смены дизайна пользователями', $data["skin_choice"]), 1, 'Дизайн шаблон сайта (front-end)') .
            $PHPShopGUI->setField("Логотип", $PHPShopGUI->setIcon($data['logo'], "logo_new", false), 1, 'Используется в шапке дизайна и печатных документах'));


    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройка e-mail уведомлений', $PHPShopGUI->setField(__("E-mail оповещение"), $PHPShopGUI->setInputText(null, "admin_mail_new", $data['admin_mail'], 300), 1, 'Для использования сторонних SMTP сервисов адрес должен совпадать с пользователем SMTP') .
            $PHPShopGUI->setField("SMTP", $PHPShopGUI->setCheckbox('option[mail_smtp_enabled]', 1, 'Отправка почты через SMTP протокол', $option['mail_smtp_enabled']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[mail_smtp_debug]', 1, 'Включить отладочные сообщения (Debug)', $option['mail_smtp_debug']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[mail_smtp_auth]', 1, 'Авторизация на сервере SMTP', $option['mail_smtp_auth'])
            ) .
            $PHPShopGUI->setField(__("Почтовый сервер SMTP"), $PHPShopGUI->setInputText(null, "option[mail_smtp_host]", $option['mail_smtp_host'], 300, false, false, false, 'smtp.yandex.ru'), 1, 'Сервер исходяшей почты SMTP') .
            $PHPShopGUI->setField(__("Порт сервера"), $PHPShopGUI->setInputText(null, "option[mail_smtp_port]", $option['mail_smtp_port'], 100, false, false, false, '25'), 1, 'Порт почтового SMTP сервера') .
            $PHPShopGUI->setField(__("Пользователь"), $PHPShopGUI->setInputText(null, "option[mail_smtp_user]", $option['mail_smtp_user'], 300, false, false, false, 'user@yandex.ru')) .
            $PHPShopGUI->setField(__("Пароль"), $PHPShopGUI->setInput('password', "option[mail_smtp_pass]", $option['mail_smtp_pass'], null, 300)) .
            $PHPShopGUI->setField(__("Обратный адрес"), $PHPShopGUI->setInputText(null, "option[mail_smtp_replyto]", $option['mail_smtp_replyto'], 300), 1, 'Ответы на почтовые сообщения будут приходить на этот адрес')
    );


    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройка управления', $PHPShopGUI->setField('Дизайн', GetAdminSkinList($option['theme']), 1, 'Цветовая схема оформления панели управления (back-end)') .
            $PHPShopGUI->setField("HTML-редактор по умолчанию", GetEditors($option['editor']), 1, 'Визуальный редактор контента') .
            $PHPShopGUI->setField("Цвет редактора шаблонов", GetAceSkinList($option['ace_theme']), 1, 'Стилизованная подсветка синтаксиса кода шаблонов') .
            $PHPShopGUI->setField(__("Заголовок"), $PHPShopGUI->setInputText(null, "option[adm_title]", $option['adm_title'], 300), 1, 'Брендовый заголовок в левом верхнем углу панели управления'));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.system.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.system.edit");

    $PHPShopGUI->setFooter($ContentFooter);

    $sidebarleft[] = array('title' => 'Категории', 'content' => $PHPShopGUI->loadLib('tab_menu', false, './system/'));
    $PHPShopGUI->setSidebarLeft($sidebarleft, 2);

    // Футер
    $PHPShopGUI->Compile(2);
    return true;
}

/**
 * Экшен сохранения
 */
function actionSave() {

    // Сохранение данных
    actionUpdate();

    header('Location: ?path=' . $_GET['path']);
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select();
    $option = unserialize($data['admoption']);

    // Корректировка пустых значений
    $PHPShopOrm->updateZeroVars('option.user_skin', 'option.templateshop_enabled', 'skin_choice_new','option.mail_smtp_enabled', 'option.mail_smtp_debug', 'option.mail_smtp_auth');

    if (is_array($_POST['option']))
        foreach ($_POST['option'] as $key => $val)
            $option[$key] = $val;

    // Смена шаблона на front-end
    if ($data['skin'] != $_POST['skin_new'] and PHPShopSecurity::true_skin($_POST['skin_new']))
        $_SESSION['skin'] = $_POST['skin_new'];

    $_POST['admoption_new'] = serialize($option);

    // Логотип
    $_POST['logo_new'] = iconAdd('logo_new');

    //$PHPShopOrm->debug=true;
    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));


    return array("success" => $action);
}

// Добавление изображения 
function iconAdd($name = 'icon_new') {

    // Папка сохранения
    $path = '/UserFiles/Image/';

    // Копируем от пользователя
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if (in_array($_FILES['file']['ext'], array('gif', 'png', 'jpg'))) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'])) {
                $file = $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'];
            }
        }
    }

    // Читаем файл из URL
    elseif (!empty($_POST['furl'])) {
        $file = $_POST[$name];
    }

    // Читаем файл из файлового менеджера
    elseif (!empty($_POST[$name])) {
        $file = $_POST[$name];
    }

    if (empty($file))
        $file = '';

    return $file;
}

// Обработка событий
$PHPShopGUI->getAction();
?>