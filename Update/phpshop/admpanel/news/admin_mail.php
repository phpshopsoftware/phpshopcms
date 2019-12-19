<?php

$TitlePage = __("Подписчики");

function actionStart() {
    global $PHPShopInterface;

    $PHPShopInterface->action_select['Заблокировать выбранные'] = array(
        'name' => 'Заблокировать выбранные IP',
        'action' => 'add-blacklist-select',
        'class' => 'disabled'
    );
    
        $PHPShopInterface->action_button['Добавить E-mail'] = array(
        'name' => '',
        'action' => 'addNew',
        'class' => 'btn btn-default btn-sm navbar-btn',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-plus',
        'tooltip' => 'data-toggle="tooltip" data-placement="left" title="Добавить E-mail"'
    );


    $PHPShopInterface->action_title['add-blacklist'] = 'Заблокировать IP';

    $PHPShopInterface->setActionPanel(__("Подписчики"), array('Удалить выбранные'), array('Добавить E-mail'));
    $PHPShopInterface->setCaption(array(null, "2%"), array("Адрес", "60%"),array("Дата добавления", "20%"), array("", "20%"));

    // Таблица с данными
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['send_mail']);
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {

            $PHPShopInterface->setRow(
                    $row['id'], array('name' => $row['mail'], 'link' => '?path=news.mail&id=' . $row['id'], 'align' => 'left'),array('name' => $row['date']), array('action' => array('edit', 'delete', 'id' => $row['id']), 'align' => 'center'));
        }
    $PHPShopInterface->Compile();
}

?>