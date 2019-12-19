<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.returncall.returncall_jurnal"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    $_POST['date_new'] = PHPShopDate::GetUnixTime($_POST['date_new']);
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success' => $action);
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->field_col = 1;
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    $Tab1 = $PHPShopGUI->setField("Дата", $PHPShopGUI->setInputDate("date_new", PHPShopDate::dataV($data['date'], false)));
    $Tab1.= $PHPShopGUI->setField('Имя: ', $PHPShopGUI->setInputText(false, 'name_new', $data['name']));
    $Tab1.= $PHPShopGUI->setField('Телефон:', $PHPShopGUI->setInputText(false, 'tel_new', $data['tel'], 300));
    $Tab1.= $PHPShopGUI->setField('Время:', $PHPShopGUI->setInputText('от', 'time_start_new', $data['time_start'], '150', false, 'left') . '<span style="float:left">&nbsp;</span>' . $PHPShopGUI->setInputText('до', 'time_end__new', $data['time_end'], '150', false, 'left'));
    $Tab1.= $PHPShopGUI->setField('IP:', $PHPShopGUI->setInputText(false, 'tel_new', $data['ip'], 300));

    $Tab1.=$PHPShopGUI->setField('Сообщение', $PHPShopGUI->setTextarea('message_new', $data['message']));

    $status_atrray[] = array('Новая', 1, $data['status']);
    $status_atrray[] = array('Перезвонить', 2, $data['status']);
    $status_atrray[] = array('Недоcтупен', 3, $data['status']);
    $status_atrray[] = array('Выполнен', 4, $data['status']);

    $Tab1.=$PHPShopGUI->setField('Статус', $PHPShopGUI->setSelect('status_new', $status_atrray, 200));


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.modules.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * Экшен сохранения
 */
function actionSave() {
    global $PHPShopGUI;


    // Сохранение данных
    actionUpdate();

    header('Location: ?path=' . $_GET['path']);
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array("success" => $action);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>