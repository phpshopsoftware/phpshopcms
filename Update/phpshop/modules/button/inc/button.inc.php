<?php

function button_menu_hook() {
    if (!$GLOBALS['SysValue']['other']['buttonOneStartFlag']) {
        $GLOBALS['SysValue']['other']['buttonOneStartFlag'] = 1;
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['button']['button_system']);
        $option = $PHPShopOrm->select();

        $dis = null;
        if ($option['enabled'] > 1 || $option['enabled'] == 0) {

            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['button']['button_forms']);
            $data = $PHPShopOrm->select(array('content', 'id'), array('enabled' => "='1'"), array('order' => 'num'), array('limit' => 100));

            if (is_array($data))
                foreach ($data as $row) {
                    $dis.='<div>' . str_replace('&#43;', '+',$row['content']) . '</div>';
                }

            $GLOBALS['SysValue']['other']['button_forms'] = $dis;
            $buttons = ParseTemplateReturn($GLOBALS['SysValue']['templates']['button']['button'], true);

            if ($option['enabled'] == 2) {
                $GLOBALS['SysValue']['other']['leftMenu'].=$buttons;
            } elseif ($option['enabled'] == 3)
                $GLOBALS['SysValue']['other']['rightMenu'].=$buttons;
            elseif ($option['enabled'] == 0)
                $GLOBALS['SysValue']['other']['button'].=$buttons;
        }
    }
}

button_menu_hook();
?>