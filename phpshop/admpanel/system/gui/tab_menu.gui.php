<?php

function tab_menu() {
    global $subpath;

    ${'menu_active_' . $subpath[1]} = 'active';
    
    $tree = '
       <ul class="nav nav-pills nav-stacked">
       <li class="' . $menu_active_system . '"><a href="?path=system">�������� ���������</a></li>
       <li class="' . $menu_active_seo . '"><a href="?path=system.seo">SEO ���������</a></li>
       <li class="' . $menu_active_image . '"><a href="?path=system.image">�����������</a></li>
       <li><a href="?path=tpleditor">������� �������</a></li>
       </ul>';
    
    return $tree;
}

?>
