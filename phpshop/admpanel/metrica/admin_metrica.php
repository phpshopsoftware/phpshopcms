<?php

$TitlePage = "Статистика - Сводка";

function actionStart() {
    global $PHPShopGUI, $PHPShopInterface, $TitlePage, $select_name, $PHPShopSystem;

    // Настройки
    $metrica_id = $PHPShopSystem->getSerilizeParam('admoption.metrica_id');
    $metrica_token = $PHPShopSystem->getSerilizeParam('admoption.metrica_token');


    if (function_exists('ini_set'))
        ini_set('default_socket_timeout', 5);

    $ctx = stream_context_create(array('http' =>
        array(
            'timeout' => 5
        )
    ));

    $PHPShopGUI->action_button['Показать в Метрике'] = array(
        'name' => 'Отчет на Яндекс.Метрика',
        'action' => 'https://metrika.yandex.ru/dashboard?id=' . $metrica_id,
        'class' => 'btn  btn-default btn-sm navbar-btn btn-action-panel-blank',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-export'
    );

    //$PHPShopInterface->checkbox_action = false;
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './js/bootstrap-datetimepicker.ru.js', 'js/chart.min.js', 'metrica/gui/metrica.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');


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

    $PHPShopGUI->setActionPanel($TitlePage, $select_name, array('Показать в Метрике'));

    if (PHPShopSecurity::true_param($metrica_id, $metrica_token)) {

        // Посетители
        $array_url_data = array(
            'preset' => 'hourly',
            'metrics' => 'ym:s:visits,ym:s:users,ym:s:pageviews,ym:s:percentNewVisitors,ym:s:bounceRate,ym:s:pageDepth,ym:s:avgVisitDurationSeconds',
            'group' => $_GET['group'],
            'date1' => $date_start,
            'date2' => $date_end,
            'id' => $metrica_id,
            'oauth_token' => $metrica_token,
        );

        $url = 'https://api-metrika.yandex.ru/stat/v1/data?' . http_build_query($array_url_data);
        $json_data = json_decode(file_get_contents($url, false, $ctx), true);

        $canvas_value = $canvas_label = null;
        if (is_array($json_data)) {

            foreach ($json_data[data] as $value) {

                $date = $value[dimensions][0][name];
                $visits = $value[metrics][0];
                $users = $value[metrics][1];

                // График
                $canvas_value.='"' . $visits . '",';
                $canvas_label.='"' . $date . '",';
            }
        }


        $PHPShopGUI->_CODE.=' 
<div class="row intro-row">
         <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-star"></span> ' . __('Визиты') . '</div>
                <div class="panel-body text-right panel-intro">
                 <a data-toggle="tooltip" data-placement="top" href="?path=metrica.traffic&date_start=' . $_GET['date_start'] . '&date_end=' . $_GET['date_end'] . '&group_date=' . $_GET['group_date'] . '" title="' . __('Показать больше') . '">' . $json_data[totals][0] . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-user"></span> ' . __('Посетители') . '</div>
                <div class="panel-body text-right panel-intro">
                 <a data-toggle="tooltip" data-placement="top" href="?path=metrica.traffic&date_start=' . $_GET['date_start'] . '&date_end=' . $_GET['date_end'] . '&group_date=' . $_GET['group_date'] . '" title="' . __('Показать больше') . '">' . $json_data[totals][1] . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-eye-open"></span> ' . __('Просмотры') . '</div>
                <div class="panel-body text-right panel-intro">
                 <a data-toggle="tooltip" data-placement="top" href="?path=metrica.traffic&date_start=' . $_GET['date_start'] . '&date_end=' . $_GET['date_end'] . '&group_date=' . $_GET['group_date'] . '" title="' . __('Показать больше') . '">' . $json_data[totals][2] . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-bell"></span> ' . __('Новые') . '<span class="pull-right text-muted">%</span></div>
                <div class="panel-body text-right panel-intro">
                 <a data-toggle="tooltip" data-placement="top" href="?path=metrica.traffic&date_start=' . $_GET['date_start'] . '&date_end=' . $_GET['date_end'] . '&group_date=' . $_GET['group_date'] . '" title="' . __('Показать больше') . '">' . round($json_data[totals][3], 2) . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 hidden-xs hidden-sm">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-thumbs-down"></span> ' . __('Отказы') . '<span class="pull-right text-muted">%</span></div>
                <div class="panel-body text-right panel-intro">
                 <a data-toggle="tooltip" data-placement="top" href="?path=metrica.traffic&date_start=' . $_GET['date_start'] . '&date_end=' . $_GET['date_end'] . '&group_date=' . $_GET['group_date'] . '" title="' . __('Показать больше') . '">' . round($json_data[totals][4], 2) . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 hidden-xs hidden-sm">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-hourglass"></span> ' . __('Время') . '<span class="pull-right text-muted">мин.</span></div>
                <div class="panel-body text-right panel-intro">
                 <a data-toggle="tooltip" data-placement="top" href="?path=metrica.traffic&date_start=' . $_GET['date_start'] . '&date_end=' . $_GET['date_end'] . '&group_date=' . $_GET['group_date'] . '" title="' . __('Показать больше') . '">' . round($json_data[totals][5], 2) . '</a>
               </div>
          </div>
       </div>
</div>


<div class="row intro-row">
       <div class="hidden-xs hidden-sm col-md-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-stats"></span> ' . __('Посещаемость по часам') . ' 
             <span class="pull-right hidden-xs">
             
<div class="dropdown">
  <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <span class="glyphicon glyphicon-cog"></span>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu dropdown-menu-right canvas-select">
    <li class="disabled"><a href="#" class="canvas-line">' . __('Линейная диаграмма') . '</a></li>
    <li><a href="#" class="canvas-bar">' . __('Гистограмма') . '</a></li>
    <li><a href="#" class="canvas-radar">' . __('Радар диаграмма') . '</a></li>
    <li class="divider"></li>
    <li><a href="?path=metrica.traffic">' . __('Показать больше') . '</a></li>
  </ul>
</div>

                </span>
              </div>
                <div class="panel-body" style="padding:0px 5px 0px 5px">
                 <div class="intro-canvas">
                     <canvas id="canvas" data-title="посетителей"  data-value=\'[' . substr($canvas_value, 0, strlen($canvas_value) - 1) . ']\' data-label=\'[' . substr($canvas_label, 0, strlen($canvas_label) - 1) . ']\'></canvas>
                 </div>
               </div>
          </div>
       </div>';


        // Страницы
        $array_url_data = array(
            'preset' => 'popular',
            'metrics' => 'ym:pv:pageviews, ym:pv:users',
            'group' => $_GET['group'],
            'date1' => $date_start,
            'date2' => $date_end,
            'id' => $metrica_id,
            'oauth_token' => $metrica_token,
        );

        $url = 'https://api-metrika.yandex.ru/stat/v1/data?' . http_build_query($array_url_data);
        $json_data = json_decode(file_get_contents($url, false, $ctx), true);

        $PHPShopInterface = new PHPShopInterface();
        $PHPShopInterface->checkbox_action = false;
        $PHPShopInterface->setCaption(array("Адрес страницы", "40%"), array("Визиты", "10%"));
        $n = 0;
        if (is_array($json_data)) {

            $json_data = $json_data[data];

            foreach ($json_data as $key => $value) {

                $name = PHPShopString::utf8_win1251($json_data[$key][dimensions][4][name]);
                $favicon = $json_data[$key][dimensions][4][favicon];
                $visits = $json_data[$key][metrics][0];
                $users = $json_data[$key][metrics][1];
                $icon = '<img src="//favicon.yandex.net/favicon/' . $favicon . '/" style="padding-right:5px;width:21px" />';

                if (!empty($name) and $n < 7) {
                    $PHPShopInterface->setRow(array('name' => $icon . $name, 'link' => $name, 'target' => '_blank'), array('name' => $visits, 'align' => 'right'));
                    $n++;
                }
            }
        }
        if (count($json_data) == 0)
            $PHPShopInterface->setRow('Нет данных..', array('name' => '?', 'align' => 'right'));


        $PHPShopGUI->_CODE.='<div class="col-md-6 col-xs-12">
     <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-thumbs-up"></span> ' . __('Популярные страницы') . ' <a class="pull-right" href="?path=metrica.popular">' . __('Показать больше') . '</a></div>
                   <table class="table table-hover intro-list">' . $PHPShopInterface->getContent() . '</table>
          </div>
    </div>
  </div>';

        // Трафик
        if ($date_start != $date_end and $_GET['group_date'] != 'today') {

            $PHPShopInterface = new PHPShopInterface();
            $PHPShopInterface->checkbox_action = false;
            $PHPShopInterface->setCaption(array("Дата", "10%"), array("Визит", "10%", array('align' => 'center')), array("Посетители", "10%", array('align' => 'center')), array("Просмотры", "10%", array('align' => 'center')), array("Время ", "10%", array('align' => 'right')));

            $ctx = stream_context_create(array('http' =>
                array(
                    'timeout' => 5
                )
            ));

            $array_url_data = array(
                'preset' => 'traffic',
                'metrics' => 'ym:s:visits,ym:s:users,ym:s:pageviews,ym:s:percentNewVisitors,ym:s:bounceRate,ym:s:pageDepth,ym:s:avgVisitDurationSeconds',
                'group' => 'day',
                'date1' => $date_start,
                'date2' => $date_end,
                'id' => $metrica_id,
                'oauth_token' => $metrica_token,
            );

            $url = 'https://api-metrika.yandex.ru/stat/v1/data?' . http_build_query($array_url_data);
            $json_data = json_decode(file_get_contents($url, false, $ctx), true);

            if (is_array($json_data)) {
                $n = 0;
                $canvas_data = $json_data = $json_data[data];
                $canvas_value = $canvas_label = null;
                foreach ($json_data as $value) {
                    $date = $value[dimensions][0][id];
                    $visits = $value[metrics][0];
                    $users = $value[metrics][1];
                    $pageviews = $value[metrics][2];
                    $avgVisitDurationSeconds = $value[metrics][6] / 60;

                    if (!empty($name) and $n < 6) {
                        $PHPShopInterface->setRow(array('name' => date('d.m.Y', strtotime($date)), 'align' => 'left'), array('name' => $visits, 'align' => 'center'), array('name' => $users, 'align' => 'center'), array('name' => $pageviews, 'align' => 'center'), array('name' => round($avgVisitDurationSeconds, 2), 'align' => 'right'));
                        $n++;
                    }
                }


                // График
                if (is_array($canvas_data)) {
                    krsort($canvas_data);
                    foreach ($canvas_data as $value) {

                        $canvas_value.='"' . $value[metrics][0] . '",';
                        $canvas_label.='"' . date('d.m', strtotime($value[dimensions][0][id])) . '",';
                    }
                }

                $traffic_list = $PHPShopInterface->getContent();


                $PHPShopGUI->_CODE.=' 
    <div class="row intro-row">
    <div class="col-md-6 ">
       
           <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-dashboard"></span> ' . __('Посещаемость') . ' <a class="pull-right" href="?path=metrica.traffic">' . __('Показать больше') . '</a></div>
                   <table class="table table-hover ">' . $traffic_list . '</table>
          </div>

       </div>
       <div class="col-md-6 hidden-xs hidden-sm">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-equalizer"></span> ' . __('Посещаемость') . ' 
             <span class="pull-right hidden-xs">
             
<div class="dropdown">
  <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <span class="glyphicon glyphicon-cog"></span>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu dropdown-menu-right canvas-select">
    <li class="disabled"><a href="#" class="canvas-line" data-canvas="2">' . __('Линейная диаграмма') . '</a></li>
    <li><a href="#" class="canvas-bar" data-canvas="2">' . __('Гистограмма') . '</a></li>
    <li><a href="#" class="canvas-radar" data-canvas="2">' . __('Радар диаграмма') . '</a></li>
    <li class="divider"></li>
    <li><a href="?path=metrica">' . __('Показать больше') . '</a></li>
  </ul>
</div>

                </span>
              </div>
                <div class="panel-body" style="padding:0px 5px 0px 5px">
                 <div class="intro-canvas">
                     <canvas id="canvas2" data-title="' . __('посетителя') . '"  data-value=\'[' . substr($canvas_value, 0, strlen($canvas_value) - 1) . ']\' data-label=\'[' . substr($canvas_label, 0, strlen($canvas_label) - 1) . ']\'></canvas>
                 </div>
               </div>
          </div>
       </div>
     </div>';
            }
        }


        // Рефереры
        $array_url_data = array(
            'preset' => 'sources_summary',
            'metrics' => 'ym:s:visits,ym:s:users,ym:s:bounceRate,ym:s:pageDepth,ym:s:avgVisitDurationSeconds',
            'group' => $_GET['group'],
            'date1' => $date_start,
            'date2' => $date_end,
            'id' => $metrica_id,
            'oauth_token' => $metrica_token,
        );

        $url = 'https://api-metrika.yandex.ru/stat/v1/data?' . http_build_query($array_url_data);
        $json_data = json_decode(file_get_contents($url, false, $ctx), true);

        $PHPShopInterface = new PHPShopInterface();
        $PHPShopInterface->checkbox_action = false;
        $PHPShopInterface->setCaption(array("Реферер", "40%"), array("Визиты", "10%", array('align' => 'right')));

        $n = 0;
        if (is_array($json_data[data])) {
            foreach ($json_data[data] as $value) {

                $name = PHPShopString::utf8_win1251($value[dimensions][1][name]);
                $visits = $value[metrics][0];
                $icon = '<img src="//favicon.yandex.net/favicon/' . $value[dimensions][1][favicon] . '/" style="width:21px;padding-right:5px" />';

                if (strstr($name, '.'))
                    $name = '<a target="_blank" href="http://' . $name . '">' . $name . '</a>';

                if (!empty($name) and $n < 7) {
                    $PHPShopInterface->setRow($icon . $name, array('name' => $visits, 'align' => 'right'));
                    $n++;
                }
            }
        }
        if (count($json_data[data]) == 0)
            $PHPShopInterface->setRow('Нет данных..', array('name' => '?', 'align' => 'right'));

        $PHPShopGUI->_CODE.='
<div class="row intro-row">
   <div class="col-md-6 col-xs-12">
        <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-share-alt"></span> ' . __('Реферер') . ' <a class="pull-right" href="?path=metrica.sources_summary">' . __('Показать больше') . '</a></div>
                   <table class="table table-hover intro-list">' . $PHPShopInterface->getContent() . '</table>
          </div>
   </div>
';


        // Поисковая фраза
        $array_url_data = array(
            'preset' => 'sources_search_phrases',
            'metrics' => 'ym:s:visits,ym:s:users,ym:s:bounceRate,ym:s:pageDepth,ym:s:avgVisitDurationSeconds',
            'group' => $_GET['group'],
            'date1' => $date_start,
            'date2' => $date_end,
            'id' => $metrica_id,
            'oauth_token' => $metrica_token,
        );

        $url = 'https://api-metrika.yandex.ru/stat/v1/data?' . http_build_query($array_url_data);
        $json_data = json_decode(file_get_contents($url, false, $ctx), true);

        $PHPShopInterface = new PHPShopInterface();
        $PHPShopInterface->checkbox_action = false;
        $PHPShopInterface->setCaption(array("Поисковая фраза, Поисковая система", "40%"), array("Визиты", "10%", array('align' => 'right')));

        $n = 0;
        if (is_array($json_data[data])) {
            foreach ($json_data[data] as $value) {

                $name = PHPShopString::utf8_win1251($value[dimensions][0][name]);
                $name_1 = PHPShopString::utf8_win1251($value[dimensions][1][name]);
                $visits = $value[metrics][0];
                $icon = '<img src="//favicon.yandex.net/favicon/' . $value[dimensions][1][favicon] . '/" style="width:21px;padding-right:5px" />';

                if ($name_1 == 'Яндекс')
                    $name = '<a target="_blank" href="https://yandex.ru/search/?text=' . $name . '">' . PHPShopSecurity::TotalClean($name) . '</a>';
                else $name=PHPShopSecurity::TotalClean($name);

                if (!empty($name) and $n < 7) {
                    $PHPShopInterface->setRow($icon . $name, array('name' => $visits, 'align' => 'right'));
                    $n++;
                }
            }
        }
        if (count($json_data[data]) == 0)
            $PHPShopInterface->setRow('Нет данных..', array('name' => '?', 'align' => 'right'));

        $PHPShopGUI->_CODE.='
   <div class="col-md-6 col-xs-12">
        <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> ' . __('Последняя поисковая фраза') . ' <a class="pull-right" href="?path=metrica.search_phrases">' . __('Показать больше') . '</a></div>
                   <table class="table table-hover intro-list">' . $PHPShopInterface->getContent() . '</table>
          </div>
   </div>
</div>
';
    } else {
        $PHPShopGUI->_CODE.= $PHPShopGUI->setAlert(__('Для получения статистики посещения требуется настроить параметры интеграции <a href="?path=system.integration"><span class="glyphicon glyphicon-share-alt"></span> Яндекс.Метрики</a>'), 'warning');
    }

    $searchforma.=$PHPShopGUI->setInputDate("date_start", $date_start, 'margin-bottom:10px', null, 'Дата начала отбора');
    $searchforma.=$PHPShopGUI->setInputDate("date_end", $date_end, false, null, 'Дата конца отбора');
    $searchforma.=$PHPShopGUI->setInputArg(array('type' => 'hidden', 'name' => 'path', 'value' => $_GET['path']));


    $group_date_value[] = array(__('Интервал'), 0, $_GET['group_date']);
    $group_date_value[] = array(__('Сегодня'), 'today', $_GET['group_date']);
    $group_date_value[] = array(__('Вчера'), 'yesterday', $_GET['group_date']);
    $group_date_value[] = array(__('Неделя'), 'week', $_GET['group_date']);
    $group_date_value[] = array(__('Месяц'), 'month', $_GET['group_date']);
    $group_date_value[] = array(__('Квартал'), 'quart', $_GET['group_date']);
    $group_date_value[] = array(__('Год'), 'year', $_GET['group_date']);
    $searchforma.= $PHPShopGUI->setSelect('group_date', $group_date_value, 180);

    $searchforma.=$PHPShopGUI->setButton('Показать', 'search', 'btn-order-search pull-right');

    if ($clean)
        $searchforma.=$PHPShopInterface->setButton('Сброс', 'remove', 'btn-order-cancel pull-left visible-lg');


    $sidebarright[] = array('title' => 'Отчеты', 'content' => $PHPShopGUI->loadLib('tab_menu', false, './metrica/'));
    $sidebarright[] = array('title' => 'Интервал', 'content' => $PHPShopGUI->setForm($searchforma, false, "order_search", false, false, 'form-sidebar'));

    $PHPShopGUI->setSidebarRight($sidebarright, 2);


    $PHPShopGUI->Compile($form = false);
}

?>