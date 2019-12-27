<?php

// Преобразование символов в латиницу
function setLatin($str) {
    $str=strtolower($str);
    $str=str_replace("/", "", $str);
    $str=str_replace("\\", "", $str);
    $str=str_replace("(", "", $str);
    $str=str_replace(")", "", $str);
    $str=str_replace(":", "", $str);
    $str=str_replace(" ", "-", $str);
    $str=str_replace("\"", "", $str);
    $str=str_replace(".", "", $str);
    $str=str_replace("«", "", $str);
    $str=str_replace("»", "", $str);
    $str=str_replace("ь", "", $str);
    $str=str_replace("ъ", "", $str);

    $_Array=array(
            "а"=>"a",
            "б"=>"b",
            "в"=>"v",
            "г"=>"g",
            "д"=>"d",
            "е"=>"e",
            "ё"=>"e",
            "ж"=>"zh",
            "з"=>"z",
            "и"=>"i",
            "й"=>"i",
            "к"=>"k",
            "л"=>"l",
            "м"=>"m",
            "н"=>"n",
            "о"=>"o",
            "п"=>"p",
            "р"=>"r",
            "с"=>"s",
            "т"=>"t",
            "у"=>"u",
            "ф"=>"f",
            "х"=>"h",
            "ц"=>"c",
            "ч"=>"ch",
            "ш"=>"sh",
            "щ"=>"sh",
            "ы"=>"y",
            "э"=>"e",
            "ю"=>"uy",
            "я"=>"ya",
            "А"=>"a",
            "Б"=>"b",
            "В"=>"v",
            "Г"=>"g",
            "Д"=>"d",
            "E"=>"e",
            "Ё"=>"e",
            "Ж"=>"gh",
            "З"=>"z",
            "И"=>"i",
            "Й"=>"i",
            "К"=>"k",
            "Л"=>"l",
            "М"=>"m",
            "Н"=>"n",
            "О"=>"o",
            "П"=>"p",
            "Р"=>"r",
            "С"=>"s",
            "Т"=>"t",
            "У"=>"u",
            "Ф"=>"f",
            "Х"=>"h",
            "Ц"=>"c",
            "Ч"=>"ch",
            "Ш"=>"sh",
            "Щ"=>"sh",
            "Э"=>"e",
            "Ю"=>"uy",
            "Я"=>"ya",
            "."=>"",
            ","=>"",
            "$"=>"i",
            "%"=>"i",
            "&"=>"and");

    $chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);

    foreach($chars as $val)
        if(empty($_Array[$val])) @$new_str.=$val;
        else $new_str.=$_Array[$val];

    return $new_str;
}

// Автогенерация урлов каталогов фото
function setGenerationPhoto() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name22']);
    $data = $PHPShopOrm->select(array('id','name'),false,false,array('limit'=>1000));
    
    if(is_array($data))
      foreach($data as $val){
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name22']);
        $array['seoname_new']=setLatin($val['name']);
        $PHPShopOrm->update($array,array('id'=>'='.$val['id']));
    }
          
}

// Автогенерация урлов каталогов
function setGeneration() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name']);
    $data = $PHPShopOrm->select(array('id','name'),false,false,array('limit'=>1000));
    
    if(is_array($data))
      foreach($data as $val){
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name']);
        $array['seoname_new']=setLatin($val['name']);
        $PHPShopOrm->update($array,array('id'=>'='.$val['id']));
    }
          
}

// Автогенерация урлов новостей
function setGenerationNews() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name8']);
    $data = $PHPShopOrm->select(array('id','title'),false,false,array('limit'=>1000));

    if(is_array($data))
      foreach($data as $val){
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name8']);
        $array['seo_name_new']=setLatin($val['title']);
        $PHPShopOrm->update($array,array('id'=>'='.$val['id']));
    }

}


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    
    // Автогенерация урлов
    if(!empty($_POST['generation'])) setGeneration();
    if(!empty($_POST['generationnews'])) setGenerationNews();
    if(!empty($_POST['generationphoto'])) setGenerationPhoto();
    
    header('Location: ?path=modules&install=check');
}


function actionStart() {
    global $PHPShopGUI;
    
    // Создаем объекты для формы
    $ContentField1=$PHPShopGUI->setCheckbox("generation",1,"Запустить автоматическую генерацию SeoUrl путем перевода на латиницу имени каталогов.",false);
    $ContentField1.=$PHPShopGUI->setLine();
    $ContentField1.=$PHPShopGUI->setCheckbox("generationnews",1,"Запустить автоматическую генерацию SeoUrl путем перевода на латиницу заголовков новостей.",false);
    $ContentField1.=$PHPShopGUI->setLine();
    $ContentField1.=$PHPShopGUI->setCheckbox("generationphoto",1,"Запустить автоматическую генерацию SeoUrl путем перевода на латиницу имени фото-каталогов.",false);
    
    // Содержание закладки 1
    $Tab1=$PHPShopGUI->setField("Автогенерация",$ContentField1);
    
    $Tab3=$PHPShopGUI->setPay();
    
    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,true),array("О Модуле",$Tab3));
    
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