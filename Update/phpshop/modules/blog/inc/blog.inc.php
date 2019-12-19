<?php

/**
 * ������� ��������� ������ �����
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopElements
 */

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

class PHPShopBlogElement extends PHPShopElements {

    /**
     * @var bool  ���������� ������ �� �������
     */
    var $disp_only_index = false;

    /**
     * @var Int ���-�� �������
     */
    var $limit = 3;

    /**
     * �����������
     */
    function __construct() {

        // �������
        $this->debug = false;

        // ��� ��
        $this->objBase = $GLOBALS['SysValue']['base']['blog']['blog_log'];
        parent::__construct();
        $this->option();
    }

    /**
     * ���������
     */
    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['blog']['blog_system']);
        $this->data = $PHPShopOrm->select();

        // ��������� ���������
        $this->LoadItems['modules']['blog']['enabled'] = $this->data['enabled'];
        $this->LoadItems['modules']['blog']['flag'] = $this->data['flag'];
        $this->LoadItems['modules']['blog']['enabled_menu'] = $this->data['enabled_menu'];
    }

    /**
     * ����� ��������� ����������
     * @return string
     */
    function lastblogForma() {
        $disp = null;
        $num = 0;
        $data = $this->PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array("limit" => $this->limit));
        if (is_array($data))
            foreach ($data as $row) {

                // ���������� ����������
                $this->set('blogId', $row['id']);
                $this->set('blogZag', $row['title']);
                $this->set('blogData', $row['date']);
                $this->set('blogKratko', $row['description']);

                if (!empty($row['content'])) {
                    $this->set('blogComStart', '');
                    $this->set('blogComEnd', '');
                } else {
                    $this->set('blogComStart', '<!--');
                    $this->set('blogComEnd', '-->');
                }

                $disp.=ParseTemplateReturn($GLOBALS['SysValue']['templates']['blog']['blog_main_mini'], true);
            }

        $this->set('leftMenuName', $this->data['title']);
        $this->set('leftMenuContent', $disp);
        return $this->parseTemplate($this->getValue('templates.left_menu'));
    }

    /**
     * ����� ��������� ������� �����
     * @return string
     */
    function last() {
        global $PHPShopModules;
        $dis = '';


        $view = true;

        if ($view) {
            $data = $this->PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array("limit" => $this->limit));
            if (is_array($data))
                foreach ($data as $row) {

                    // ���������� ���������
                    $this->set('blogId', $row['id']);
                    $this->set('blogZag', $row['title']);
                    $this->set('blogData', $row['date']);
                    $this->set('blogKratko', $row['description']);

                    if (!empty($row['content'])) {
                        $this->set('blogComStart', '');
                        $this->set('blogComEnd', '');
                    } else {
                        $this->set('blogComStart', '<!--');
                        $this->set('blogComEnd', '-->');
                    }
                    // �������� ������
                    $PHPShopModules->setHookHandler(__CLASS__, __FUNCTION__, $this, $row);

                    // ���������� ������
                    $dis.=$this->parseTemplate($this->getValue('templates.blog.blog_main_mini'));
                }
            return $dis;
        }
    }

    // ���������� ������ � ���-����
    function addToTopMenu() {

        // �������� ����
        $this->set('topMenuName', $this->data['title']);

        // ������
        $this->set('topMenuLink', 'index');

        // ������ ������������
        $this->set('userName', $_SESSION['userName']);
        $this->set('userMail', $_SESSION['userMail']);

        // ��������� ������ � ������� 'page' �� 'example'
        $dis = $this->PHPShopModules->Parser(array('page' => 'blog'), $this->getValue('templates.top_menu'));
        return $dis;
    }

}

$PHPShopBlogElement = new PHPShopBlogElement();

// ������ � ���������
if (!empty($GLOBALS['LoadItems']['modules']['blog']['enabled_menu'])) {
    $GLOBALS['SysValue']['other']['topMenu'].=$PHPShopBlogElement->addToTopMenu();
}

if (!empty($GLOBALS['LoadItems']['modules']['blog']['enabled'])) {

    if ($GLOBALS['LoadItems']['modules']['blog']['flag'] == 1)
        $GLOBALS['SysValue']['other']['rightMenu'].=$PHPShopBlogElement->lastblogForma();
    else
        $GLOBALS['SysValue']['other']['leftMenu'].=$PHPShopBlogElement->lastblogForma();
}else {
    $PHPShopBlogElement->init('lastblogForma');
}
?>