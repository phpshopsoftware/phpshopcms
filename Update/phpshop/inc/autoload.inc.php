<?php

/**
 * Загрузчик
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopInc
 */
// Библиотека
$_classPath = 'phpshop/';

// Защищаем от дублей /index.php/index.php
if (strstr($_SERVER['REQUEST_URI'], 'index.php')) {
    header('Location: /error/');
    exit();
}

// Определяем переменные
$SysValue['other']['telNum'] = $PHPShopSystem->getValue('tel');

// Телефон для звонков
if (strstr($SysValue['other']['telNum'], ","))
    $tel_xs = explode(" ", $SysValue['other']['telNum']);
else
    $tel_xs[] = $SysValue['other']['telNum'];
$SysValue['other']['telNumMobile'] = $tel_xs[0];


$SysValue['other']['streetAddress'] = $PHPShopSystem->getValue('addres');
$SysValue['other']['mail'] = $PHPShopSystem->getValue('admin_mail');
$SysValue['other']['pageCss'] = $SysValue['dir']['templates'] . chr(47) . $_SESSION['skin'] . chr(47) . $SysValue['css']['default'];
$SysValue['other']['version'] = substr($SysValue['upload']['version'], 0, 1) . '.' . substr($SysValue['upload']['version'], 1, 1);

// Цветовая тема шаблона
$theme = $PHPShopSystem->getSerilizeParam('admoption.' . $_SESSION['skin'] . '_theme');
if (!empty($theme))
    $SysValue['other'][$_SESSION['skin'] . '_theme'] = $theme;

// Проверка существования шаблона
if (!file_exists("phpshop/templates/" . $_SESSION['skin'] . "/index.html"))
    $_SESSION['skin'] = 'bootstrap';

// Выбор шаблона
$PHPShopSkinElement = new PHPShopSkinElement();
$PHPShopSkinElement->init('skinSelect', true);

// Загрузка модулей
include($SysValue['class']['modules']);
$PHPShopModules = new PHPShopModules();
$PHPShopModules->doLoad();

// Описание сайта
$SysValue['other']['name'] = $PHPShopSystem->getValue('name');
$SysValue['other']['company'] = $PHPShopSystem->getValue('company');
$SysValue['other']['ShopDir'] = $SysValue['dir']['dir'];

// Мини-отзывы
$PHPShopGbookElement = new PHPShopGbookElement();
$PHPShopGbookElement->init('miniGbook');

// Опрос
$PHPShopOprosElement = new PHPShopOprosElement();
$PHPShopOprosElement->init('oprosDisp');

// Мини-новости
$PHPShopNewsElement = new PHPShopNewsElement();
$PHPShopNewsElement->init('miniNews');

// Баннер
$PHPShopBannerElement = new PHPShopBannerElement();
$PHPShopBannerElement->init('banersDisp');

// Облако тегов
$PHPShopCloudElement = new PHPShopCloudElement();
$PHPShopCloudElement->init('cloud');

// Текстовый блок
$PHPShopTextElement = new PHPShopTextElement();
$PHPShopTextElement->init('leftMenu', true); // Вывод левого блока
$PHPShopTextElement->init('rightMenu', true); // Вывод правого блока
$PHPShopTextElement->init('topMenu', true); // Вывод главного меню
$PHPShopTextElement->init('logo', true); // Вывод логотипа
//
// Каталог
$PHPShopCatalogElement = new PHPShopCatalogElement();
$PHPShopCatalogElement->init('mainMenuPage');

// Слайдер
$PHPShopSliderElement = new PHPShopSliderElement();
$PHPShopSliderElement->init('imageSlider');

// Фотогалерея
$PHPShopPhotoElement = new PHPShopPhotoElement();
$PHPShopPhotoElement->init('mainMenuPhoto');
$PHPShopPhotoElement->init('getPhotos');

// RSS грабер новостей
$PHPShopRssParser = new PHPShopRssParser();

// Подключение ядра
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
                echo PHPShopCore::setError($classname, "не определен класс phpshop/core/$classname.core.php");
        }
        else
            include("pages/error.php");
    }
}
?>