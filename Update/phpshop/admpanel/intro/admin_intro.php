<?php

$TitlePage = __("������ ������������");



function actionStart() {
    global $PHPShopInterface, $PHPShopGUI, $TitlePage, $PHPShopBase;


    // �������� ����������
    if (!isset($_SESSION['update_check'])) {
        define("UPDATE_PATH", "http://www.phpshop.ru/update/updatecms5.php?from=" . $_SERVER['SERVER_NAME'] . "&version=" . $GLOBALS['SysValue']['upload']['version']);

        $update_enable = @xml2array(UPDATE_PATH, "update", true);

        if (is_array($update_enable) and $update_enable['status'] == 'active') {
            $_SESSION['update_check'] = intval($update_enable['name'] - $update_enable['num']);
        }
        else
            $_SESSION['update_check'] = 0;
    }


    $_SESSION['mod_limit'] = 50;



    $PHPShopGUI->setActionPanel($TitlePage, false, array('�����'));
    $PHPShopGUI->addJSFiles('js/chart.min.js', 'intro/gui/intro.gui.js');
    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setCaption(array("", "10%"), array("", "20%"), array("", "20%"), array("", "15%"), array("", "15%", array('align' => 'right')));

    // �������
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
    $PHPShopOrm->debug = false;
    $PHPShopOrm->sql = 'SELECT id,date,title FROM ' . $GLOBALS['SysValue']['base']['news'] . ' order by id desc limit 8';
    $data = $PHPShopOrm->select();
    if (is_array($data))
        foreach ($data as $row) {
            $PHPShopInterface->setRow(array('name' => $row['title'], 'link' => '?path=news&return=intro&id=' . $row['id'], 'class' => 'label-link'), array('name' => $row['date'], 'align' => 'right'));
        }
        
    // ������� ������
	if(is_array($data)){
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
    $PHPShopOrm->debug = false;
    $PHPShopOrm->sql = 'SELECT count(*) as num, date,title FROM ' . $GLOBALS['SysValue']['base']['news'] . ' GROUP BY date order by id desc limit 30';
    $canvas_value = $canvas_label = '0,';
    $data = $PHPShopOrm->select();
    if (is_array($data))
        foreach ($data as $row) {

            $canvas_value.='"' . $row['num'] . '",';
            $canvas_label.='"' . $row['date'] . '",';
            
        }

    $order_list = $PHPShopInterface->getContent();
	}

    // �����������
    $PHPShopInterface = new PHPShopInterface();
    $PHPShopInterface->checkbox_action = false;
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['jurnal']);
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id desc'), array('limit' => 5));
    if (is_array($data))
        foreach ($data as $row) {

            if (empty($row['flag'])) {
                $status = '<span class="glyphicon glyphicon-ok"></span>';
                $link = '?path=users&id=' . $row['id'];
            } else {
                $status = '<span class="glyphicon glyphicon-remove" style="color:red"></span>';
                $link = '?path=users.stoplist&action=new&ip=' . $row['ip'];
            }

            $PHPShopInterface->setRow($status, array('name' => $row['user'], 'link' => $link, 'align' => 'left'), array('name' => $row['ip'], 'align' => 'right'), array('name' => PHPShopDate::get($row['datas'], true), 'align' => 'right'));
        }

    $user_list = $PHPShopInterface->getContent();


    // ����� ��������
    $PHPShopInterface = new PHPShopInterface();
    $PHPShopInterface->checkbox_action = false;
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page']);
    $data = $PHPShopOrm->select(array('id,name,date'), false, array('order' => 'date desc'), array('limit' => 5));
    if (is_array($data))
        foreach ($data as $row) {


            $PHPShopInterface->setRow(
                    array('name' => $row['name'], 'link' => '?path=page.catalog&return=intro&id=' . $row['id'], 'align' => 'left'), array('name' => PHPShopDate::get($row['date'], false), 'align' => 'right'));
        }
    $product_list = $PHPShopInterface->getContent();


    $PHPShopGUI->_CODE.='

    <div class="row intro-row">
       <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-eye-open"></span> �������</div>
                <div class="panel-body text-right panel-intro">
                <a href="?path=page.catalog">' . $PHPShopBase->getNumRows('page', "where enabled='1'") . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-picture"></span> �����������</div>
                <div class="panel-body text-right panel-intro">
                 <a href="?path=photo.catalog">' . $PHPShopBase->getNumRows('photo', "where enabled='1'") . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-film"></span> �������</div>
                <div class="panel-body text-right panel-intro">
                 <a href="?path=banner">' . $PHPShopBase->getNumRows('banner', "") . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-list-alt"></span> �������</div>
                <div class="panel-body text-right panel-intro">
                 <a href="?path=news">' . $PHPShopBase->getNumRows('news', "") . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-envelope"></span> ����������</div>
                <div class="panel-body text-right panel-intro">
                 <a href="?path=news.letter">' . $PHPShopBase->getNumRows('newsletter', "") . '</a>
               </div>
          </div>
       </div>
        <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-bullhorn"></span> ������</div>
                <div class="panel-body text-right panel-intro">
                 <a href="?path=gbook">' . $PHPShopBase->getNumRows('gbook', "") . '</a>
               </div>
          </div>
       </div>
   </div>   
   
<div class="row intro-row">
       <div class="col-xs-12 col-md-12 col-lg-6">
           <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-list-alt"></span> ��������� ������� <a class="pull-right" href="?path=news">�������� ������</a></div>
                   <table class="table table-hover intro-list">' . $order_list . '</table>
          </div>
       </div>
       <div class="visible-lg col-lg-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-signal"></span> ������ �������� 
             <span class="pull-right hidden-xs">
             
<div class="dropdown">
  <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <span class="glyphicon glyphicon-cog"></span>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu dropdown-menu-right canvas-select">
    <li class="disabled"><a href="#" class="canvas-line">�������� ���������</a></li>
    <li><a href="#" class="canvas-bar">�����������</a></li>
    <li><a href="#" class="canvas-radar">����� ���������</a></li>
  </ul>
</div>
                </span>
              </div>
                <div class="panel-body">
                 <div class="intro-canvas">
                     <canvas id="canvas" data-currency=""  data-value=\'[' . substr($canvas_value, 0, strlen($canvas_value) - 1) . ']\' data-label=\'[' . substr($canvas_label, 0, strlen($canvas_label) - 1) . ']\'></canvas>
                 </div>
               </div>
          </div>
       </div>
   </div>
   
<div class="row intro-row">
       <div class="col-md-6 col-xs-12">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-user"></span> ������ ����������� <a class="pull-right" href="?path=users.jurnal">�������� ������</a></div>
                <table class="table table-hover intro-list">' . $user_list . '</table>
          </div>
       </div>
       <div class="col-md-6 col-xs-12">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-refresh"></span> ���������� ������� <a class="pull-right" href="?path=page.catalog">�������� ������</a></div>
                <table class="table table-hover intro-list">' . $product_list . '</table>
          </div>
       </div>
   </div>   
';

    $PHPShopGUI->Compile();
}

?>