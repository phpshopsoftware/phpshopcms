<?php

$TitlePage = __('Создание Администратора');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name19']);

function hidePassword($pas) {
    $num = strlen($pas);
    $i = 0;
    $str = null;
    while ($i < $num) {
        $str.="X";
        $i++;
    }
    return $str;
}

function rules_zero($a) {
    if ($a != 1)
        return 0;
    else
        return 1;
}

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules;

    // Начальные данные
    $data['enabled'] = 1;


    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./users/gui/users.gui.js','./js/validator.js');
    $PHPShopGUI->setActionPanel(__("Администраторы"), false, array('Сохранить и закрыть', 'Создать и редактировать'));

    $pasgen = substr(md5(date("U")), 0, 8);

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setCollapse(__('Информация'), 
            $PHPShopGUI->setField("Логин", $PHPShopGUI->setInput('text.required.4', "login_new", $data['login'])) .
            $PHPShopGUI->setField("E-mail", $PHPShopGUI->setInput('email.required.6', "mail_new", $data['mail'])) .
            $PHPShopGUI->setField("Пароль", $PHPShopGUI->setInput("password.required.6", "password_new", hidePassword($data['password']))) .
            $PHPShopGUI->setField("Подтверждение пароля", $PHPShopGUI->setInput("password.required.6", "password2_new", hidePassword($data['password'])) . '<br>' . $PHPShopGUI->setInput("button", false, "Сгенерировать пароль", false, false, "$('input[name=password_new],input[name=password2_new]').val('P" . $pasgen . "');alert('Сгенерирован пароль: " . $pasgen . "');", "btn-sm") . '&nbsp;&nbsp;&nbsp;' . $PHPShopGUI->setCheckbox('sendPasswordEmail', 1, 'Оповестить по E-mail', 1)) .
            $PHPShopGUI->setField("Статус", $PHPShopGUI->setRadio("enabled_new", 1, "Вкл.", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "Выкл.", $data['enabled']) . '&nbsp;&nbsp;')
    );

    // Права
    $Tab2 = $PHPShopGUI->loadLib('tab_rules', $data['status'], false, 'autofill');

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Права", $Tab2));


    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.users.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция записи
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules,$PHPShopSystem;

    // Права
    $statusUser = array(
        "gbook" => rules_zero($_POST[gbook_rul_1]) . "-" . rules_zero($_POST[gbook_rul_2]) . "-" . rules_zero($_POST[gbook_rul_3]),
        "news" => rules_zero($_POST[news_rul_1]) . "-" . rules_zero($_POST[news_rul_2]) . "-" . rules_zero($_POST[news_rul_3]),
        "order" => rules_zero($_POST[order_rul_1]) . "-" . rules_zero($_POST[order_rul_2]) . "-" . rules_zero($_POST[order_rul_3]) . "-" . rules_zero($_POST[order_rul_4]),
        "users" => rules_zero($_POST[users_rul_1]) . "-" . rules_zero($_POST[users_rul_2]) . "-" . rules_zero($_POST[users_rul_3]) . "-" . rules_zero($_POST[users_rul_4]),
        "shopusers" => rules_zero($_POST[shopusers_rul_1]) . "-" . rules_zero($_POST[shopusers_rul_2]) . "-" . rules_zero($_POST[shopusers_rul_3]),
        "catalog" => rules_zero($_POST[catalog_rul_1]) . "-" . rules_zero($_POST[catalog_rul_2]) . "-" . rules_zero($_POST[catalog_rul_3]) . "-" . rules_zero($_POST[catalog_rul_4]) . "-" . rules_zero($_POST[catalog_rul_5]) . "-" . rules_zero($_POST[catalog_rul_6]),
        "report" => rules_zero($_POST[report_rul_1]) . "-" . rules_zero($_POST[report_rul_2]) . "-" . rules_zero($_POST[report_rul_3]),
        "page" => rules_zero($_POST[page_rul_1]) . "-" . rules_zero($_POST[page_rul_2]) . "-" . rules_zero($_POST[page_rul_3]),
        "menu" => rules_zero($_POST[menu_rul_1]) . "-" . rules_zero($_POST[menu_rul_2]) . "-" . rules_zero($_POST[menu_rul_3]),
        "banner" => rules_zero($_POST[banner_rul_1]) . "-" . rules_zero($_POST[banner_rul_2]) . "-" . rules_zero($_POST[banner_rul_3]),
        "slider" => rules_zero($_POST[slider_rul_1]) . "-" . rules_zero($_POST[slider_rul_2]) . "-" . rules_zero($_POST[slider_rul_3]),
        "links" => rules_zero($_POST[links_rul_1]) . "-" . rules_zero($_POST[links_rul_2]) . "-" . rules_zero($_POST[links_rul_3]),
        "csv" => rules_zero($_POST[csv_rul_1]) . "-" . rules_zero($_POST[csv_rul_2]) . "-" . rules_zero($_POST[csv_rul_3]),
        "opros" => rules_zero($_POST[opros_rul_1]) . "-" . rules_zero($_POST[opros_rul_2]) . "-" . rules_zero($_POST[opros_rul_3]),
        "rating" => rules_zero($_POST[rating_rul_1]) . "-" . rules_zero($_POST[rating_rul_2]) . "-" . rules_zero($_POST[rating_rul_3]),
        "exchange" => rules_zero($_POST[exchange_rul_1]) . "-" . rules_zero($_POST[exchange_rul_2]) . "-" . rules_zero($_POST[exchange_rul_3]),
        "system" => rules_zero($_POST[system_rul_1]) . "-" . rules_zero($_POST[system_rul_2]),
        "discount" => rules_zero($_POST[discount_rul_1]) . "-" . rules_zero($_POST[discount_rul_2]) . "-" . rules_zero($_POST[discount_rul_3]),
        "currency" => rules_zero($_POST[currency_rul_1]) . "-" . rules_zero($_POST[currency_rul_2]) . "-" . rules_zero($_POST[currency_rul_3]),
        "delivery" => rules_zero($_POST[delivery_rul_1]) . "-" . rules_zero($_POST[delivery_rul_2]) . "-" . rules_zero($_POST[delivery_rul_3]),
        "servers" => rules_zero($_POST[servers_rul_1]) . "-" . rules_zero($_POST[servers_rul_2]) . "-" . rules_zero($_POST[servers_rul_3]),
        "rsschanels" => rules_zero($_POST[rss_rul_1]) . "-" . rules_zero($_POST[rss_rul_2]) . "-" . rules_zero($_POST[rss_rul_3]),
        "update" => rules_zero($_POST[update_rul_1]),
        "modules" => rules_zero($_POST[modules_rul_1]) . "-" . rules_zero($_POST[modules_rul_2]). "-" . rules_zero($_POST[modules_rul_3]) . "-" . rules_zero($_POST[modules_rul_4]). "-" . rules_zero($_POST[modules_rul_5])
    );


    $_POST['status_new'] = serialize($statusUser);

    $hasher = new PasswordHash(8, false);
    $_POST['password_new'] = $hasher->HashPassword($_POST['password_new']);

    // Оповещение пользователя
    if (!empty($_POST['sendPasswordEmail'])) {

        PHPShopObj::loadClass("parser");
        PHPShopObj::loadClass("mail");

        PHPShopParser::set('user_name', 'Администратор');
        PHPShopParser::set('login', $_POST['login_new']);
        PHPShopParser::set('password', $_POST['password2_new']);

        $zag_adm = "Пароль администратора " . $_SERVER['SERVER_NAME'];
        $PHPShopMail = new PHPShopMail($_POST['mail_new'], $PHPShopSystem->getEmail(), $zag_adm, '', true, true);
        $content_adm = PHPShopParser::file('tpl/changepass.mail.tpl', true);

        if (!empty($content_adm)) {
            $PHPShopMail->sendMailNow($content_adm);
        }
    }

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);

    if ($_POST['saveID'] == 'Создать и редактировать')
        header('Location: ?path=' . $_GET['path'] . '&id=' . $action);
    else
        header('Location: ?path=' . $_GET['path']);

    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>