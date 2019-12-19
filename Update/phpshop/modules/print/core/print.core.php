<?php

class PHPShopPrint extends PHPShopCore {

    function __construct() {
        $this->objBase=$GLOBALS['SysValue']['base']['table_name11'];
        $this->debug=false;
        parent::PHPShopCore();


    }



    function index() {

        // ������������
        $link=PHPShopSecurity::TotalClean($this->PHPShopNav->getName(),2);
        $link=str_replace("print","",$link);

        // ������� ������
        $row=parent::getFullInfoItem(array('*'),array('link'=>"='$link'"));


        // ���������� ���������
        $this->set('pageContent',Parser($row['content']));
        $this->set('pageTitle',$row['name']);
        $this->set('pageLink',$row['link']);

        // ����
        if(empty($row['title'])) $title=$row['name'];
        else $title=$row['title'];
        $this->title=$title." - ".$this->PHPShopSystem->getValue("name");
        $this->description=$row['description'];
        $this->keywords=$row['keywords'];
        $this->lastmodified=$row['datas'];


        // ��������� ������� ������
        $this->navigation($row['category'],$row['name']);

        // ���������� ������
        $pageContent=ParseTemplateReturn($GLOBALS['SysValue']['templates']['print']['print_page_forma'],true);
        exit($pageContent);
    }

   
}
?>