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
    
    $data_pic = xml2array($skin_base_path . '/template5.php', "template", true);
    $title='<p class="text-muted hidden-xs data-row">Ниже представлены классические шаблоны стандарта HTML4 от предыдущих версий PHPShop. Функциональность классических шаблонов может отличаться от современных шаблонов. Для 100% функциональности продукта рекомендуется использовать современные шаблоны стандарта HTML5. При ошибке автоматической установки шаблонов следует скачать архив в ручном режиме и распаковать его на свой FTP в папку <code>/phpshop/templates/</code>. Некоторые классические шаблоны не совместимы с модулем <kbd>Seo Url 1.14</kbd>.</p>';
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

            $img_list.='<div class="col-md-3"><div class="panel '.$panel.'"><div class="panel-heading">' . $row['name'] .$mes. '<span class="glyphicon glyphicon-plus pull-right btn ' . $main . ' btn-xs skin-load" data-path="' . $row['name'] . '" data-toggle="tooltip" data-placement="top" title="' . __('Загрузить') . '"></span></div><div class="panel-body text-center"><img class="image-shadow" src="https://www.phpshopcms.ru'  . $row['icon'] . '"></div></div></div>';

            if ($i == 4) {
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
  
    
    return $disp;
}

?>
