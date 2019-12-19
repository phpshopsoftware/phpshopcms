<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

class PHPShopDev extends PHPShopCore {

    // Конструктор
    function __construct() {
        $this->objBase=$GLOBALS['SysValue']['base']['debug']['debug_system'];
        $this->debug=false;
        $this->cache=true;
        $this->action=array("nav"=>"index",'get'=>array('phpinfo','debug_start','debug_stop','error_log','modules_list',
                        'errorlog_clean','key'));
        parent::__construct();
        $this->system();
    }

    // Настройка
    function system() {
        $this->system=$this->PHPShopOrm->select();
        $this->set('pageNav', ' ');
        $this->set('breadcrumbs_home',' ');
        $this->set('breadcrumbs_splitter',' ');
    }


    function modules_list() {
        global $_classPath,$PHPShopModules,$addHandler;

        $log=PHPShopText::tr(PHPShopText::b('Установлено'),PHPShopText::b('Модуль'),PHPShopText::b('Размещение'));
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.modules'));
        $data=$PHPShopOrm->select(array('*'),false,array('order'=>'date desc'),array('limit'=>100));
        if(is_array($data))
            foreach($data as $row) {
                $log.=PHPShopText::tr(PHPShopDate::dataV($row['date']),$row['name'],$_classPath.'modules/'.$row['path']);
            }

        $stat=PHPShopText::h3('Установленные модули').PHPShopText::table($log,$cellpadding=3,$cellspacing=1,$align='center',$width='98%',$bgcolor=false,$border=1,null,'table table-striped table-bordered');

        $autoload=PHPShopText::tr(PHPShopText::b('Видимость'),PHPShopText::b('Загрузчик'));
        if(is_array($PHPShopModules->ModValue['autoload']))
            foreach($PHPShopModules->ModValue['autoload'] as $val) {
                $autoload.=PHPShopText::tr('./*',$val);

            }

        $stat.=PHPShopText::h3('Автозагрузка').
                PHPShopText::table($autoload,$cellpadding=3,$cellspacing=1,$align='center',$width='98%',$bgcolor=false,$border=1,null,'table table-striped table-bordered');


        $core=PHPShopText::tr(PHPShopText::b('Путь'),PHPShopText::b('Исполнитель'));
        if(is_array($PHPShopModules->ModValue['core']))
            foreach($PHPShopModules->ModValue['core'] as $key=>$val) {
                $core.=PHPShopText::tr('/'.$key.'/',$val);

            }

        $stat.=PHPShopText::h3('Разделы ЧПУ').
                PHPShopText::table($core,$cellpadding=3,$cellspacing=1,$align='center',$width='98%',$bgcolor=false,$border=1,null,'table table-striped table-bordered');

        $hook=PHPShopText::tr(PHPShopText::b('Класс'),PHPShopText::b('Перехватчик'));
        if(is_array($PHPShopModules->ModValue['hook']))
            foreach($PHPShopModules->ModValue['hook'] as $class=>$v) {
                foreach($v as $key=>$val) {
                    if($key[0] != '#') {
                        if(is_file($val)) {
                            include_once($val);
                            if(is_array($addHandler))
                                foreach($addHandler as $old_f=>$new_f)
                                    if($old_f[0] != '#')
                                        $hook.=PHPShopText::tr(str_replace('phpshop','PHPShop',$class).'::'.$old_f.'()',$val);
                        }
                    }
                }

            }

        $stat.=PHPShopText::h3('Хуки').
                PHPShopText::table($hook,$cellpadding=3,$cellspacing=1,$align='center',$width='98%',$bgcolor=false,$border=1,null,'table table-striped table-bordered');

        $this->set('pageContent', $stat,true);


        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));

    }

    function key() {
        if($_GET['key'] == $this->system['text']) {
            $_SESSION['debug_guest']=1;
            $this->index();
        }
    }

    function key_forma() {
        $this->set('pageTitle','Debug');

        // Мета
        $this->title='Debug';

        // Навигация хлебные крошки
        $this->navigation(false,'Debug');
        $content=PHPShopText::setInputText('Ключ:','key',$_GET['key'],$size=200);
        $content.=PHPShopText::setInput('submit','key_check','Вход');
        $this->set('pageContent', PHPShopText::form($content,'key_action','get'),true);
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }


    function autorization() {
        if($this->system['enabled'] == 1) {
            if(!empty($_SESSION['idPHPSHOP'])) return true;
        }
        elseif($this->system['text'] != '') {
            if(!empty($_SESSION['debug_guest'])) return true;
        }

        else return true;
    }



    function phpinfo() {
        phpinfo();
        exit;
    }

    function debug_start() {
        $_SESSION['debug']=1;
        $GLOBALS['SysValue']['my']['debug']="true";
    }

    function debug_stop() {
        unset($_SESSION['debug']);
        $GLOBALS['SysValue']['my']['debug']="false";
    }

    function errorlog_clean() {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.errorlog.errorlog_log'));
        $PHPShopOrm->delete(array('id'=>'>0'));
        $this->error_log();
    }

    function getValue($param) {
        $param=explode(".",$param);
        if(count($param)>2) return $this->SysValue[$param[0]][$param[1]][$param[2]];
        return $this->SysValue[$param[0]][$param[1]];
    }


    function error_log() {
        $clean_button=null;
        $log=PHPShopText::tr(PHPShopText::b('Date'),PHPShopText::b('IP'),PHPShopText::b('Message'));
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.errorlog.errorlog_log'));
        $PHPShopOrm->debug=false;

        // Поиск
        if(!empty($_POST['words'])) $where=array('error'=>" REGEXP '".PHPShopSecurity::true_search($_POST['words'])."'");
        else $where=false;

        $data=$PHPShopOrm->select(array('*'),$where,array('order'=>'id desc'),array('limit'=>300));
        if(is_array($data)) {
            foreach($data as $row) {
                $log.=PHPShopText::tr(PHPShopDate::dataV($row['date']),$row['ip'],$row['error']);
            }

            $search_forma=PHPShopText::form(PHPShopText::setInputText('Найти:','words',PHPShopSecurity::true_search($_POST['words'])).PHPShopText::setInput('submit','submit','Найти','left'),'search_forma');
            $clean_button=PHPShopText::form(PHPShopText::setInput('submit','errorlog_clean','Очистить Error Log'),'log_action',$method='get');
        }
        $this->set('pageContent', PHPShopText::h1('Отладочные сообщения').$search_forma.$clean_button.PHPShopText::table($log,$cellpadding=3,$cellspacing=1,$align='center',$width='100%',$bgcolor=false,$border=0, $id = false, 'table table-striped').$clean_button,true);

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }


    function index() {

        if($this->autorization()) {

            $content=PHPShopText::setInput('submit','phpinfo','PHP Info');

            if(empty($_SESSION['debug']))
                $content.=PHPShopText::setInput('submit','debug_start','Debug Panel Start');
            else
                $content.=PHPShopText::setInput('submit','debug_stop','Debug Panel Stop');

            if($this->getValue('base.errorlog.errorlog_system') != '')
                $content.=PHPShopText::setInput('submit','error_log','Error Log');

            $content.=PHPShopText::setInput('submit','modules_list','Установленные модули');

            $form=PHPShopText::form($content,'debug_action',$method='get');

            // Определяем переменые
            $this->set('pageContent', PHPShopText::h1('Настройка').$form);
            $this->set('pageTitle','Debug');

            // Мета
            $this->title='Debug';

            // Навигация хлебные крошки
            $this->navigation(false,'Debug');

            // Подключаем шаблон
            $this->parseTemplate($this->getValue('templates.page_page_list'));
        }
        elseif($this->system['text'] != '')
            $this->key_forma();

        else $this->setError404();
    }

}

?>