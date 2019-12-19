<?php

/**
 * ���������� ���� �������
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopCore
 */
class PHPShopPhoto extends PHPShopCore {

    /**
     * @var Int  ���-�� ���� � �����
     */
    var $ilim = 4;
    var $empty_index_action=true;

    /**
     * �����������
     */
    function __construct() {

        // ���-�� ���� �� ��������
        $num_row = 30;

        // ��� ��
        $this->objBase = $GLOBALS['SysValue']['base']['table_name23'];

        // �������
        $this->debug = false;

        // ������ �������
        $this->action = array("nav" => "CID");

        // ������ ��� ��������� ������� ������
        $this->navigationArray = 'CatalogPhoto';

        // �� ��� ������� ������
        $this->navigationBase = 'base.table_name22';
        parent::PHPShopCore();

        $this->page = $GLOBALS['SysValue']['nav']['page'];
        if (strlen($this->page) == 0)
            $this->page = 1;

        $this->num_row = $num_row;
    }
    
    // ����������� PID ��������
    function getPid($name) {
        $PHPShopOrm = new PHPShopOrm($this->getValue($this->navigationBase));
        $PHPShopOrm->debug=$this->debug;
        $row=$PHPShopOrm->select(array('id'),array('seoname'=>"='$name'"),false,array('limit'=>1));
        return $row['id'];
    }

    
    function index() {

        // �������� ������
        //$this->name = $this->PHPShopNav->getName('/photo/');
        $this->name =$this->PHPShopNav->objNav['name'];
        $name = PHPShopSecurity::TotalClean($this->name, 2);

        // ID ���������
        $this->category = $this->getPid($name);

        if (!$this->category)
            return $this->setError404();

 
        $this->PHPShopPhotoCategory = new PHPShopPhotoCategory($this->category);
        $this->category_name = $this->PHPShopPhotoCategory->getName();

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name22'));
        $PHPShopOrm->debug = $this->debug;
        $row = $PHPShopOrm->select(array('id,name'), array('parent_to' => "=" . $this->category, 'enabled' => "='1'"), false, array('limit' => 1));

        // ���� ����
        if (empty($row['id'])) {

            $this->ListPhoto();
        }
        // ���� ��������
        else {

            $this->ListCategory();
        }
    }

    
    /**
     * ����� ������ ����
     */
    function ListPhoto() {
        $disp = null;
        $i = 0;

        // ���� ��� ���������
        $this->objPath = '/photo/'.$this->name.'_' . $this->category . '_';

        // ������� ������
        $this->dataArray = parent::getListInfoItem(array('*'), array('category' => '=' . $this->category, 'enabled' => "='1'"), array('order' => 'num'));
        if (is_array($this->dataArray))
            foreach ($this->dataArray as $row) {

                $name_s = str_replace(".", "s.", $row['name']);

                // ������ �����������
                $realsize = @getimagesize('http://' . $_SERVER['SERVER_NAME'] . $name_s);

                if (!empty($realsize[0]))
                    $width = 'width="' . $realsize[0] . '"';
                else
                    $width = null;
                if (!empty($realsize[1]))
                    $height = 'height="' . $realsize[1] . '"';
                else
                    $height = null;

                $disp.='<TD valign="top" align="center" style="width:92px;padding:2px;">
 <a class="highslide" onclick="return hs.expand(this)" target="_blank" href="' . $row['name'] . '">
 <img ' . $width . ' ' . $height . ' src="' . $name_s . '" border="0"></a><div class="highslide-caption">' . $row['info'] . '</div>
</TD>';
                if ($i < $this->ilim - 1) {
                    $i++;
                } else {
                    $i = 0;
                    $disp.='</TR><TR>';
                }
            }
        // ���� ���� �������� ��������
        if (empty($this->LoadItems['CatalogPhoto'][$this->category]))
            $content = $this->PHPShopPhotoCategory->getContent();
        elseif (!empty($this->LoadItems['CatalogPhoto'][$this->category]['content_enabled']))
            $content = $this->PHPShopPhotoCategory->getContent();

        $dis = '<script type="text/javascript" src="/highslide/highslide-p.js"></script>
<link rel="stylesheet" type="text/css" href="/highslide/highslide.css" />

<script type="text/javascript">
hs.registerOverlay({
  html: \'<div class="closebutton" onclick="return hs.close(this)" title="�������"></div>\',
  position: \'top right\',
  fade: 2 // fading the semi-transparent overlay looks bad in IE
});


hs.graphicsDir = \'/highslide/graphics/\';
hs.wrapperClassName = \'borderless\';
</script>
<p>' . $content . '<p>
<table border="0" cellspacing="0" cellpadding="0" >
<tr height="94">
' . $disp . '
</tr>
</table>';

        $this->set('pageContent', Parser($dis));
        $this->set('pageTitle', $this->category_name);

        // ���������
        $this->setPaginator();

        // Title
        if($this->PHPShopPhotoCategory->getValue('seotitle') != '')
            $this->title=$this->PHPShopPhotoCategory->getValue('seotitle');
        else $this->title=$this->category_name." - ".$this->PHPShopSystem->getValue("name");

        // Description
        $this->description=$this->PHPShopPhotoCategory->getValue('seodesc');

        // Keywords
        $this->keywords=$this->PHPShopPhotoCategory->getValue('seokey');

        // ��������� ������� ������
        $this->navigation($row['parent_to'], $this->category_name);

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * ����� ������ ��������� ����
     */
    function ListCategory() {

        // ������� ������
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name22'));
        $PHPShopOrm->debug = $this->debug;
        $dataArray = $PHPShopOrm->select(array('name', 'id','seoname','seotitle','seodesc','seokey'), array('parent_to' => '=' . $this->category), array('order' => 'num'), array('limit' => 100));
        if (is_array($dataArray))
            foreach ($dataArray as $row) {
                $dis.=PHPShopText::li($row['name'],"/photo/". $row['seoname'] . ".html");
           }

        //$disp = PHPShopText::h1($this->category_name);

        // ���� ���� �������� ��������
        if (!empty($this->LoadItems['CatalogPhoto'][$this->category]['content_enabled']))
            $disp.=$this->PHPShopPhotoCategory->getContent();

        $disp.=PHPShopText::ul($dis);

        $this->set('pageContent', $disp);
        $this->set('pageTitle', $this->category_name);

        // Title
        if($this->PHPShopPhotoCategory->getValue('seotitle') != '')
            $this->title=$this->PHPShopPhotoCategory->getValue('seotitle');
        else $this->title=$this->category_name." - ".$this->PHPShopSystem->getValue("name");

        // Description
        $this->description=$this->PHPShopPhotoCategory->getValue('seodesc');

        // Keywords
        $this->keywords=$this->PHPShopPhotoCategory->getValue('seokey');

        // ��������� ������� ������
        $this->navigation($this->category, $this->category_name);

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

}

/**
 * ���� �������
 * ���������� ������ � ����������� ���� �������
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopObj
 */
class PHPShopPhotoCategory extends PHPShopObj {

    /**
     * �����������
     * @param int $objID �� ���������
     */
    function PHPShopPhotoCategory($objID) {
        $this->objID = $objID;
        $this->objBase = $GLOBALS['SysValue']['base']['table_name22'];
        parent::PHPShopObj();
    }

    /**
     * ������ ����� ���������
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }

    /**
     * ������ �������� ���������
     * @return string
     */
    function getContent() {
        return parent::getParam("content");
    }

}

?>