<?php

/**
 * Дополнительная навигация
 */
function tab_menu() {
    global $subpath;

    ${'menu_active_' . $subpath[2]} = 'active';
    
   
    $tree = '
       <ul class="nav nav-pills nav-stacked">
       <li class="' . $menu_active_ . '"><a href="?path=exchange.'.$subpath[1].'">Товары</a></li>
       <li class="' . $menu_active_catalog . '"><a href="?path=exchange.'.$subpath[1].'.catalog">Каталоги</a></li>
       <li class="' . $menu_active_user . '"><a href="?path=exchange.'.$subpath[1].'.user">Пользователи</a></li>
       <li class="' . $menu_active_order . '"><a href="?path=exchange.'.$subpath[1].'.order">Заказы</a></li>
       </ul>';
    
    return $tree;
}

?>