<?php

/**
 * ���������
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopInc
 */
// ����������
$_classPath = 'phpshop/';

// �������� �� ������ /index.php/index.php
if (strstr($_SERVER['REQUEST_URI'], 'index.php')) {
    header('Location: /error/');
    exit();
}

// ����� �������
if ($PHPShopSystem->getValue('skin_choice')) {

    if (isset($_REQUEST['skin'])) {
        if (@file_exists("phpshop/templates/" . $_REQUEST['skin'] . "/index.html")) {
            $skin = $_REQUEST['skin'];
            if (PHPShopSecurity::true_login($_REQUEST['skin']))
                $_SESSION['skin'] = $_REQUEST['skin'];
        }
    }
    elseif (empty($_SESSION['skin'])) {
        $skin = $PHPShopSystem->getValue('skin');
        $_SESSION['skin'] = $skin;
    }
} else {
    $skin = $PHPShopSystem->getValue('skin');
    $_SESSION['skin'] = $skin;
}

// �������� ������������� �����
function Open($page) {
    global $SysValue;
    $page = $page . ".php";
    $handle = @opendir('pages');
    while ($file = readdir($handle)) {
        if ($file == $page) {
            return $page;
            exit;
        }
    }
    return $SysValue['my']['index'];
}

// �������� install
if (!getenv("COMSPEC")) {
    if (is_dir("./install/"))
        PHPShopBase::errorConnect(105, '���������� ���������', '������� ����� install');
}

// ���������� ����������
$SysValue['other']['telNum'] = $PHPShopSystem->getValue('tel');

// ������� ��� �������
if (strstr($SysValue['other']['telNum'], ","))
    $tel_xs = explode(" ", $SysValue['other']['telNum']);
else
    $tel_xs[] = $SysValue['other']['telNum'];
$SysValue['other']['telNumMobile'] = $tel_xs[0];


$SysValue['other']['streetAddress'] = $PHPShopSystem->getValue('addres');
$SysValue['other']['mail'] = $PHPShopSystem->getValue('admin_mail');
$SysValue['other']['pageCss'] = $SysValue['dir']['templates'] . chr(47) . $_SESSION['skin'] . chr(47) . $SysValue['css']['default'];
$SysValue['other']['version'] = substr($SysValue['upload']['version'], 0, 1) . '.' . substr($SysValue['upload']['version'], 1, 1);

// �������� ���� �������
$theme = $PHPShopSystem->getSerilizeParam('admoption.' . $_SESSION['skin'] . '_theme');
if (!empty($theme))
    $SysValue['other'][$_SESSION['skin'] . '_theme'] = $theme;

// �������� ������������� �������
if (!file_exists("phpshop/templates/" . $_SESSION['skin'] . "/index.html"))
    $_SESSION['skin'] = 'bootstrap';

// ����� �������
$PHPShopSkinElement = new PHPShopSkinElement();
$PHPShopSkinElement->init('skinSelect', true);

// �������� �������
include($SysValue['class']['modules']);
$PHPShopModules = new PHPShopModules();
$PHPShopModules->doLoad();

// �������� �����
$SysValue['other']['name'] = $PHPShopSystem->getValue('name');
$SysValue['other']['company'] = $PHPShopSystem->getValue('company');
$SysValue['other']['ShopDir'] = $SysValue['dir']['dir'];

// ����-������
$PHPShopGbookElement = new PHPShopGbookElement();
$PHPShopGbookElement->init('miniGbook');

// �����
$PHPShopOprosElement = new PHPShopOprosElement();
$PHPShopOprosElement->init('oprosDisp');

// ����-�������
$PHPShopNewsElement = new PHPShopNewsElement();
$PHPShopNewsElement->init('miniNews');

// ������
$PHPShopBannerElement = new PHPShopBannerElement();
$PHPShopBannerElement->init('banersDisp');

// ���������
$PHPShopAnalitica = new PHPShopAnalitica();

// ��������� ����
$PHPShopTextElement = new PHPShopTextElement();
$PHPShopTextElement->init('leftMenu', true); // ����� ������ �����
$PHPShopTextElement->init('rightMenu', true); // ����� ������� �����
$PHPShopTextElement->init('topMenu', true); // ����� �������� ����
$PHPShopTextElement->init('logo', true); // ����� ��������
$PHPShopTextElement->init('dadata', true); // ����� ��������� DaData.ru

//
// �������
$PHPShopCatalogElement = new PHPShopCatalogElement();
$PHPShopCatalogElement->init('mainMenuPage');

// �������
$PHPShopSliderElement = new PHPShopSliderElement();
$PHPShopSliderElement->init('imageSlider');

// �����������
$PHPShopPhotoElement = new PHPShopPhotoElement();
$PHPShopPhotoElement->init('mainMenuPhoto');
$PHPShopPhotoElement->init('getPhotos');

// RSS ������ ��������
$PHPShopRssParser = new PHPShopRssParser();

// Recaptcha
$PHPShopRecaptchaElement = new PHPShopRecaptchaElement();
$PHPShopRecaptchaElement->init('captcha');

// ����������� ����
if (!empty($SysValue['nav']['path'])) {
    $core_file = "./phpshop/core/" . $PHPShopNav->getPath() . ".core.php";
    $old_core_file = "pages/" . $PHPShopNav->getPath() . ".php";
    if (is_file($old_core_file)) {
        include_once("pages/" . Open($SysValue['nav']['path']));
    } elseif (!$PHPShopModules->doLoadPath($SysValue['nav']['path'])) {
        if (is_file($core_file)) {
            include_once($core_file);
            $classname = 'PHPShop' . ucfirst($SysValue['nav']['path']);
            if (class_exists($classname)) {
                $PHPShopCore = new $classname ();
                $PHPShopCore->loadActions();
            }
            else
                echo PHPShopCore::setError($classname, "�� ��������� ����� phpshop/core/$classname.core.php");
        }
        else
            include("pages/error.php");
    }
}
?>