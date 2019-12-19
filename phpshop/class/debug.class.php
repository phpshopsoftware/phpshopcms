<?php

/**
 * Отладочная панель
 * Включение в config.ini параметр my[debug]=true
 * <code>
 * // example:
 * timer('start','Моя отладка');
 * debug($_POST,'Моя отладка');
 * timer('end','Моя отладка');
 * </code>
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 */
class PHPShopDebug {

    var $value;
    var $tollbar_height_closed = 150;
    var $tollbar_height_opened = 500;

    /**
     * @var string фон отладочной панели для вывода информации
     */
    var $backgroundcolor = 'black';

    /**
     * @var string цвет текста в отладчной панели
     */
    var $textcolor = 'green';

    /**
     * @var string цвет панели
     */
    var $tabcolor = '#337AB7';

    /**
     * @var string цвет заголовков панели
     */
    var $texttabcolor = 'white';

    function add($value, $desc = false) {
        $this->value[$desc] = $value;
    }

    function timeon($desc = false) {
        // Включаем таймер
        $this->start_time[$desc] = microtime(true);
    }

    function timeoff($desc = false) {
        // Выключаем таймер
        $time = microtime(true);
        $seconds = ($time - $this->start_time[$desc]);
        $this->seconds[$desc] = substr($seconds, 0, 6);
    }

    function disp($name, $content) {
        global $PHPShopModules;

        if ($_GET['debug'] == 'timer') {
            $name['Total SQL'] = $this->total_sql;
            $name['Total Seconds'] = $this->total_seconds;
            $name['Total Memory'] = $this->total_memory;
        }
        ob_start();
        print_r($name);
        $disp = $content . ': ' . ob_get_clean();

        echo '<pre class="debug-kit-pre">' . strip_tags($disp) . '</pre>';
    }

    function log() {
        $disp = '';
        $base = $GLOBALS['SysValue']['base']['errorlog']['errorlog_log'];

        if (!empty($base)) {
            $PHPShopOrm = new PHPShopOrm($base);
            $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'id DESC'), array('limit' => 100));

            if (is_array($data))
                foreach ($data as $val)
                    $disp.=PHPShopDate::dataV($val['date']) . ' ' . $val['error'] . '</br>';
        }
        else
            $disp = __('Модуль Error Log не установлен');

        echo $disp;
    }

    function compile($total_sql, $total_seconds, $total_memory) {
        global $PHPShopNav, $PHPShopModules;

        $this->total_sql = $total_sql;
        $this->total_seconds = $total_seconds;
        $this->total_memory = $total_memory;

        if (!empty($_GET['debug'])) {
            $height = $this->tollbar_height_closed . "px";
            $height2 = ($this->tollbar_height_closed - 20) . "px";
        } else {
            $height = "25px";
            $height2 = "0px";
        }


        $metod = '?';
        if (is_array($PHPShopNav->objNav['query'])) {
            foreach ($PHPShopNav->objNav['query'] as $k => $v)
                if (is_array($v)) {
                    foreach ($v as $key => $val)
                        $metod.=$k . '[' . $key . ']=' . $val . '&';
                } elseif ($k != 'debug')
                    $metod.=$k . '=' . $v . '&';
        }

        echo '
           <script>
           function debug_toolbar(toolbar,height){
           height = toolbar.style.height;
           if(height == "' . ($this->tollbar_height_closed - 20) . 'px") {
           document.getElementById("debug-kit-toolbar").style.height="500px";
           toolbar.style.height="500px";
           }
             else {
             toolbar.style.height = "' . ($this->tollbar_height_closed - 20) . 'px";
             document.getElementById("debug-kit-toolbar").style.height="' . $this->tollbar_height_closed . 'px";
             }
           }
           </script>
           <style>
           
           .debug-kit-pre{
           background-color: #000;
           color: #FFF;
           border: 0px;
           }

           #debug-kit-toolbar {
           position: fixed;
           top: 0px;
           right:0px;
           width: 100%;
           height: ' . $height . ';
           overflow: visible;
           z-index:10000000;
           font-family: helvetica, arial, sans-serif;
           }
           
           #debug-kit-nav{
           background-color: ' . $this->tabcolor . ';

           color: ' . $this->textcolor . ';
           width: 500px;
           padding: 3px;
           padding-right:5px;
           }

           #debug-kit-nav a{
           color: ' . $this->texttabcolor . ';
           font-size: 12px;
           }

           #debug-kit-nav span{
           color: white;
           font-size: 12px;
           }

           #debug-kit-display {
           background-color: ' . $this->backgroundcolor . ';
           overflow: auto;
           color: ' . $this->textcolor . ';
           border: 0px;
           border-style: inset;
           height: ' . $height2 . ';
           font-size: 11px;
           text-align: left;
           }

           </style>
           <div title="Развернуть" id="debug-kit-toolbar" align="right">
           <div id="debug-kit-nav">
           <a href="javascript:location.reload();" title="Reload"><span class="glyphicon glyphicon-refresh"></span></a>
           <span>' . $total_seconds . '</span>
               <a href="/dev/" title="Debug Kit Option"><img border=0 alt="Dev" src="/phpshop/admpanel/icon/debug.png" width=16 height=16 alt="Отладчик Вкл" align="absmiddle" hspace="5"></a>
           <a href="' . $metod . 'debug=session">Session</a> 
           <a href="' . $metod . 'debug=sysvalue">SysValue</a> 
           <a href="' . $metod . 'debug=request">Request</a> 
           <a href="' . $metod . 'debug=timer">Timer</a>
           <a href="' . $metod . 'debug=variables">Variables</a> 
           <a href="' . $metod . 'debug=values">Values</a> 
           <a href="?" title="Exit"><span class=" glyphicon glyphicon-remove"></span></a>
           <div id="debug-kit-display" onclick="debug_toolbar(this)">';

        if (!empty($_GET['debug']))
            switch ($_GET['debug']) {
                case "session":
                    $this->disp($_SESSION, "_SESSION");
                    $this->disp($GLOBALS['Cache'], "GLOBALS['Cache']");
                    break;
                case "sysvalue":
                    $SysValue = $GLOBALS['SysValue'];
                    $SysValue['connect'] = '******';
                    $SysValue['other'] = '******';
                    $this->disp($SysValue, "GLOBALS['SysValue']");
                    break;
                case "request":
                    $this->disp($GLOBALS['SysValue']['nav'], "GLOBALS['SysValue']['nav']");
                    break;
                case "log":
                    $this->log();
                    break;
                case "variables":
                    $this->disp($GLOBALS['SysValue']['other'], "GLOBALS['SysValue']['other']");
                    break;
                case "values":
                    $this->disp($this->value, "Values");
                    break;
                case "timer":
                    $timer['Repsonal'] = $this->seconds;
                    $timer['Modules'] = $PHPShopModules->handlerDone;
                    $this->disp($timer, "Timer");
                    break;
            }

        echo '</div></div>';
    }

}

/**
 * Отладка персональных данных
 * @global obj $PHPShopDebug
 * @param mixed $value перемнная для отладки
 * @param string $desc оисание переменной
 */
function debug($value, $desc = false) {
    global $PHPShopDebug;
    $PHPShopDebug->add($value, $desc);
}

/**
 * Замер времени выполнения
 * @global obj $PHPShopDebug
 * @param string $option переключатель вкл.выкл [start|end]
 * @param string $desc описание таймера
 */
function timer($option = 'start', $desc = false) {
    global $PHPShopDebug;

    if ($option == 'start')
        $PHPShopDebug->timeon($desc);
    else
        $PHPShopDebug->timeoff($desc);
}

?>