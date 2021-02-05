<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.googletranslate.googletranslate_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm,$PHPShopModules;
    
    // Настройки витрины
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);
    
    $_POST['lang_new']= serialize($_POST['lang']);

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id='.$_GET['id']);
    return $action;
}

// Выбор языка
function GetLocaleList($lang) {
    global $PHPShopGUI;
    $dir = "../modules/googletranslate/lib/images/lang/";
    
    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                $name = explode(".",$file);
                $name=str_replace("lang__","",$name['0']);
             
                if (@in_array($name,$lang))
                    $sel = "selected";
                else
                    $sel = "";

                if ($file != "." and $file != ".." )
                    $value[] = array($name, $name, $sel, 'data-content="<img src=\'' . $dir  . $file .'\'> ' . ucfirst ($name) . '"');
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('lang[]', $value,300,false, false,false, false, 1, true);
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->title = "Настройка модуля JivoSite";

    // Выборка
    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField('Языки перевода', GetLocaleList(unserialize($data['lang'])));

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1,true), array("О Модуле", $Tab3));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>