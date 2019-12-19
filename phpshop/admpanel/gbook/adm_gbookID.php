<?php

$TitlePage = __('Редактирование Отзыва #' . $_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['gbook']);

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

        // datetimepicker
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');
    
        $PHPShopGUI->action_select['Предпросмотр'] = array(
        'name' => 'Предпросмотр',
        'url' => '../../gbook/ID_' . $data['id'] . '.html',
        'action' => 'front',
        'target' => '_blank'
    );

    $PHPShopGUI->setActionPanel(__("Редактирование Отзыва от " . $data['name']), array('Предпросмотр','|','Удалить'), array('Сохранить', 'Сохранить и закрыть'));

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
    $Tab1.= $PHPShopGUI->setField("Ответ",$oFCKeditor->AddGUI());

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.gbook.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.gbook.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.gbook.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция отправки почты
function sendMail($name, $mail) {
    global $PHPShopSystem, $PHPShopBase;

    // Подключаем библиотеку отправки почты
    PHPShopObj::loadClass("mail");

    $zag = "Ваш отзыв добавлен на сайт " . $PHPShopSystem->getValue('name');
    $message = "Уважаемый " . $name . ",

Ваш отзыв добавлен на сайт по адресу: http://" . $PHPShopBase->getSysValue('dir.dir') . $_SERVER['SERVER_NAME'] . "/gbook/

Спасибо за проявленный интерес.";
    new PHPShopMail($PHPShopSystem->getEmail(), $mail, $zag, $message);
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

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $_POST['date_new'] = PHPShopDate::GetUnixTime($_POST['date_new']);
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    else if (!empty($_POST['mail_new']))
        sendMail($_POST['name_new'], $_POST['mail_new']);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array("success" =>  $action);
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array("success" =>  $action);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>