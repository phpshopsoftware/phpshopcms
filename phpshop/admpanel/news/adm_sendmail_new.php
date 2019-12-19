<?php

$TitlePage = __('Создание Рассылки');

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopOrm, $PHPShopModules, $result_message, $TitlePage;

    // Выбор даты
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');

    // Выборка
    $data = array();
    $PHPShopGUI->field_col = 2;



    $PHPShopGUI->action_button['Сохранить и отправить'] = array(
        'name' => 'Сохранить и отправить',
        'action' => 'saveID',
        'class' => 'btn  btn-default btn-sm navbar-btn hidden-xs',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-ok'
    );


    $PHPShopGUI->action_select['Разослать'] = array(
        'name' => 'Разослать пользователям',
        'action' => 'send-user'
    );

    $PHPShopGUI->action_select['Предпросмотр'] = array(
        'name' => 'Предпросмотр',
        'url' => '../../news/ID_' . $data['id'] . '.html',
        'action' => 'front',
        'target' => '_blank'
    );

    // Имя товара
    if (strlen($data['name']) > 50)
        $title_name = substr($data['name'], 0, 70) . '...';
    else
        $title_name = $data['name'];

    $PHPShopGUI->setActionPanel($TitlePage, false, array('Сохранить и закрыть'));

    // Отчет
    if (!empty($result_message))
        $Tab1 = $PHPShopGUI->setField('Отчет', $result_message);

    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '300';
    $oFCKeditor->Value = $data['content'];

    // Содержание закладки 1
    $Tab1.=$PHPShopGUI->setField("Тема:", $PHPShopGUI->setInput("text.requared", "name_new", $data['name']));

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


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.news.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);

    return true;
}

// Функция обновления
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    // Рассылка новости
    if (!empty($_POST['template'])) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['news']);
        $data = $PHPShopOrm->select(array('*'), array('id' => "=" . intval($_POST['template'])), false, array('limit' => 1));
        if (is_array($data)) {
            $_POST['name_new'] = $data['title'];
            $_POST['content_new'] = $data['content'];
        }
    }

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['newsletter']);
    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>