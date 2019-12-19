<?php

class PHPShopIndex extends PHPShopCore {

    function __construct() {
        $this->objBase=$GLOBALS['SysValue']['base']['table_name11'];
        $this->debug=false;

        parent::PHPShopCore();
    }


    /**
     * ����� �� ���������
     */
    function index() {
        // �������� �� ������� ��������
        if($GLOBALS['SysValue']['nav']['truepath'] == "/")
            $this->indexpage();
        else $this->page();

    }

    /**
     * ��������� ��������
     */
    function indexpage() {
        // ������ ������� ��������
        $this->template='templates.index';


        // ������� ������
        $row=parent::getFullInfoItem(array('name,content'),array('category'=>"=2000",'enabled'=>"='1'"));

        // ���������� ���������
        $this->set('mainContent',Parser($row['content']));
        $this->set('mainContentTitle',Parser($row['name']));
    }



    /**
     * ��������
     */
    function page() {
        global $PHPShopModules;

        $link=PHPShopSecurity::TotalClean($this->PHPShopNav->getName(),2);

        // ������� ������
        $row=$this->PHPShopOrm->select(array('*'),array('link'=>"='$link'",'enabled'=>"!='0'"),false,array('limit'=>1));


        // ���������� �������� �� �����
        if($row['category'] == 2000)  return $this->setError404();
        elseif(empty($row['id'])) return $this->setError404();

        // ���������� ����������
        $this->set('pageContent',Parser($row['content']));
        $this->set('pageTitle',$row['name']);

        // ����
        if(empty($row['title'])) $title=$row['name'];
        else $title=$row['title'];
        $this->title=$title." - ".$this->PHPShopSystem->getValue("name");
        $this->description=$row['description'];
        $this->keywords=$row['keywords'];
        $this->lastmodified=$row['datas'];


        // ��������� ������� ������
        $this->navigation($row['category'],$row['name']);

        // �������� ������
        $PHPShopModules->setHookHandler(__CLASS__,__FUNCTION__, $this, $row);

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    // ����������� seourl ��������
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

        // ��������� ��� javascript � shop.tpl
        $this->set('pageNameId',$id);
    }


}
?>