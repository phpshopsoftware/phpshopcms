<?php

$TitlePage = __('Редактирование записи #' . intval($_GET['id']));

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.formgenerator.formgenerator_forms"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if (empty($_POST['user_mail_copy_new']))
        $_POST['user_mail_copy_new'] = 0;
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success' => $action);
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

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopSystem;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    $Tab1 = $PHPShopGUI->setField('Название:', $PHPShopGUI->setInputText(false, 'name_new', $data['name']));
    $Tab1.=$PHPShopGUI->setField('Ссылка:', $PHPShopGUI->setInputText('http://' . $_SERVER['SERVER_NAME'] . '/formgenerator/', 'path_new', $data['path']));
    $Tab1.=$PHPShopGUI->setField('E-mail:', $PHPShopGUI->setInputText(false, 'mail_new', $data['mail']));
    $Tab1.=$PHPShopGUI->setline() . $PHPShopGUI->setField('Статус:', $PHPShopGUI->setCheckbox('enabled_new', '1', 'Вывод на сайте', $data['enabled']) .
                    $PHPShopGUI->setCheckbox('user_mail_copy_new', '1', 'Выслать копию пользователю на e-mail', 1));
    $Tab1.=$PHPShopGUI->setField('Сообщение после отправки:', $PHPShopGUI->setTextarea('success_message_new', $data['success_message'], false, false, 200));
    $Tab1.=$PHPShopGUI->setField('Сообщение о заполнении обязательных полей:', $PHPShopGUI->setTextarea('error_message_new', $data['error_message']));
    $Tab1.= $PHPShopGUI->setField('Привязка к страницам:', $PHPShopGUI->setInputText(false, 'dir_new', $data['dir']) . $PHPShopGUI->setHelp('Пример: /page/about.html,/page/company.html'));
    $Tab3 = $PHPShopGUI->setTextarea('code', '@php
$PHPShopFormgeneratorElement = new PHPShopFormgeneratorElement();
echo $PHPShopFormgeneratorElement->forma("' . $data['path'] . '");
php@', 'none', '98%', 100) . $PHPShopGUI->setHelp('Код для ручной вставки. Для вставки кода в текстовый блок предварительно отключите визуальный редактор.');



    // Редактор 1
    $PHPShopGUI->setEditor('ace', true);

    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '520';
    $oFCKeditor->Value = $data['content'];
    $Tab2 = $oFCKeditor->AddGUI();



    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Содержание", $Tab2), array("Код", $Tab3));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.modules.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array("success" =>  $action);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>