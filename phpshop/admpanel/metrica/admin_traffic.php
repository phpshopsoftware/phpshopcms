<?php

$TitlePage = "Статистика - Посещаемость";

function actionStart() {
    global $PHPShopInterface, $TitlePage, $select_name, $PHPShopSystem;

    // Настройки
    $metrica_id = $PHPShopSystem->getSerilizeParam('admoption.metrica_id');
    $metrica_token = $PHPShopSystem->getSerilizeParam('admoption.metrica_token');

    $PHPShopInterface->action_button['Показать в Метрике'] = array(
        'name' => 'Отчет на Яндекс.Метрика',
        'action' => 'https://metrika.yandex.ru/stat/traffic?id=' . $metrica_id,
        'class' => 'btn  btn-default btn-sm navbar-btn btn-action-panel-blank',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-export'
    );

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->addJSFiles('./js/bootstrap-datetimepicker.min.js', './js/bootstrap-datetimepicker.ru.js', 'js/chart.min.js', 'metrica/gui/metrica.gui.js');
    $PHPShopInterface->addCSSFiles('./css/bootstrap-datetimepicker.min.css');


    if (empty($_GET['date_start'])) {
        $date_start = date('Y-m-d', strtotime("-7 day"));
    } else {
        $date_start = $_GET['date_start'];
        $clean = true;
    }

    if (empty($_GET['date_end']))
        $date_end = date('Y-m-d');
    else
        $date_end = $_GET['date_end'];

    $TitlePage.=' с ' . $date_start . ' по ' . $date_end;

    if (empty($_GET['group'])) {
        $_GET['group'] = 'day';
    }

    if (PHPShopSecurity::true_param($metrica_id, $metrica_token)) {

        $array_url_data = array(
            'preset' => 'traffic',
            'metrics' => 'ym:s:visits,ym:s:users,ym:s:pageviews,ym:s:percentNewVisitors,ym:s:bounceRate,ym:s:pageDepth,ym:s:avgVisitDurationSeconds',
            'group' => $_GET['group'],
            'date1' => $date_start,
            'date2' => $date_end,
            'id' => $metrica_id,
            'oauth_token' => $metrica_token,
        );

        $url = 'https://api-metrika.yandex.ru/stat/v1/data?' . http_build_query($array_url_data);
        $json_data = json_decode(file_get_contents($url), true);

        $PHPShopInterface->setActionPanel($TitlePage, $select_name, array('Показать в Метрике'));
        $PHPShopInterface->setCaption(array("Дата", "10%"), array("Визит", "10%"), array("Посетители", "10%"), array("Просмотры", "10%"), array("Доля новых ", "10%"), array("Отказы", "10%"), array("Глубина", "10%"), array("Время", "10%", array('align' => 'left')));

        if (is_array($json_data)) {

            $PHPShopInterface->setRow('Итого и средние', $json_data[totals][0], $json_data[totals][1], $json_data[totals][2], round($json_data[totals][3], 2) . '%', round($json_data[totals][4], 2) . '%', round($json_data[totals][5], 2), round($json_data[totals][6] / 60, 2));


            $canvas_data=$json_data = $json_data[data];
            $canvas_value = $canvas_label = null;
            foreach ($json_data as $key => $value) {
                $date = $json_data[$key][dimensions][0][id];
                $visits = $json_data[$key][metrics][0];
                $users = $json_data[$key][metrics][1];
                $pageviews = $json_data[$key][metrics][2];
                $percentNewVisitors = $json_data[$key][metrics][3];
                $bounceRate = $json_data[$key][metrics][4];
                $pageDepth = $json_data[$key][metrics][5];
                $avgVisitDurationSeconds = $json_data[$key][metrics][6] / 60;

                $PHPShopInterface->setRow(date('d.m.Y', strtotime($date)), $visits, $users, $pageviews, round($percentNewVisitors, 2) . '%', round($bounceRate, 2) . '%', round($pageDepth, 2), array('name' => round($avgVisitDurationSeconds, 2), 'align' => 'left'));
            }
            
            
            // График
            if (is_array($canvas_data)) {
                krsort($canvas_data);
                foreach ($canvas_data as $value) {

                    $canvas_value.='"' . $value[metrics][0] . '",';
                    $canvas_label.='"' . date('d.m', strtotime($value[dimensions][0][id])) . '",';
                }
            }
        }

        $PHPShopInterface->_CODE.=' 
    <div class="row intro-row">
       <div class="col-md-12">
          <div class="panel panel-default">
             <div class="panel-body">
             <span class="pull-right hidden-xs">
             
<div class="dropdown">
  <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <span class="glyphicon glyphicon-cog"></span> '.__('График').'
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu dropdown-menu-right canvas-select">
    <li class="disabled"><a href="#" class="canvas-line">' . __('Линейная диаграмма') . '</a></li>
    <li><a href="#" class="canvas-bar">' . __('Гистограмма') . '</a></li>
    <li><a href="#" class="canvas-radar">' . __('Радар диаграмма') . '</a></li>
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
       </div>
     </div>';
    } 
    
    $searchforma.=$PHPShopInterface->setInputDate("date_start", $date_start, 'margin-bottom:10px', null, 'Дата начала отбора');
    $searchforma.=$PHPShopInterface->setInputDate("date_end", $date_end, false, null, 'Дата конца отбора');
    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'hidden', 'name' => 'path', 'value' => $_GET['path']));

    $group_value[] = array(__('По дням'), 'day', $_GET['group']);
    $group_value[] = array(__('По неделям'), 'week', $_GET['group']);
    $group_value[] = array(__('По месяцам'), 'month', $_GET['group']);
    $searchforma.= $PHPShopInterface->setSelect('group', $group_value, 180);

    $searchforma.=$PHPShopInterface->setButton('Показать', 'search', 'btn-order-search pull-right');

    if ($clean)
        $searchforma.=$PHPShopInterface->setButton('Сброс', 'remove', 'btn-order-cancel pull-left visible-lg');


    $sidebarright[] = array('title' => 'Отчеты', 'content' => $PHPShopInterface->loadLib('tab_menu', false, './metrica/'));
    $sidebarright[] = array('title' => 'Интервал', 'content' => $PHPShopInterface->setForm($searchforma, false, "order_search", false, false, 'form-sidebar'));
    $PHPShopInterface->setSidebarRight($sidebarright, 2);

    $PHPShopInterface->Compile($form = false);
}

?>