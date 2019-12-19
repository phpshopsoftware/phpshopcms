<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");


$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");

$PHPShopSystem = new PHPShopSystem();

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.users.users_base"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if (empty($_POST['enabled_new'])) $_POST['enabled_new'] = 0;
    
    // Добавление в рассылку
    if(!empty($_POST['spam'])){
        $PHPShopOrmSpam = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name9']);
        $PHPShopOrmSpam->insert(array('date_new' => date("d-m-y"), 'mail_new' => $_POST['mail_new']));
    }

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['newsID']));
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopOrm;

    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Редактирование пользователя";
    $PHPShopGUI->size = "500,450";

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . $_GET['id']));
    @extract($data);

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Редактирование пользователя", "Укажите данные для записи в базу.", $PHPShopGUI->dir . "img/i_account_contacts_med[1].gif");

    $Tab1 = $PHPShopGUI->setInputText('Логин: ', 'login_new', $login);
    $Tab1.=$PHPShopGUI->setInputText('E-mail: ', 'mail_new', $mail);

    // Дополнительные поля
    $content = unserialize($content);
    $dop = null;

    if (is_array($content))
        foreach ($content as $k => $v) {
            $name = str_replace('dop_', '', $k);
            $dop.=$name . ': ' . $v . '
';
        }
    $dop = substr($dop, 0, strlen($dop) - 1);

    $Tab1.=$PHPShopGUI->setTextarea('dop', $dop);
    $Tab1.=$PHPShopGUI->setCheckbox('enabled_new', 1, 'Активирован', $enabled);
    $Tab1.=$PHPShopGUI->setCheckbox('spam', 1, 'Добавить в рассылку', 0);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 350));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "newsID", $id, "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "return onDelete('" . __('Вы действительно хотите удалить?') . "')", "but", "actionDelete") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['newsID']));
    return $action;
}

if ($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');

    // Обработка событий
    $PHPShopGUI->getAction();
}
else
    $UserChek->BadUserFormaWindow();
?>