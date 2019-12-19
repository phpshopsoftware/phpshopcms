<?php

$TitlePage = __('Редактирование записи #' . intval($_GET['id']));

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.admlog.admlog_log"));

// Функция обновления
function actionUpdate() {

    $pathinfo = pathinfo($_POST['file_new']);
    $file = $pathinfo['dirname'];

    // Карта таблиц БД
    $baseMap = array(
        'page' => 'page',
        'system' => 'table_name3',
        'gbook' => 'gbook',
        'news' => 'news',
        'menu' => 'table_name14',
        'news_writer' => 'table_name9',
        'banner' => 'table_name15',
        'links' => 'table_name17',
        'users' => 'users',
        'opros' => 'table_name20',
        'product' => 'products',
        'catalog' => 'categories'
    );

    // Карта папок
    $dirSearch = array_keys($baseMap);


    foreach ($dirSearch as $val)
        if (strpos($file, $val)) {
            $baseName = $baseMap[$val];
        }

    $contentCode = unserialize(base64_decode($_POST['contentCode']));


    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base'][$baseName]);
    $PHPShopOrm->debug = true;
    //$PHPShopOrm->trace($contentCode);

    if (!empty($contentCode['delID'])) {
        $action = $PHPShopOrm->insert($contentCode);
        $nameHandler = 'Откат удаления';
    } else {

        if (!empty($contentCode['newsID']))
            $itemID = $contentCode['newsID'];
        elseif (!empty($contentCode['pageID']))
            $itemID = $contentCode['pageID'];
        elseif (!empty($contentCode['productID']))
            $itemID = $contentCode['productID'];
        elseif (!empty($contentCode['catalogID']))
            $itemID = $contentCode['catalogID'];
        elseif (!empty($contentCode['rowID']))
            $itemID = $contentCode['rowID'];
        else
            $itemID = $contentCode['itemID'];

        $action = $PHPShopOrm->update($contentCode, array('id' => '=' . intval($itemID)));
        $nameHandler = 'Откат изменения';
    }

    // Пишем лог
    include_once('writelog.php');
    setLog(false, $nameHandler);

    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $TitlePage;


    $PHPShopGUI->action_button['Восстановить данные'] = array(
        'name' => 'Восстановить данные',
        'action' => 'report.searchreplace',
        'class' => 'btn btn-default btn-sm navbar-btn btn-action-panel',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-refresh'
    );

    $PHPShopGUI->setActionPanel($TitlePage, false, array('Восстановить данные'));


    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));
    $contentTemp = unserialize($data['content']);


    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Дата:", $PHPShopGUI->setInput("text", "name_new", PHPShopDate::dataV($data['date'], true), "left", 150));
    $Tab1.=$PHPShopGUI->setField("Пользователь:", $PHPShopGUI->setInput("text", "name_new", $data['user']));
    $Tab1.=$PHPShopGUI->setField("Действие:", $PHPShopGUI->setInput("text", "name_new", $data['title']));


    // Основное содержание
    $titleSearch = array('content_new', 'description_new');
    if (is_array($contentTemp))
        foreach ($contentTemp as $key => $val) {
            if (in_array($key, $titleSearch)) {
                $contentMain = $contentTemp[$key];
                break;
            }
        }


    // Редактор 1
    $PHPShopGUI->setEditor('none');
    $oFCKeditor = new Editor('content_temp');
    $oFCKeditor->Height = '280';
    $oFCKeditor->Value = $contentMain;

    $Tab2 = $oFCKeditor->AddGUI();

    // Данные отката
    $Tab1.=$PHPShopGUI->setInput("hidden", "contentCode", base64_encode($data['content']), "left", 1);


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Содержание", $Tab2));


    $pathinfo = pathinfo($data['file']);
    $ContentFooter = $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but");

    if ($pathinfo['basename'] != "adm_admlog_back.php")
        $ContentFooter.=$PHPShopGUI->setInput("submit", "editID", "Откатить", "right", 70, "", "but", "actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>