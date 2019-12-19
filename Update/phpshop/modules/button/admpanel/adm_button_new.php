<?php

$TitlePage = __('Создание новой кнопки');

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.button.button_forms"));


// Функция записи
function actionInsert() {
    global $PHPShopOrm;
    if(empty($_POST['num_new'])) $_POST['num_new']=1;
    if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;

    $action = $PHPShopOrm->insert($_POST);
    
    header('Location: ?path=' . $_GET['path']);
    
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopOrm;


    // Выборка
    $data['name']='Новая кнопка';
    $data['enabled']=1;
    $data['num']=1;
    

    $PHPShopGUI->field_col = 1;
    $Tab1 = $PHPShopGUI->setField('Название', $PHPShopGUI->setInputText(false, 'name_new', $data['name']));

    $Tab1.= $PHPShopGUI->setField('Приоритет', $PHPShopGUI->setInputText('№', 'num_new', $data['num'], '100') .
            $PHPShopGUI->setCheckbox('enabled_new', 1, 'Вкл.', $data['enabled']));
    
    // Редактор 
    $PHPShopGUI->setEditor('ace', true);
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '320';
    $oFCKeditor->Value = $data['content'];

    $Tab1.=$PHPShopGUI->setField('HTML Код', $oFCKeditor->AddGUI());

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,350));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=$PHPShopGUI->setInput("submit","saveID","Сохранить","right",false,false,false,"actionInsert.modules.create");

    $PHPShopGUI->setFooter($ContentFooter);
    
    return true;
}


// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>