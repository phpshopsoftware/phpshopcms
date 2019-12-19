<?php

$TitlePage = __('Редактирование Рассылки #' . $_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['newsletter']);

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopOrm, $PHPShopModules, $result_message;

    // Выбор даты
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));
    $PHPShopGUI->field_col = 2;


    // Нет данных
    if (!is_array($data)) {
        header('Location: ?path=' . $_GET['path']);
    }

    $PHPShopGUI->action_button['Сохранить и отправить'] = array(
        'name' => 'Сохранить и отправить',
        'action' => 'saveID',
        'class' => 'btn  btn-default btn-sm navbar-btn hidden-xs',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-ok'
    );


    // Имя товара
    if (strlen($data['name']) > 50)
        $title_name = substr($data['name'], 0, 70) . '...';
    else
        $title_name = $data['name'];

    $PHPShopGUI->setActionPanel(__("Рассылки: " . $title_name), array('Удалить'), array('Сохранить', 'Сохранить и отправить'));

    // Отчет
    if (!empty($result_message))
        $Tab1 = $PHPShopGUI->setField('Отчет', $result_message);

    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '300';
    $oFCKeditor->Value = $data['content'];

    // Содержание закладки 1
    $Tab1.= $PHPShopGUI->setField("Тема:", $PHPShopGUI->setInput("text.requared", "name_new", $data['name']));

    $Tab1.=$PHPShopGUI->setField("Текст письма:", $oFCKeditor->AddGUI() . $PHPShopGUI->setHelp('Переменные: <code>@url@</code> - адрес сайта, <code>@user@</code> - имя подписчика, <code>@email@</code> - email подписчика, <code>@name@</code> - название магазина, <code>@tel@</code> - телефон компании'));

    // Новости
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['news']);
    $data_page = $PHPShopOrm->select(array('*'), false, array('order' => 'id desc'), array('limit' => 10));

    $value = array();
    $value[] = array(__('Не использовать'), 0, false);
    if (is_array($data_page))
        foreach ($data_page as $val) {
            $value[] = array($val['title'] . ' &rarr;  ' . $val['date'], $val['id'], false);
        }

    $Tab1.=$PHPShopGUI->setField('Содержание из новости', $PHPShopGUI->setSelect('template', $value, '100%', false, false, false, false, false, false));


    $Tab1.=$PHPShopGUI->setField('Лимит строк', $PHPShopGUI->setInputText(null, 'send_limit', '0,1000', 150), 1, 'Запись c 1 по 1000');


    $Tab1.=$PHPShopGUI->setField("Тестовое сообщение", $PHPShopGUI->setCheckbox('test', 1, 'Отправить только тестовое сообщение на ' . $PHPShopSystem->getEmail(), 1));

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.news.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.news.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.news.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);

    return true;
}

/**
 * Экшен сохранения
 */
function actionSave() {

    // Сохранение данных
    actionUpdate();

    //header('Location: ?path=' . $_GET['path']);
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules, $PHPShopSystem, $PHPShopGUI, $result_message;

    $_POST['date_new'] = time();

    PHPShopObj::loadClass("parser");
    PHPShopObj::loadClass("mail");

    PHPShopParser::set('url', $_SERVER['SERVER_NAME']);
    PHPShopParser::set('name', $PHPShopSystem->getValue('name'));
    PHPShopParser::set('tel', $PHPShopSystem->getValue('tel'));
    PHPShopParser::set('title', $_POST['name_new']);
    PHPShopParser::set('logo', $PHPShopSystem->getLogo());
    $from = $PHPShopSystem->getEmail();


    // Рассылка новости
    if (!empty($_POST['template'])) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['news']);
        $data = $PHPShopOrm->select(array('*'), array('id' => "=" . intval($_POST['template'])), false, array('limit' => 1));
        if (is_array($data)) {
            $_POST['name_new'] = $data['title'];
            $_POST['content_new'] = $data['content'];
        }
    }

    $n = $error = 0;

    // Тест
    if (!empty($_POST['test'])) {

        PHPShopParser::set('user', $_SESSION['logPHPSHOP']);
        PHPShopParser::set('email', $from);
        //PHPShopParser::set('content', @preg_replace("/@([a-zA-Z0-9_]+)@/e", '$GLOBALS["SysValue"]["other"]["\1"]', $_POST['content_new']));
        PHPShopParser::set('content', preg_replace_callback("/@([a-zA-Z0-9_]+)@/", 'PHPShopParser::SysValueReturn', $_POST['content_new']));

        $PHPShopMail = new PHPShopMail($from, $from, $_POST['name_new'], '', true, true);
        $content = PHPShopParser::file('tpl/sendmail.mail.tpl', true);

        if (!empty($content)) {
            if ($PHPShopMail->sendMailNow($content))
                $n++;
            else
                $error++;
        }
    } else {

        // Рассылка пользователям
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['send_mail']);
        $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id desc'), array('limit' => $_POST['send_limit']));

        if (is_array($data))
            foreach ($data as $row) {

                PHPShopParser::set('user', __('Пользователь'));
                PHPShopParser::set('email', $row['mail']);
                //PHPShopParser::set('content', @preg_replace("/@([a-zA-Z0-9_]+)@/e", '$GLOBALS["SysValue"]["other"]["\1"]', $_POST['content_new']));
                PHPShopParser::set('content', preg_replace_callback("/@([a-zA-Z0-9_]+)@/", 'PHPShopParser::SysValueReturn', $_POST['content_new']));

                $PHPShopMail = new PHPShopMail($row['mail'], $from, $_POST['name_new'], '', true, true);
                $content = PHPShopParser::file('tpl/sendmail.mail.tpl', true);

                if (!empty($content)) {
                    if ($PHPShopMail->sendMailNow($content))
                        $n++;
                    else
                        $error++;
                }
            }
    }

    $result_message = $PHPShopGUI->setAlert('Успешно разослано по <strong>' . $n . '</strong> адресам с ограничением ' . $_POST['send_limit'] . ' записей. Ошибок <strong>' . $error . '</strong>.');

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['newsletter']);
    $action=$PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));

    return array("success" => $action);
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array("success" => $action);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>