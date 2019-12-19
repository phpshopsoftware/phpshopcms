<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.formgenerator.formgenerator_forms"));

// Функция записи
function actionInsert() {
    global $PHPShopOrm;
    if (empty($_POST['user_mail_copy_new']))
        $_POST['user_mail_copy_new'] = 0;
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem;


    if (is_file('../modules/formgenerator/templates/formgenerator.tpl'))
        $content = file_get_contents('../modules/formgenerator/templates/formgenerator.tpl');

    $Tab1 = $PHPShopGUI->setField('Название:', $PHPShopGUI->setInputText(false, 'name_new', 'Новая форма'));
    $Tab1.=$PHPShopGUI->setField('Ссылка:', $PHPShopGUI->setInputText('http://' . $_SERVER['SERVER_NAME'] . '/formgenerator/', 'path_new', 'example'));
    $Tab1.=$PHPShopGUI->setField('E-mail:', $PHPShopGUI->setInputText(false, 'mail_new', $PHPShopSystem->getParam("adminmail2")));
    $Tab1.=$PHPShopGUI->setline() . $PHPShopGUI->setField('Статус:', $PHPShopGUI->setCheckbox('enabled_new', '1', 'Вывод на сайте', 1) .
                    $PHPShopGUI->setCheckbox('user_mail_copy_new', '1', 'Выслать копию пользователю на e-mail', 1));
    $Tab1.=$PHPShopGUI->setField('Сообщение после отправки:', $PHPShopGUI->setTextarea('success_message_new', 'Данные приняты, наши менеджеры свяжутся с вами.', false, false, 200));
    $Tab1.=$PHPShopGUI->setField('Сообщение о заполнении обязательных полей:', $PHPShopGUI->setTextarea('error_message_new', 'Ошибка заполнения формы. Заполните все поля, отмеченные звездочками (*).'));
    $Tab1.= $PHPShopGUI->setField('Привязка к страницам:', $PHPShopGUI->setInputText(false, 'dir_new', '') . $PHPShopGUI->setHelp('Пример: /page/about.html,/page/company.html'));


    // Редактор 1
    $PHPShopGUI->setEditor('ace', true);

    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '520';
    $oFCKeditor->Value = $content;
    $Tab2 = $oFCKeditor->AddGUI();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Содержание", $Tab2));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "Сохранить", "right", false, false, false, "actionInsert.modules.create");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>