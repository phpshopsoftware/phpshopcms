<?php

function addLocale($data) {
    global $PHPShopGUI;

    // ��������� �������� � ������� actionStart
    $Tab3=$PHPShopGUI->setField("���������:",$PHPShopGUI->setInput("text",'title_news_locale_new',$data['title_news_locale'],"left",500));

    // �������� 2
    $oFCKeditor = new Editor('description_news_locale_new') ;
    $oFCKeditor->Height = '250';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Config['EditorAreaCSS'] = $MyStyle;
    $oFCKeditor->Value = $data['description_news_locale'];
    $oFCKeditor->Mod='textareas';

    // ���������� ��������
    $Tab3.=$oFCKeditor->AddGUI();


    // �������� 3
    $oFCKeditor = new Editor('content_news_locale_new') ;
    $oFCKeditor->Height = '300';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Config['EditorAreaCSS'] = $MyStyle;
    $oFCKeditor->Value = $data['content_news_locale'];
    $oFCKeditor->Mod='textareas';

    // ���������� ��������
    $Tab4.=$oFCKeditor->AddGUI();

    $PHPShopGUI->addTab(array("����",$Tab3,350),array("���� ���.",$Tab4,350));
}


$addHandler=array(
        'actionStart'=>'addLocale',
        'actionDelete'=>false,
        'actionUpdate'=>false
);

?>