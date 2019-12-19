<?php

function tab_menu() {
    global $subpath, $PHPShopSystem;

    ${'menu_active_' . $subpath[1]} = 'active';

    if (!empty($_GET['date_start']))
        $date = '&date_start=' . $_GET['date_start'] . '&date_end=' . $_GET['date_end'] . '&group_date=' . $_GET['group_date'];
    else
        $date = null;

    $tree = '
       <ul class="nav nav-pills nav-stacked">
       <li class="' . $menu_active_metrica . '"><a href="?path=metrica' . $date . '">' . __('������') . '</a></li>
       <li class="' . $menu_active_traffic . '"><a href="?path=metrica.traffic' . $date . '">' . __('������������') . '</a></li>
       <li class="' . $menu_active_popular . '"><a href="?path=metrica.popular' . $date . '">' . __('��������') . '</a></li>
       <li class="' . $menu_active_sources_summary . '"><a href="?path=metrica.sources_summary' . $date . '">' . __('���������, ������') . '</a></li>
       <li class="' . $menu_active_sources_social . '"><a href="?path=metrica.sources_social' . $date . '">' . __('���������� ����') . '</a></li>
       <li class="' . $menu_active_sources_sites . '"><a href="?path=metrica.sources_sites' . $date . '">' . __('�����') . '</a></li>
       <li class="' . $menu_active_search_phrases . '"><a href="?path=metrica.search_phrases' . $date . '">' . __('��������� �����') . '</a></li>
       <li class="' . $menu_active_search_engines . '"><a href="?path=metrica.search_engines' . $date . '">' . __('��������� �������') . '</a></li>
           ';

    if ($PHPShopSystem->ifSerilizeParam('admoption.metrica_ecommerce')) {
        $tree .= ' <li class="' . $menu_active_top_products . '"><a href="?path=metrica.top_products' . $date . '">' . __('���������� ������') . '</a></li>
       <li class="' . $menu_active_top_brands . '"><a href="?path=metrica.top_brands' . $date . '">' . __('���������� ���������') . '</a></li>
       <li class="' . $menu_active_basket . '"><a href="?path=metrica.basket' . $date . '">' . __('������ � �������') . '</a></li>
       <li class="' . $menu_active_purchase . '"><a href="?path=metrica.purchase' . $date . '">' . __('������') . '</a></li>
       <li class="' . $menu_active_basket_products . '"><a href="?path=metrica.basket_products' . $date . '">' . __('���������� ������') . '</a></li>
           ';
    }

    $tree.= '</ul>';
    return $tree;
}
?>