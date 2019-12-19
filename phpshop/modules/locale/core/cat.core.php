<?php

class PHPShopCat extends PHPShopCore {

    function __construct() {
        $this->objBase=$GLOBALS['SysValue']['base']['table_name11'];
        $this->objPath="/cat/";
        $this->debug=false;
        parent::PHPShopCore();
    }

    // Высчитываем PID каталога
    function getPid($name) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug=$this->debug;
        $row=$PHPShopOrm->select(array('id'),array('seoname'=>"='$name'"),false,array('limit'=>1));
        return $row['id'];
    }

    // Высчитываем seourl каталога
    function getSeourl($id) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug=$this->debug;
        $row=$PHPShopOrm->select(array('seoname'),array('id'=>"=".$id),false,array('limit'=>1));
        return $row['seoname'];
    }

    function index() {

        // Получаем ссылку
        $this->name=str_replace('cat','',$this->PHPShopNav->getName());
        $name=PHPShopSecurity::TotalClean($this->name,2);


        // ID категории
        $this->category=$this->getPid($name);

        if(!$this->category) return $this->setError404();

        $this->PHPShopCategory = new PHPShopCategory($this->category);
        $this->category_name=$this->PHPShopCategory->getName();

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug=$this->debug;
        $row=$PHPShopOrm->select(array('id,name'),array('parent_to'=>"=".$this->category),false,array('limit'=>1));

        // Если страницы
        if(empty($row['id'])) {

            $this->ListPage();
        }
        // Если каталоги
        else {

            $this->ListCategory();
        }
    }



    function ListPage() {
        $dis='';

        // 404
        if(!isset($this->category_name)) return $this->setError404();

        // Выборка данных
        $this->dataArray=$this->PHPShopOrm->select(array('name,link,category'),array('category'=>'='.$this->category,'enabled'=>"='1'"),
                array('order'=>'num'),array('limit'=>100));
        if(is_array($this->dataArray))
            foreach($this->dataArray as $row) {
                $dis.="<li><a href=\"/".$row['link'].".html\" title=\"".$row['name']."\">".$row['name']."</a></li>";
            }



        $disp="<h1>".$this->category_name."</h1>";

        // Если есть описание каталога
        if(!empty($this->LoadItems['CatalogPage'][$this->category]['content_enabled']))
            $disp.=$this->PHPShopCategory->getContent();

        $disp.="<ul>$dis</ul>";


        $this->set('pageContent',$disp);
        $this->set('pageTitle',$this->category_name);

        // Мета
        $this->title=$this->category_name." - ".$this->PHPShopSystem->getValue("name");


        // Навигация хлебные крошки
        $this->navigation($row['category'],$this->category_name);

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));

    }

    function ListCategory() {

        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name'));
        $PHPShopOrm->debug=$this->debug;
        $dataArray=$PHPShopOrm->select(array('name','id','seoname'),array('parent_to'=>'='.$this->category),array('order'=>'num'),array('limit'=>100));
        if(is_array($dataArray))
            foreach($dataArray as $row) {
                $dis.="<li><a href=\"/cat/".$row['seoname'].".html\" title=\"".$row['name']."\">".$row['name']."</a></li>";
            }

        $disp="<h1>".$this->category_name."</h1>";

        // Если есть описание каталога
        if(!empty($this->LoadItems['CatalogPage'][$this->category]['content_enabled']))
            $disp.=$this->PHPShopCategory->getContent();

        $disp.="<ul>$dis</ul>";


        $this->set('pageContent',$disp);
        $this->set('pageTitle',$this->category_name);

        // Мета
        $this->title=$this->category_name." - ".$this->PHPShopSystem->getValue("name");


        // Навигация хлебные крошки
        $this->navigation($this->category,$this->category_name);

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));

    }

    function navigation($id,$name) {
        $dis='';
        $spliter=ParseTemplateReturn($this->getValue('templates.breadcrumbs_splitter'));
        $home=ParseTemplateReturn($this->getValue('templates.breadcrumbs_home'));

        $arrayPath=$this->getNavigationPath($id);

        if(is_array($arrayPath)) {
            $arrayPath=array_reverse( $arrayPath);
            
            array_pop($arrayPath);

            foreach($arrayPath as $v) {
                $dis.= $spliter.'<A href="/'.$this->PHPShopNav->getPath().'/'.$this->getSeourl($v['id']).'.html">'.$v['name'].'</a>';
            }

        }

        $dis=$home.$dis.$spliter.'<b>'.$name.'</b>';
        $this->set('breadCrumbs',$dis);

        // Навигация для javascript в shop.tpl
        $this->set('pageNameId',$id);
    }

}

?>