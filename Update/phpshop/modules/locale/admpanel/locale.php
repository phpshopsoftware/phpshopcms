<?php

function getNameLocale(){
    
    $pathinfo=pathinfo($_SERVER['PHP_SELF']);
    $file=$pathinfo['dirname'];
    
        $baseMap=array(
            'page'=>'_',
            'catalog'=>'_cat_',
            'menu'=>'_menu_'
    );
    
    $dirSearch=array_keys($baseMap);
    
    
    foreach($dirSearch as $val)
        if(strpos($file,$val)) {
            $baseName=$baseMap[$val];
            return $baseName;
        }
}


function addLocale($data) {
    global $PHPShopGUI;

    $prefix = getNameLocale();
    
    // ��������� �������� � ������� actionStart
    $Tab3=$PHPShopGUI->setField("���������:",$PHPShopGUI->setInput("text",'name'.$prefix.'locale_new',$data['name'.$prefix.'locale'],"left",400));
    
    // �������� 2
    $oFCKeditor = new Editor('content'.$prefix.'locale_new') ;
    $oFCKeditor->Height = '370';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Config['EditorAreaCSS'] = $MyStyle;
    $oFCKeditor->Value = $data['content'.$prefix.'locale'];
    $oFCKeditor->Mod='textareas';
    
    // ���������� ��������
    $Tab3.=$oFCKeditor->AddGUI();
    $PHPShopGUI->addTab(array("����",$Tab3,450));
}


$addHandler=array(
        'actionStart'=>'addLocale',
        'actionDelete'=>false,
        'actionUpdate'=>false
);

?>