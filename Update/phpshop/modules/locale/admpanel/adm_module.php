<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.locale.locale_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if(empty($_POST['skin_enabled_new'])) $_POST['skin_enabled_new']=0;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
}


// Выбор шаблона
function GetSkins($skin) {
    global $PHPShopGUI;
    $dir="../templates";
    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                if($skin == $file)
                    $sel="selected";
                else $sel="";

                if($file!="." and $file!=".." and $file!="index.html")
                    $value[]=array($file,$file,$sel);
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('skin_new',$value);
}


function actionStart() {
    global $PHPShopGUI,$PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    
    // Содержание закладки 1
    $Tab1=$PHPShopGUI->setField("Название сайта:",$PHPShopGUI->setInputText('','name_new',$data['name']));
    $Tab1.=$PHPShopGUI->setField("Дизайн 2-го языка:",GetSkins($data['skin']).
            $PHPShopGUI->setLine().
            $PHPShopGUI->setCheckbox('skin_enabled_new',1,'Использовать',$data['skin_enabled']));


    $Tab3=$PHPShopGUI->setPay();
    
    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,true),array("О Модуле",$Tab3,270));
    
    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", 1) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");
    
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>