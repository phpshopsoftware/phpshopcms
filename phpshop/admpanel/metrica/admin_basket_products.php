<?php

$TitlePage = "Статистика - Заказанные товары";

function actionStart() {
    global $PHPShopInterface, $TitlePage, $select_name, $PHPShopSystem;

    // Настройки
    $metrica_id = $PHPShopSystem->getSerilizeParam('admoption.metrica_id');
    $metrica_token = $PHPShopSystem->getSerilizeParam('admoption.metrica_token');

    $PHPShopInterface->action_button['Показать в Метрике'] = array(
        'name' => 'Отчет на Яндекс.Метрика',
        'action' => 'https://metrika.yandex.ru/stat/basket_products?id=' . $metrica_id,
        'class' => 'btn  btn-default btn-sm navbar-btn btn-action-panel-blank',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-export'
    );

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->addJSFiles('./js/bootstrap-datetimepicker.min.js', './js/bootstrap-datetimepicker.ru.js', 'metrica/gui/metrica.gui.js');
    $PHPShopInterface->addCSSFiles('./css/bootstrap-datetimepicker.min.css');


    if (empty($_GET['date_start']))
        $date_start = date('Y-m-d');
    else {
        $date_start = $_GET['date_start'];
        $clean = true;
    }

    if (empty($_GET['date_end']))
        $date_end = date('Y-m-d');
    else
        $date_end = $_GET['date_end'];


    // Интервал
    if (!empty($_GET['group_date'])) {
        switch ($_GET['group_date']) {
            case "today":
                $date_start = date('Y-m-d');
                $date_end = date('Y-m-d');
                break;
            case "yesterday":
                $date_start = date('Y-m-d', strtotime("-1 day"));
                $date_end = date('Y-m-d');
                break;
            case "week":
                $date_start = date('Y-m-d', strtotime("-7 day"));
                $date_end = date('Y-m-d');
                break;
            case "month":
                $date_start = date('Y-m-d', strtotime("-1 month"));
                $date_end = date('Y-m-d');
                break;
            case "quart":
                $date_start = date('Y-m-d', strtotime("-3 month"));
                $date_end = date('Y-m-d');
                break;
            case "year":
                $date_start = date('Y-m-d', strtotime("-12 month"));
                $date_end = date('Y-m-d');
                break;
        }
    }


    $TitlePage.=' с ' . $date_start . ' по ' . $date_end;

    if (empty($_GET['group'])) {
        $_GET['group'] = 'day';
    }

    $array_url_data = array(
        'preset' => 'basket_products',
        'metrics' => 'ym:s:productPurchasedQuantity, ym:s:productPurchasedPrice, ym:s:productPurchasedUniq',
        'group' => $_GET['group'],
        'date1' => $date_start,
        'date2' => $date_end,
        'id' => $metrica_id,
        'oauth_token' => $metrica_token,
    );

    $url = 'https://api-metrika.yandex.ru/stat/v1/data?' . http_build_query($array_url_data);
    $json_data = json_decode(file_get_contents($url), true);

    $PHPShopInterface->setActionPanel($TitlePage, $select_name, array('Показать в Метрике'));
    $PHPShopInterface->setCaption(array("Название товара", "50%"), array("Куплено", "10%",array('align'=>'center')),array("Стоимость", "10%",array('align'=>'center')), array("Посетители", "10%",array('align'=>'center')));

    if (is_array($json_data)) {
        

        $PHPShopInterface->setRow('Итого и средние', array('name'=>$json_data[totals][0],'align'=>'center'), array('name'=>$json_data[totals][1],'align'=>'center'), array('name'=>$json_data[totals][2],'align'=>'center'));

        $json_data = $json_data[data];
        
        foreach ($json_data as $value) {

            $name = $value[dimensions][0][name];
            $users = $value[metrics][2];
            $cart = $value[metrics][0];
            $order = $value[metrics][1];

            $PHPShopInterface->setRow(array('name' => PHPShopString::utf8_win1251($name)), array('name'=>$cart,'align'=>'center'), array('name'=>$order,'align'=>'center'), array('name'=>$users,'align'=>'center'));
        }
    }

    $searchforma.=$PHPShopInterface->setInputDate("date_start", $date_start, 'margin-bottom:10px', null, 'Дата начала отбора');
    $searchforma.=$PHPShopInterface->setInputDate("date_end", $date_end, false, null, 'Дата конца отбора');
    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'hidden', 'name' => 'path', 'value' => $_GET['path']));

    $group_date_value[] = array(__('Интервал'), 0, $_GET['group_date']);
    $group_date_value[] = array(__('Сегодня'), 'today', $_GET['group_date']);
    $group_date_value[] = array(__('Вчера'), 'yesterday', $_GET['group_date']);
    $group_date_value[] = array(__('Неделя'), 'week', $_GET['group_date']);
    $group_date_value[] = array(__('Месяц'), 'month', $_GET['group_date']);
    $group_date_value[] = array(__('Квартал'), 'quart', $_GET['group_date']);
    $group_date_value[] = array(__('Год'), 'year', $_GET['group_date']);
    $searchforma.= $PHPShopInterface->setSelect('group_date', $group_date_value, 180);

    $searchforma.=$PHPShopInterface->setButton('Показать', 'search', 'btn-order-search pull-right');

    if ($clean)
        $searchforma.=$PHPShopInterface->setButton('Сброс', 'remove', 'btn-order-cancel pull-left visible-lg');


    $sidebarright[] = array('title' => 'Отчеты', 'content' => $PHPShopInterface->loadLib('tab_menu', false, './metrica/'));
    $sidebarright[] = array('title' => 'Интервал', 'content' => $PHPShopInterface->setForm($searchforma, false, "order_search", false, false, 'form-sidebar'));

    $PHPShopInterface->setSidebarRight($sidebarright, 2);

    $PHPShopInterface->Compile($form = false);
}

?>