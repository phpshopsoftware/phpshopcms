<?php

// Вывод комментариев
function comment_log($obj,$page) {

    $comment=null;
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['comment']['comment_log']);
    $PHPShopOrm->debug=false;
    /*
    $result = $PHPShopOrm->query('SELECT a.*, b.login FROM '.$GLOBALS['SysValue']['base']['comment']['comment_log'].' AS a
            JOIN '.$GLOBALS['SysValue']['base']['users']['users_base'].' AS b ON a.user_id = b.id
            WHERE a.page="'.$page.'" order by a.id desc');
    */

    $data=$PHPShopOrm->select(array('*'),array('page'=>"='$page'"),array('order'=>'id desc'),array('limit'=>300));
    if(is_array($data))
        foreach($data as $row) {
            $obj->set('commentLink',$row['id']);
            $obj->set('commentUser',$row['user']);
            $obj->set('commentDate',PHPShopDate::dataV($row['date'],false));
            $obj->set('commentContent',$row['content']);

            $comment.=ParseTemplateReturn($GLOBALS['SysValue']['templates']['comment']['comment_content'],true);

        }

    if(empty($comment)) $obj->set('commentList',$GLOBALS['SysValue']['lang']['comment_empty']);
    else $obj->set('commentList',$comment);

    $comment=ParseTemplateReturn($GLOBALS['SysValue']['templates']['comment']['comment_list'],true);
    return $comment;

}

// Запись комментария в БД
function comment_add($page) {

    // Очищаем комментарий
    $content=PHPShopSecurity::TotalClean($_POST['contentComment'],2);
    if(PHPShopSecurity::true_login($_SESSION['userName'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['comment']['comment_log']);
        $PHPShopOrm->insert(array('user_id_new'=>$_SESSION['userId'],'user_new'=>$_SESSION['userName'],'content_new'=>$content,'date_new'=>date("U"),'page_new'=>$page));
    }
}

// Запись рейтинга в БД
function rating_add($page) {

    if(PHPShopSecurity::true_login($_SESSION['userName']) and PHPShopSecurity::true_num($_POST['pageRatingAdd'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['comment']['comment_ratlog']);
        $PHPShopOrm->debug=true;
        $PHPShopOrm->insert(array('user_id_new'=>$_SESSION['userId'],'rating_new'=>$_POST['pageRatingAdd'],'date_new'=>date("U"),'page_new'=>$page));
    }
}


// Хук вставки в страницу комментариев
function comment_hook($obj,$row) {

    //$row['rating_enabled']=1;
    //$row['comment_enabled']=1;

    // Рейтинг
    if(!empty($row['rating_enabled'])) {
        $i=0;
        $star=1;
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['comment']['comment_ratlog']);
        $PHPShopOrm->debug=false;
        $data=$PHPShopOrm->select(array('avg(rating) as star'),array('page'=>"='".$obj->PHPShopNav->getName()."'"),false,false);
        if(is_array($data)) $star=$data['star'];

        if($star<1) $star=1;


        $star=16*$star;
        $obj->set('ratingStar',$star);
        if(!empty($_SESSION['userName'])) {
            $rating_forma=ParseTemplateReturn($GLOBALS['SysValue']['templates']['comment']['rating_forma'],true);
            $obj->set('ratingUpdate',$rating_forma,true);
            $rating=ParseTemplateReturn($GLOBALS['SysValue']['templates']['comment']['rating_list'],true);
        } else {

            $rating=ParseTemplateReturn($GLOBALS['SysValue']['templates']['comment']['rating_list'],true);
        }

        $obj->set('pageContent',$rating,true);

        // Запись рейтинга
        if(PHPShopSecurity::true_num($_POST['pageRatingAdd'])) {
            rating_add($obj->PHPShopNav->getName());
            header("Location: ".$_SERVER['HTTP_REFERER'].'#breadcrumbs');
        }
    }

    // Комментарии
    if(!empty($row['comment_enabled'])) {

        // Если включен вывод
        $PHPShopCommentArray = new PHPShopCommentArray();

        if(!empty($_SESSION['userName'])) {
            $forma=ParseTemplateReturn($GLOBALS['SysValue']['templates']['comment']['comment_forma'],true);
        } else $forma=$GLOBALS['SysValue']['lang']['comment_rules'];

        $comment=comment_log($obj,$obj->PHPShopNav->getName());
        $obj->set('pageContent',$comment.$forma,true);

        // Запись нового комментария
        if(PHPShopSecurity::true_param($_POST['contentComment'],$_POST['addComment'])) {
            comment_add($obj->PHPShopNav->getName());
            header("Location: ".$_SERVER['HTTP_REFERER'].'#breadcrumbs');
        }
    }

}

// Настройки модуля
PHPShopObj::loadClass("array");
class PHPShopCommentArray extends PHPShopArray {
    function __construct() {
        $this->objType=3;
        $this->objBase=$GLOBALS['SysValue']['base']['comment']['comment_system'];
        parent::__construct("enabled");
    }
}


$addHandler=array(
        'index'=>'comment_hook',
        'page'=>'comment_hook'
);

?>