<?php

$TitlePage = __("Настройка интеграций с сервисами");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm, $PHPShopBase;

// Выборка
    $data = $PHPShopOrm->select();
    $option = unserialize($data['admoption']);

// Размер названия поля
    $PHPShopGUI->field_col = 3;


    $PHPShopGUI->setActionPanel($TitlePage, false, array('Сохранить'));

    // Демо-режим
    if ($PHPShopBase->getParam('template_theme.demo') == 'true') {
        $option['metrica_token'] = '';
    }

    // Яндекс.Метрика
    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Статистика посещений Яндекс.Метрика', $PHPShopGUI->setField('Токен', $PHPShopGUI->setInputText(false, 'option[metrica_token]', $option['metrica_token'], 370, '<a target="_blank" href="https://oauth.yandex.ru/authorize?response_type=token&client_id=78246cbd13f74fbd9cb2b48d8bff2559">Получить</a>')) .
            $PHPShopGUI->setField('ID сайта', $PHPShopGUI->setInputText(null, 'option[metrica_id]', $option['metrica_id'], 300, false, false, false, 'XXXXXXXX') .
                    $PHPShopGUI->setHelp('Отчеты доступны в разделе <a href="?path=metrica">Статистика посещений</a>')) .
            $PHPShopGUI->setField("Код счетчика", $PHPShopGUI->setCheckbox('option[metrica_enabled]', 1, 'Включить сбор статистики и разместить код счетчика', $option['metrica_enabled'])) .
            $PHPShopGUI->setField("Виджет", $PHPShopGUI->setCheckbox('option[metrica_widget]', 1, 'Включить виджет статистики в панель инструментов', $option['metrica_widget']))
            , 'in', false
    );

    // Google Analitiks
    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Статистика посещений Google',
            $PHPShopGUI->setField('Идентификатор отслеживания', $PHPShopGUI->setInputText('UA-', 'option[google_id]', $option['google_id'], 300, false, false, false, 'XXXXX-Y').
                    $PHPShopGUI->setHelp('Отчеты доступны в разделе <a href="https://analytics.google.com/analytics/web/" target="_blank">Google Аналитика</a>')) .
            $PHPShopGUI->setField("Код счетчика", $PHPShopGUI->setCheckbox('option[google_enabled]', 1, 'Включить сбор статистики и разместить код счетчика', $option['google_enabled'])) 
            , 'in', true
    );

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Безопасность Google reCAPTCHA', $PHPShopGUI->setField("reCAPTCHA", $PHPShopGUI->setCheckbox('option[recaptcha_enabled]', 1, 'Включить режим усиленной проверки от ботов', $option['recaptcha_enabled']), 1, 'Поддерживаются только новые шаблоны') .
            $PHPShopGUI->setField("Публичный ключ", $PHPShopGUI->setInputText(null, "option[recaptcha_pkey]", $option['recaptcha_pkey'], 300)) .
            $PHPShopGUI->setField("Секретный ключ", $PHPShopGUI->setInputText(null, "option[recaptcha_skey]", $option['recaptcha_skey'], 300) . $PHPShopGUI->setHelp('Персональные ключи для домена выдаются через <a href="https://www.google.com/recaptcha" target="_blank">Google.com</a>'))
    );

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Подсказки DaData.ru', $PHPShopGUI->setField("Подсказки", $PHPShopGUI->setCheckbox('option[dadata_enabled]', 1, 'Включить подсказки DaData.ru', $option['dadata_enabled']), 1, 'Поддерживаются только новые шаблоны') .
            $PHPShopGUI->setField("Публичный ключ", $PHPShopGUI->setInputText(null, "option[dadata_token]", $option['dadata_token'], 300) . $PHPShopGUI->setHelp('Информация о сервисе, регистрация, получение ключей <a href="https://dadata.ru" target="_blank">DaData.ru</a>'))
    );

     $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Новостная лента', $PHPShopGUI->setField("RSS", $PHPShopGUI->setCheckbox('option[rss_graber_enabled]', 1, 'Загружать новости из внешних RSS каналов', $option['rss_graber_enabled']). $PHPShopGUI->setHelp('Новостные каналы управляются в  разделе <a href="?path=news.rss">RSS каналы</a>'))
    );

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
    $PHPShopOrm->updateZeroVars('option.recaptcha_enabled', 'option.dadata_enabled', 'option.sms_enabled', 'option.sms_status_order_enabled', 'option.notice_enabled', 'option.metrica_enabled', 'option.metrica_widget', 'option.metrica_ecommerce','option.google_enabled', 'option.google_analitics','option.rss_graber_enabled');

    if (is_array($_POST['option']))
        foreach ($_POST['option'] as $key => $val)
            $option[$key] = $val;


    $_POST['admoption_new'] = serialize($option);

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));


    return array("success" => $action);
}

// Обработка событий
$PHPShopGUI->getAction();
?>