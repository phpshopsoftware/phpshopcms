<?php

/**
 * �������������� ���������
 */
function tab_menu_service() {
    global $subpath;

    ${'menu_active_' . $subpath[1]} = 'active';
    
   
    $tree = '
       <ul class="nav nav-pills nav-stacked">
       <li class="' . $menu_active_service. '"><a href="?path=exchange.service">������������</a></li>
       <li class="' . $menu_active_sql . '"><a href="?path=exchange.sql">SQL ������ � ����</a></li>
       <li class="' . $menu_active_backup . '"><a href="?path=exchange.backup">��������� �����������</a></li>
       </ul>';
    
    return $tree;
}

?>