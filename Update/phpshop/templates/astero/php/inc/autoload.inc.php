<?php

// Цветовые темы CSS
if (isset($_COOKIE['astero_theme'])) {
    if (PHPShopSecurity::true_skin($_COOKIE['astero_theme'])) {
        $GLOBALS['SysValue']['other']['astero_theme'] = $_COOKIE['astero_theme'];
    }
    else
        $GLOBALS['SysValue']['other']['astero_theme'] = 'bootstrap-theme-default';
} /* elseif (!empty($GLOBALS['SysValue']['other']['template_theme']))
  $GLOBALS['SysValue']['other']['bootstrap_theme'] = $GLOBALS['SysValue']['other']['template_theme']; */
elseif (empty($GLOBALS['SysValue']['other']['astero_theme'])){
    $GLOBALS['SysValue']['other']['astero_theme'] = 'bootstrap-theme-default';
    setcookie('astero_theme', 'bootstrap-theme-default');
}
else  setcookie('astero_theme', $GLOBALS['SysValue']['other']['astero_theme']);


function create_theme_menu($file) {
    
    $current = $GLOBALS['SysValue']['other']['astero_theme'];
    if(empty($current)) $current='bootstrap-theme-default';
    
    $color = array(
        'green' => '#3DC964',
        'red' => '#FF749A',
        'blue' => '#5697E7',
        'orange' => '#FF8E71',
        'yellow' => '#E1A339',
        'default' => '#3BC6E4'
    );
    if (preg_match("/^bootstrap-theme-([a-zA-Z0-9_]{1,30}).css$/", $file, $match)) {
        $icon = $color[$match[1]];
        if (empty($icon))
            $icon = $match[1];

        if ($current == 'bootstrap-theme-' . $match[1])
            $check = '<span class="glyphicon glyphicon-ok"></span>';
        else
            $check = null;

        return '<div class="bootstrap-theme text-center" style="background:' . $icon . '" title="' . $match[1] . '" data-skin="bootstrap-theme-' . $match[1] . '">' . $check . '</div>';
    }
}

// Редактор тем оформления
if ($GLOBALS['SysValue']['template_theme']['user'] == 'true' or !empty($_SESSION['logPHPSHOP']) or !empty($GLOBALS['SysValue']['other']['skinSelect'])) {

    // CSS
    $PHPShopCssParser = new PHPShopCssParser($GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/css/' . $GLOBALS['SysValue']['other']['astero_theme'] . '.css');
    $css_parse = $PHPShopCssParser->parse();

    // XML
    PHPShopObj::loadClass(array('xml', 'admgui'));
    $PHPShopGUI = new PHPShopGUI();

    // bootstrap-colorpicker
    $PHPShopGUI->addCSSFiles($GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/css/bootstrap-colorpicker.min.css',$GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/css/editor.css');
    $PHPShopGUI->addJSFiles($GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/js/bootstrap-colorpicker.min.js',$GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/js/editor.js');

    $option = xml2array($GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/editor/style.xml', false, true);
    $css_edit.=$PHPShopGUI->includeJava . $PHPShopGUI->includeCss;

    if (is_array($option))
        foreach ($option['element'] as $id => $element) {

            if (is_array($element['var'][0]))
                $element_var = $element['var'];
            else
                $element_var[0] = $element['var'];

            if (is_array($element_var))
                foreach ($element_var as $var) {

                    // Цвет
                    if ($var['type'] == 'color') {
                        $css_edit.=$PHPShopGUI->setField($element['description'], $PHPShopGUI->setInputColor($var['name'], str_replace(array('!important'), array(''), $css_parse[$element['name']][$var['name']]), 130, 'color-' . $id, $element['name']), 5, $element['content']);
                    }

                    // Фильтр
                    else if ($var['type'] == 'slider') {
                        $current_filter = $PHPShopCssParser->getParam($element['name'], '-editor-filter');

                        $filter = '<div id="color-slide" data-option="' . $current_filter . '"></div><input type="hidden" name="filter" class="color-filter color-value" value="' . $current_filter . '" data-option="' . $element['name'] . '" id="color-' . $id . '"> ';
                        $css_edit.=$PHPShopGUI->setField($element['description'], $filter, 5, $element['content']);
                    }

                    // Тема
                    else if ($var['type'] == 'theme') {
                        $theme = PHPShopFile::searchFile($GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/css/', 'create_theme_menu');
                        $css_edit.=$PHPShopGUI->setField($element['description'], $theme, 5, $element['content']);
                    }
                }
        }

    // Сохранить
    if (!empty($_SESSION['logPHPSHOP'])){
        $css_edit.=$PHPShopGUI->setButton('Сохранить', 'floppy-disk', 'saveTheme');
        //$css_edit.=$PHPShopGUI->setButton('Управлять', 'cog', 'openAdminModal pull-right');
    }

    // Панель                       
    $theme_menu = $PHPShopGUI->setPanel('Оформление', $css_edit . $theme_menu, 'panel-default form-horizontal');
    //$theme_menu.='<div class="text-muted editor-help">Для обновления цвета в редакторе используйте сочетания клавиш <kbd>Ctrl</kbd> + <kbd>F5</kbd></div>';
    
    // Память вывода панели
    if (!empty($_COOKIE['style_selector_status'])) {
        if ($_COOKIE['style_selector_status'] == 'enabled') {
            $editor['right'] = 0;
            $editor['close'] = 'ss-close';
        } else {
            $editor['right'] = -280;
            $editor['close'] = false;
        }
    } else if ($GLOBALS['SysValue']['template_theme']['demo'] == 'true') {
        $editor['right'] = 0;
        $editor['close'] = 'ss-close';
    } else {
        $editor['right'] = -280;
        $editor['close'] = false;
    }

    $theme_menu = '
        <div id="style-selector" style="width: 280px; right: ' . $editor['right'] . 'px;" class="hidden-xs hidden-sm">
        <div class="style-toggle ' . $editor['close'] . '" title="Панель оформления"></div>
           <div id="style-selector-container">
              <div class="style-selector-wrapper">
              ' . $GLOBALS['SysValue']['other']['skinSelect'] . $theme_menu . '
              </div>
           </div>
        </div>';

    if ($GLOBALS['SysValue']['template_theme']['demo'] == 'true' or !empty($_SESSION['logPHPSHOP']) or !empty($GLOBALS['SysValue']['other']['skinSelect']))
        $GLOBALS['SysValue']['other']['editor'] = $theme_menu;
}

?>