<?php

class PHPShopIndex extends PHPShopCore {

    function __construct() {
        $this->objBase=$GLOBALS['SysValue']['base']['table_name11'];
        $this->debug=false;

        parent::PHPShopCore();
    }


    /**
     * Экшен по умолчанию
     */
    function index() {
        // Проверка на главную страницу
        if($GLOBALS['SysValue']['nav']['truepath'] == "/")
            $this->indexpage();
        else $this->page();

    }

    /**
     * Начальная страница
     */
    function indexpage() {
        // Шаблон главной страницы
        $this->template='templates.index';


        // Выборка данных
        $row=parent::getFullInfoItem(array('name,content'),array('category'=>"=2000",'enabled'=>"='1'"));

        // Определяем переменые
        $this->set('mainContent',Parser($row['content']));
        $this->set('mainContentTitle',Parser($row['name']));
    }



    /**
     * Страницы
     */
    function page() {
        global $PHPShopModules;

        $link=PHPShopSecurity::TotalClean($this->PHPShopNav->getName(),2);

        // Выборка данных
        $row=$this->PHPShopOrm->select(array('*'),array('link'=>"='$link'",'enabled'=>"!='0'"),false,array('limit'=>1));


        // Прикрываем страницу от дубля
        if($row['category'] == 2000)  return $this->setError404();
        elseif(empty($row['id'])) return $this->setError404();

        // Определяем переменные
        $this->set('pageContent',Parser($row['content']));
        $this->set('pageTitle',$row['name']);

        // Мета
        if(empty($row['title'])) $title=$row['name'];
        else $title=$row['title'];
        $this->title=$title." - ".$this->PHPShopSystem->getValue("name");
        $this->description=$row['description'];
        $this->keywords=$row['keywords'];
        $this->lastmodified=$row['datas'];


        // Навигация хлебные крошки
        $this->navigation($row['category'],$row['name']);

        // Перехват модуля
        $PHPShopModules->setHookHandler(__CLASS__,__FUNCTION__, $this, $row);

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    // Высчитываем seourl каталога
    function getSeourl($id) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug=$this->debug;
        $row=$PHPShopOrm->select(array('seoname'),array('id'=>"=".$id),false,array('limit'=>1));
        return $row['seoname'];
    }


    function navigation($id,$name) {
        $dis='';
        $spliter=ParseTemplateReturn($this->getValue('templates.breadcrumbs_splitter'));
        $home=ParseTemplateReturn($this->getValue('templates.breadcrumbs_home'));

        $arrayPath=$this->getNavigationPath($id);

        if(is_array($arrayPath)) {
            $arrayPath=array_reverse( $arrayPath);


            foreach($arrayPath as $v) {
                $dis.= $spliter.'<A href="/cat/'.$this->getSeourl($v['id']).'.html">'.$v['name'].'</a>';
            }

        }

        $dis=$home.$dis.$spliter.'<b>'.$name.'</b>';
        $this->set('breadCrumbs',$dis);

        // Навигация для javascript в shop.tpl
        $this->set('pageNameId',$id);
    }


}
?>