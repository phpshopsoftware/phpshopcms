<?php

function tab_menu() {
    global $subpath,$help;

    ${'menu_active_' . $subpath[1]} = 'active';
    
    $tree = '
       <ul class="nav nav-pills nav-stacked">
       <li class="' . $menu_active_update . '"><a href="?path=update">����������</a></li>
       <li class="' . $menu_active_restore . '"><a href="?path=update.restore">��������������</a></li>
       </ul>';
    
        //$help = '<p class="text-muted">��������� ������� �������� <b>FTP �����������</b> � ����� �������� ���������� �� ���������� � <a href="https://help.phpshop.ru/update" target="_blank" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-book"></span> ��������</a></p>';
        
        $help = '<p class="text-muted">������������� ������������ Windows ������� <a href="http://phpshop.ru/loads/files/setup.exe" target="_blank" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-cloud-download"></span> Updater.exe</a> ��� ������ � ������������.</p>';
    
    return $tree;
}

?>
