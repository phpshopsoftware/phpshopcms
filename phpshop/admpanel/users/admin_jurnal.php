<?php

$TitlePage = __("Журнал авторизации");

function actionStart() {
    global $PHPShopInterface, $TitlePage;
    

    $PHPShopInterface->action_select['Заблокировать выбранные'] = array(
        'name' => 'Заблокировать выбранные IP',
        'action' => 'add-blacklist-select',
        'class' => 'disabled'
    );


    $PHPShopInterface->action_button['Черный список'] = array(
        'name' => 'Черный список',
        'action' => 'users.stoplist',
        'class' => 'btn btn-default btn-sm navbar-btn btn-action-panel',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-fire'
    );

    $PHPShopInterface->action_title['add-blacklist'] = 'Заблокировать IP';
    $PHPShopInterface->action_title['whois'] = 'Кто это?';

    $PHPShopInterface->addJSFiles('./js/bootstrap-datetimepicker.min.js', './js/bootstrap-datetimepicker.ru.js', './users/gui/users.gui.js');
    $PHPShopInterface->addCSSFiles('./css/bootstrap-datetimepicker.min.css');


    // Поиск
    $where = null;
    $limit = 300;
    if (is_array($_GET['where'])) {
        foreach ($_GET['where'] as $k => $v) {
            if ($v != '' and $v != 'none')
                $where.= ' ' . PHPShopSecurity::TotalClean($k) . ' = "' . PHPShopSecurity::TotalClean($v) . '" or';
        }

        if ($where)
            $where = 'where' . substr($where, 0, strlen($where) - 2);

        // Дата
        if (!empty($_GET['date_start']) and !empty($_GET['date_end'])) {
            if ($where)
                $where.=' and ';
            else
                $where = ' where ';
            $where.=' datas between ' . (PHPShopDate::GetUnixTime($_GET['date_start']) - 1) . ' and ' . (PHPShopDate::GetUnixTime($_GET['date_end']) + 259200 / 2) . '  ';
            $TitlePage.=' с ' . $_GET['date_start'] . ' по ' . $_GET['date_end'];
        }

        $limit = 1000;
    }



    $PHPShopInterface->setActionPanel($TitlePage, array('Заблокировать выбранные'), array('Черный список'));
    $PHPShopInterface->setCaption(array(null, "2%"), array("Логин", "30%"), array("IP", "20%"), array("Авторизация", "20%"), array("", "10%"), array("Статус &nbsp;&nbsp;&nbsp;", "10%", array('align' => 'right')));


    // Таблица с данными
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['jurnal']);
    $PHPShopOrm->Option['where'] = ' or ';
    $PHPShopOrm->debug = false;
    $PHPShopOrm->mysql_error = false;
    $PHPShopOrm->sql = 'SELECT * FROM ' . $GLOBALS['SysValue']['base']['jurnal'] . ' ' . $where . ' order by id desc limit ' . $limit;
    $data = $PHPShopOrm->select();
    if (is_array($data))
        foreach ($data as $row) {

            if (empty($row['flag'])) {
                $status = '<span class="glyphicon glyphicon-ok"></span>';
                $link = '?path=users&id=' . $row['id'];
            } else {
                $status = '<span class="glyphicon glyphicon-remove" style="color:red"></span>';
                $link = '?path=users.stoplist&action=new&ip=' . $row['ip'];
            }

            $PHPShopInterface->setRow(
                    $row['id'], array('name' => $row['user'], 'link' => $link, 'align' => 'left'), $row['ip'], array('name' => PHPShopDate::get($row['datas'], true)), array('action' => array('add-blacklist', 'whois', 'id' => $row['id']), 'align' => 'center'), array('name' => $status, 'align' => 'right'));
        }

    if (isset($_GET['date_start']))
        $date_start = $_GET['date_start'];
    else
        $date_start = PHPShopDate::get(time() - 2592000);

    if (isset($_GET['date_end']))
        $date_end = $_GET['date_end'];
    else
        $date_end = PHPShopDate::get(time() - 1);

    // Статус заказа
    $PHPShopInterface->field_col = 1;
    $searchforma.=$PHPShopInterface->setInputDate("date_start", $date_start, 'margin-bottom:10px', null, 'Дата начала отбора');
    $searchforma.=$PHPShopInterface->setInputDate("date_end", $date_end, false, null, 'Дата конца отбора');
    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'where[ip]', 'placeholder' => 'IP', 'value' => $_GET['where']['ip']));
    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'where[user]', 'placeholder' => 'Пользователь', 'value' => $_GET['where']['user']));

    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'hidden', 'name' => 'path', 'value' => $_GET['path']));
    $searchforma.=$PHPShopInterface->setButton(__('Найти'), 'search', 'btn-order-search pull-right');

    if ($where)
        $searchforma.=$PHPShopInterface->setButton(__('Сброс'), 'remove', 'btn-order-cancel pull-left');

    // Правый сайдбар
    $sidebarright[] = array('title' => 'Расширенный поиск', 'content' => $PHPShopInterface->setForm($searchforma, false, "order_search", false, false, 'form-sidebar'));
    $PHPShopInterface->setSidebarRight($sidebarright, 2);
    $PHPShopInterface->Compile(2);
}

?>