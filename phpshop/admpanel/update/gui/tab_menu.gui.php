<?php

function tab_menu() {
    global $subpath,$help;

    ${'menu_active_' . $subpath[1]} = 'active';
    
    $tree = '
       <ul class="nav nav-pills nav-stacked">
       <li class="' . $menu_active_update . '"><a href="?path=update">Обновление</a></li>
       <li class="' . $menu_active_restore . '"><a href="?path=update.restore">Восстановление</a></li>
       </ul>';
    
        //$help = '<p class="text-muted">Требуется указать параметр <b>FTP подключения</b> к сайту согласно инструкции по обновлению в <a href="https://help.phpshop.ru/update" target="_blank" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-book"></span> Учебнике</a></p>';
        
        $help = '<p class="text-muted">Рекомендуется использовать Windows утилиту <a href="http://phpshop.ru/loads/files/setup.exe" target="_blank" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-cloud-download"></span> Updater.exe</a> для работы с обновлениями.</p>';
    
    return $tree;
}

?>
