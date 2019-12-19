<?php

function addLocale($data) {
    global $PHPShopGUI;

    // Добавляем значения в функцию actionStart
    $Tab3=$PHPShopGUI->setField("Заголовок:",$PHPShopGUI->setInput("text",'title_news_locale_new',$data['title_news_locale'],"left",500));

    // Редактор 2
    $oFCKeditor = new Editor('description_news_locale_new') ;
    $oFCKeditor->Height = '250';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Config['EditorAreaCSS'] = $MyStyle;
    $oFCKeditor->Value = $data['description_news_locale'];
    $oFCKeditor->Mod='textareas';

    // Содержание закладки
    $Tab3.=$oFCKeditor->AddGUI();


    // Редактор 3
    $oFCKeditor = new Editor('content_news_locale_new') ;
    $oFCKeditor->Height = '300';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Config['EditorAreaCSS'] = $MyStyle;
    $oFCKeditor->Value = $data['content_news_locale'];
    $oFCKeditor->Mod='textareas';

    // Содержание закладки
    $Tab4.=$oFCKeditor->AddGUI();

    $PHPShopGUI->addTab(array("Язык",$Tab3,350),array("Язык доп.",$Tab4,350));
}


$addHandler=array(
        'actionStart'=>'addLocale',
        'actionDelete'=>false,
        'actionUpdate'=>false
);

?>