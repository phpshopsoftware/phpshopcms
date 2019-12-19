<?php


// Запись лога
function setLog($data=false,$nameHandler=false) {
    global $TitlePage;
    $PHPShopOrm= new PHPShopOrm('phpshop_modules_admlog_log');

    // Тип действия
    if(!$nameHandler) {
        if(!empty($_POST['editID'])) {
            if($_POST['actionList']['editID'] == 'actionInsert') $nameHandler = 'Создание';
            else $nameHandler = 'Редактирование';
        }
        elseif(!empty($_REQUEST['delID'])) $nameHandler = 'Удаление';
        else  $nameHandler = 'Сохранение';
    } else $TitlePage = 'Журнал событий';

    // Заголовок
    $titleSearch=array('name_new','title_new','login_new','link_new','info_new','order_num');
    foreach($_POST as $key=>$val) {
        if(in_array($key, $titleSearch)) {
            $titleName = $_POST[$key];
            break;
        }
    }

    $log=array(
            'date_new'=>date('U'),
            'user_new'=>$_SESSION['logPHPSHOP'],
            'ip_new'=>$_SERVER['REMOTE_ADDR'],
            'file_new'=>$nameHandler,
            'title_new'=>$TitlePage.' -> '.$nameHandler.' - > '.$titleName,
            'content_new'=>serialize($_REQUEST)
    );

    $PHPShopOrm->insert($log);
}


// Добавляем значения в функции
$addHandler=array(
        'actionStart'=>false,
        'actionDelete'=>'setLog',
        'actionUpdate'=>'setLog',
        'actionInsert'=>'setLog',
        'actionSave'=>'setLog'
);
?>