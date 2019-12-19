<?php

/**
 * Панель дополнительных шаблонов
 * @param array $row массив данных
 * @return string 
 */
function tab_base($data) {
    global $PHPShopGUI,$skin_base_path,$PHPShopSystem;


    // Установленные шаблоны
    if (is_array($data))
        foreach ($data as $val) {
            $path_parts = pathinfo($val);
            $ready_theme[] = $path_parts['basename'];
        }


    $i = 1;
    $count = 0;
    /*
    $data_pic = xml2array($skin_base_path . '/template5.php', "template", true);
    $title='<p class="text-muted hidden-xs data-row">Ниже представлены классические шаблоны стандарта HTML4 от предыдущих версий PHPShop. Функциональность классических шаблонов может отличаться от современных шаблонов. Для 100% функциональности продукта рекомендуется использовать современные шаблоны стандарта HTML5.</p>';
    $img_list = null;
    if (is_array($data_pic))
        foreach ($data_pic as $row) {

            if ($i == 1)
                $img_list.='<div class="row">';

            if (in_array($row['name'],$ready_theme)){
                $main = "hide";
                $panel = 'panel-success';
                $mes = ' - Установлен';
            }
            else{
                $main = "btn-default";
                $panel = 'panel-default';
                $mes = null;
            }

            $img_list.='<div class="col-md-4"><div class="panel '.$panel.'"><div class="panel-heading">' . $row['name'] .$mes. '<span class="glyphicon glyphicon-plus pull-right btn ' . $main . ' btn-xs skin-load" data-path="' . $row['name'] . '" data-toggle="tooltip" data-placement="top" title="' . __('Загрузить') . '"></span></div><div class="panel-body text-center"><img class="image-shadow" src="' . $skin_base_path . $row['icon'] . '"></div></div></div>';

            if ($i == 3) {
                $img_list.='</div>';
                $i = 1;
            }
            else
                $i++;

            $count++;
        }


    if (count($data_pic) % 4 != 0)
        $img_list.='</div>';


    if (!empty($img_list))
        $disp= $PHPShopGUI->setCollapse(__('Дополнительные шаблоны'), $title.$img_list);
    else $disp=$PHPShopGUI->setAlert('Ошибка связи с сервером '.$skin_base_path, $type = 'warning'); 
*/
    
    $promo='            <!-- DESIGN CATALOG -->
            <div id="mgdcwidget-container" class="mgdcwidget-container"></div><script type="text/javascript">document.write(\'<script id="mgdcwidget-loader" src="//design.srv18.com/widget/index.php?id=9c78ae4de7f738ce19e1490cf24e97830a2ff711de59c22d6fc3d0d837cb3f78&\' + (new Date).getTime() + \'" type="text/javascript" charset="utf-8" async="async"><\/script>\');</script>
            <!-- /DESIGN CATALOG -->';
    
    /*
    if($PHPShopSystem->getSerilizeParam('admoption.templateshop_enabled') != 1)
    $disp.=$PHPShopGUI->setCollapse(__('Магазин дизайнов'), $promo);*/
    
        $promo='Дизайн-бюро <a href="http://phpshop-design.ru" target="_blank">PHPShop.Design</a> делает дизайны только для  PHPShop, а значит, неожиданностей при создании дизайна не произойдет, и  вы получите уникальный профессиональный дизайн в срок, отвечающий всем  требованиям сегодняшнего дня. <ol>
        <li>Мы на 100% знаем свою платформу, а это значит, что  Вам не придется переплачивать за часы работы дизайнера, не знакомого с  PHPShop. </li>
        <li>Мы стараемся учитывать всю функциональность PHPShop  еще на первом этапе его создания, и вы получите работающий  интернет-магазин таким, каким Вы его видите на утвержденном Вами макете. </li>
        <li>Большинство доработок, ранее требовавших  вмешательства в код платформы, на новой версии PHPShop 5,  производятся с помощью "дизайн-хуков", - это значит, что в будущем вы  сможете обновляться без потери доработок. </li>
        <li>Мы соблюдаем сроки, и предоставляем гарантии - если  после завершения проекта Вы заметите недочет с нашей стороны  мы  устраним его. </li>
    </ol>
    <p>Для заказа персонального дизайна нужно заполнить бриф, в котором вы  формулируете будущий проект, все возникающие вопросы уточнить у наших  консультантов. Cрок создания макета дизайна - 15 рабочих дней.</p>
    <p>
    <a href="http://phpshop-design.ru/page/brif-design.html" target="_blank" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-share-alt"></span> Бриф на Персональный дизайн сайта</a></p>';
    $disp.=$PHPShopGUI->setCollapse(__('Персональный дизайн'), $promo);
    
    return $disp;
}

?>
