<?php

class PHPShopCommentElement extends PHPShopElements {
    /**
     * @var int количество комментариев для вывода
     */
    var $num=10;

    function __construct() {
        $this->objBase=$GLOBALS['SysValue']['base']['comment']['comment_log'];
        $this->debug=false;
        parent::PHPShopElements();
        $this->option();
    }

    /**
     * Настройки
     */
    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['comment']['comment_system']);
        $this->data = $PHPShopOrm->select();

        // Сохраняем настройки
        $this->LoadItems['modules']['comment']['enabled']=$this->data['enabled'];
        $this->LoadItems['modules']['comment']['flag']=$this->data['flag'];
    }


    /**
     * Форма последних комментариев
     * @return string
     */
    function lastcommentForma() {
        $disp=null;
        $num=0;
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['comment']['comment_log']);
        $PHPShopOrm->debug=false;
        $result=$PHPShopOrm->query('SELECT  DISTINCT a.page,a.user, b.name FROM '.$GLOBALS['SysValue']['base']['comment']['comment_log'].' AS a
            JOIN '.$GLOBALS['SysValue']['base']['table_name11'].' AS b ON a.page = b.link order by a.id desc limit '.$this->num);
        while ($row = mysqli_fetch_array($result)) {

            // Учет модуля SEOURL
            if($this->getValue("base.seourl.seourl_system"))
                $this->set('commentPage','/'.$row['page']);
            else $this->set('commentPage','/page/'.$row['page']);

            $this->set('commentUser',$row['user']);
            $this->set('commentTitle',$row['name']);

            $disp.=ParseTemplateReturn($GLOBALS['SysValue']['templates']['comment']['comment_last_content'],true);

        }

        $this->set('leftMenuName',$GLOBALS['SysValue']['lang']['comment_last_title']);
        $this->set('leftMenuContent',$disp);
        return $this->parseTemplate($this->getValue('templates.left_menu'));
    }

    /**
     * Форма страниц в топе
     * @return string
     */
    function topratingForma() {
        $disp=null;
        $num=0;
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['comment']['comment_ratlog']);
        $PHPShopOrm->debug=false;
        $result=$PHPShopOrm->query(' SELECT
AVG(rating) as prating, page, pages.name
FROM '.$GLOBALS['SysValue']['base']['comment']['comment_ratlog'].' AS ratlog
INNER JOIN '.$GLOBALS['SysValue']['base']['table_name11'].' AS pages ON (ratlog.page = pages.link)
GROUP BY page ORDER BY prating DESC LIMIT '.$this->num);
        while ($row = mysqli_fetch_array($result)) {

            // Учет модуля SEOURL
            if($this->getValue("base.seourl.seourl_system"))
                $this->set('ratingtopPage','/'.$row['page']);
            else $this->set('ratingtopPage','/page/'.$row['page']);

            $this->set('ratingtopUser',$row['user']);
            $this->set('ratingtopName',$row['name']);

            $disp.=ParseTemplateReturn($GLOBALS['SysValue']['templates']['comment']['rating_top_forma'],true);

        }

        $this->set('leftMenuName',$GLOBALS['SysValue']['lang']['rating_top_title']);
        $this->set('leftMenuContent',$disp);
        return $this->parseTemplate($this->getValue('templates.left_menu'));
    }

}



// Вывод
$PHPShopCommentElement = new PHPShopCommentElement();
if(!empty($GLOBALS['LoadItems']['modules']['comment']['enabled'])) {

    if($GLOBALS['LoadItems']['modules']['comment']['flag']==1)  $GLOBALS['SysValue']['other']['rightMenu'].=$PHPShopCommentElement->lastcommentForma().$PHPShopCommentElement->topratingForma();
    else $GLOBALS['SysValue']['other']['leftMenu'].=$PHPShopCommentElement->lastcommentForma().$PHPShopCommentElement->topratingForma();

}else {
    $PHPShopCommentElement->init('lastcommentForma');
    $PHPShopCommentElement->init('topratingForma');
}

?>