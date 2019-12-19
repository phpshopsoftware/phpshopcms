<?php

$TitlePage = __('Создание отзыва');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['gbook']);

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopModules;

    // Выборка
    $data['datas'] = PHPShopDate::get();
    $data['tema'] = __('Отзыв от ') . $data['datas'];
    $data['name'] = __('Администратор');

    $PHPShopGUI->setActionPanel(__("Создание Отзывы"), false, array('Сохранить и закрыть'));

    // datetimepicker
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');


    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('answer_new');
    $oFCKeditor->Height = '320';
    $oFCKeditor->Value = $data['answer'];

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Дата:",$PHPShopGUI->setInputDate("date_new", PHPShopDate::get($data['date'])));

    $Tab1.=$PHPShopGUI->setField("Имя:", $PHPShopGUI->setInput("text", "name_new", $data['name']));

    $Tab1.=$PHPShopGUI->setField("E-mail:", $PHPShopGUI->setInput("text", "mail_new", $data['mail']));

    $Tab1.=$PHPShopGUI->setField("Тема:", $PHPShopGUI->setTextarea("title_new", $data['title'])) .
            $PHPShopGUI->setField("Отзыв:", $PHPShopGUI->setTextarea("question_new", $data['question'], "", '100%', '200'));
    $Tab1.=$PHPShopGUI->setField("Статус", $PHPShopGUI->setRadio("enabled_new", 1, "Вкл.", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "Выкл.", $data['enabled']));
    // Содержание закладки 2
    $Tab1.= $PHPShopGUI->setField("Ответ", $oFCKeditor->AddGUI());

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, null);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.gbook.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция обновления
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    $_POST['datas_new'] = time();

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>