<?php

// Заголовок
$TitlePage = __("Модули");

function getFileInfo($file) {
    $f = parse_ini_file("../../license/" . $file, 1);

    $_SESSION['mod_limit'] = 50;

    return $f['License']['SupportExpires'];
}

$_SESSION['mod_limit'] = 50;

// Информация по модулю
function GetModuleInfo($name) {
    $path = "../modules/" . $name . "/install/module.xml";
    return xml2array($path, false, true);
}

function ChekInstallModule($path, $num = false) {
    global $link_db;

    $return = array();
    $sql = 'SELECT a.*, b.key FROM ' . $GLOBALS['SysValue']['base']['modules'] . ' AS a LEFT OUTER JOIN ' . $GLOBALS['SysValue']['base']['modules_key'] . ' AS b ON a.path = b.path where a.path="' . $path . '"';

    $result = mysqli_query($link_db, $sql);
    $row = mysqli_fetch_array($result);
    if (mysqli_num_rows($result) > 0) {
        $return[0] = "#C0D2EC";
        $return[1] = array('status' => array('enable' => 1, 'align' => 'right', 'caption' => array('Выкл', 'Вкл')));
        $return[2] = $row['date'];
        $return[3] = $row['key'];
    } elseif ($num >= $_SESSION['mod_limit']) {

        $return[0] = "white";
        $return[1] = array('name' => '<span class="glyphicon glyphicon-lock pull-right text-muted" data-toggle="tooltip" data-placement="left" title="Лимит превышен"></span>');
        $return[2] = null;
        $return[3] = $row['key'];
    } else {
        $return[0] = "white";
        $return[1] = array('status' => array('enable' => 0, 'align' => 'right', 'caption' => array('<span class="text-muted">Выкл</span>', 'Вкл')));
        $return[2] = null;
        $return[3] = $row['key'];
    }
    return $return;
}

function actionStart() {
    global $PHPShopInterface, $PHPShopBase;


    $PHPShopInterface->action_select['Отключить выбранные'] = array(
        'name' => 'Отключить выбранные',
        'action' => 'module-off-select',
        'class' => 'disabled',
        'url' => '#'
    );

    $PHPShopInterface->action_select['Включить выбранные'] = array(
        'name' => 'Включить выбранные',
        'action' => 'module-on-select',
        'class' => 'disabled',
        'url' => '#'
    );

    if ($PHPShopBase->Rule->CheckedRules('modules', 'remove')) {
        $PHPShopInterface->action_button['Загрузить'] = array(
            'name' => '',
            'action' => '',
            'class' => 'btn btn-default btn-sm navbar-btn load-module',
            'type' => 'button',
            'icon' => 'glyphicon glyphicon-plus',
            'tooltip' => 'data-toggle="tooltip" data-placement="left" title="Загрузить модуль"'
        );
    }


    $PHPShopInterface->action_title['manual'] = 'Инструкция';


    $PHPShopInterface->setActionPanel(__("Модули"), array('Отключить выбранные', 'Включить выбранные'), array('Загрузить'));



    $PHPShopInterface->setCaption(
            array(null, "3%"), array("Описание", "60%"), array("Установлено", "15%"), array("", "10%"), array("Статус" . "", "7%", array('align' => 'right'))
    );

    $PHPShopInterface->addJSFiles('./js/jquery.treegrid.js', './modules/gui/modules.gui.js');
    $PHPShopInterface->path = 'modules.action';


    $where = false;
    if (!empty($_GET['cat'])) {
        $where = array('category' => '=' . intval($_GET['cat']));
    }

    // Количество установленных модулей
    if (empty($_GET['install'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['modules']);
        $data = $PHPShopOrm->select(array('*'), false, false, array('limit' => intval($_SESSION['mod_limit'])));
        $num = count($data);
    }


    $path = "../modules/";
    $i = 1;

    if (isset($_GET['install'])) {

        $active_tree_menu = 'install';

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['modules']);
        $data = $PHPShopOrm->select(array('*'), false, array('order' => 'date desc'), array('limit' => intval($_SESSION['mod_limit'])));
        $num = count($data);
        if (is_array($data))
            foreach ($data as $row) {
                $ChekInstallModule = ChekInstallModule($row['path']);
                $drop_menu = null;

                // Информация по модулю
                $Info = GetModuleInfo($row['path']);

                if (!empty($Info['status']))
                    $new = '<span class="label label-primary">' . $Info['status'] . '</span>';
                else
                    $new = null;

                if (!empty($Info['faqlink']))
                    $wikiPath = $Info['faqlink'];
                else
                    $wikiPath = 'http://wiki.phpshop.ru/index.php/Modules#' . str_replace(' ', '_', $Info['name']);

                if (!empty($Info['trial']) and empty($ChekInstallModule[3])) {
                    $trial = ' (Trial 30 дней)';
                }
                else
                    $trial = null;


                if (!$PHPShopBase->Rule->CheckedRules('modules', 'edit')) {
                    $status = '<span class="glyphicon glyphicon-lock pull-right"></span>';
                    $drop_menu = null;
                } else {
                    $status = $ChekInstallModule[1];

                    $drop_menu = array('option', 'manual', 'id' => $row['path']);


                    // Меню модуля
                    if (is_array($Info['adminmenu']['podmenu'][0])) {
                        foreach ($Info['adminmenu']['podmenu'] as $menu_value) {
                            array_push($drop_menu, array('name' => $menu_value['podmenu_name'], 'url' => '?path=modules.' . $menu_value['podmenu_action']));
                        }
                    } else {
                        array_push($drop_menu, array('name' => $Info['adminmenu']['podmenu']['podmenu_name'], 'url' => '?path=modules.' . $Info['adminmenu']['podmenu']['podmenu_action']));
                    }

                    array_push($drop_menu, '|');
                    array_push($drop_menu, 'off');
                }

                $name = '<div class="modules-list">
                            <a href="?path=modules&id=' . $row['path'] . '" data-wiki="' . $wikiPath . '">' . $Info['name'] . ' ' . $Info['version'] . $trial . '</a> ' . $new . '<br>' . $Info['description'] . '</div>';


                $PHPShopInterface->setRow($row['path'], $name, '<span class="install-date">' . PHPShopDate::get($row['date']) . '</span>', array('action' => $drop_menu, 'align' => 'center'), $status);

                $i++;
            }
    } elseif (@$dh = opendir($path)) {

        $active_tree_menu = $_GET['cat'];

        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != "..") {

                if (is_dir($path . $file)) {

                    // Информация по модулю
                    $Info = GetModuleInfo($file);

                    if (!empty($Info['status']))
                        $new = '<span class="label label-primary">' . $Info['status'] . '</span>';
                    else
                        $new = null;

                    // Если выбрана категория
                    if (isset($_GET['cat']) and @strstr($Info['category'], $_GET['cat']) and empty($Info['hidden'])) {

                        $ChekInstallModule = ChekInstallModule($file, $num);

                        // Дата установки
                        if (!empty($ChekInstallModule[2])) {
                            $InstallDate = date("d-m-Y", $ChekInstallModule[2]);
                            $drop_menu = array('option', 'manual', '|', 'off', 'id' => $file);
                        } elseif ($num < $_SESSION['mod_limit']) {
                            $InstallDate = null;
                            $drop_menu = array('manual', '|', 'on', 'id' => $file);
                        } else {
                            $InstallDate = null;
                            $drop_menu = null;
                        }

                        if (!empty($Info['trial']) and empty($ChekInstallModule[3])) {
                            $trial = ' (Trial 30 дней)';
                        }
                        else
                            $trial = null;

                        if (!empty($Info['faqlink']))
                            $wikiPath = $Info['faqlink'];
                        else
                            $wikiPath = 'http://wiki.phpshop.ru/index.php/Modules#' . str_replace(' ', '_', $Info['name']);


                        if (!$PHPShopBase->Rule->CheckedRules('modules', 'edit') or EXPIRES < $Info['sign']) {
                            $status = '<span class="glyphicon glyphicon-lock pull-right"></span>';
                            unset($drop_menu);
                        }
                        else
                            $status = $ChekInstallModule[1];

                        $name = '<div class="modules-list">
                            <a href="?path=modules&id=' . $file . '" data-wiki="' . $wikiPath . '">' . $Info['name'] . ' ' . $Info['version'] . $trial . '</a> ' . $new . '<br>' . $Info['description'] . '</div>';


                        $PHPShopInterface->setRow($file, $name, '<span class="install-date">' . $InstallDate . '</span>', array('action' => $drop_menu, 'align' => 'center'), $status);

                        $i++;
                    }
                    // Вывод всех модулей
                    elseif (empty($_GET['cat']) and empty($Info['hidden'])) {

                        $active_tree_menu = 'all';

                        $ChekInstallModule = ChekInstallModule($file, $num);

                        if (!empty($Info['status']))
                            $new = '<span class="label label-primary">' . $Info['status'] . '</span>';
                        else
                            $new = null;

                        // Дата установки
                        if (!empty($ChekInstallModule[2])) {
                            $InstallDate = date("d-m-Y", $ChekInstallModule[2]);
                            $drop_menu = array('option', 'manual', '|', 'off', 'id' => $file);
                        } elseif ($num < $_SESSION['mod_limit']) {
                            $InstallDate = null;
                            $drop_menu = array('manual', '|', 'on', 'id' => $file);
                        } else {
                            $InstallDate = null;
                            $drop_menu = null;
                        }

                        if (!empty($Info['trial']) and empty($ChekInstallModule[3])) {
                            $trial = ' (Trial 30 дней)';
                        }
                        else
                            $trial = null;

                        if (!empty($Info['faqlink']))
                            $wikiPath = $Info['faqlink'];
                        else
                            $wikiPath = 'http://wiki.phpshop.ru/index.php/Modules#' . str_replace(' ', '_', $Info['name']);


                        if (!$PHPShopBase->Rule->CheckedRules('modules', 'edit') or EXPIRES < $Info['sign']) {
                            $status = '<span class="glyphicon glyphicon-lock pull-right"></span> ';
                            unset($drop_menu);
                        }
                        else
                            $status = $ChekInstallModule[1];

                        $name = '<div class="modules-list">
                            <a href="?path=modules&id=' . $file . '" data-wiki="' . $wikiPath . '">' . $Info['name'] . ' ' . $Info['version'] . $trial . '</a> ' . $new . '<br>' . $Info['description'] . '</div>';

                        $PHPShopInterface->setRow($file, $name, '<span class="install-date">' . $InstallDate . '</span>', array('action' => $drop_menu, 'align' => 'center'), $status);
                        $i++;
                    }
                }
            }
        }
        closedir($dh);
    }

    if ($num == $_SESSION['mod_limit'])
        $label_class = 'label-warning';
    else
        $label_class = 'label-primary';

    $tree = '<table class="tree table table-hover">
        <tr class="treegrid-all">
           <td><a href="?path=modules" class="treegrid-parent" data-parent="treegrid-all">Все модули</a></td>
	</tr>
        <tr class="treegrid-template">
           <td><a href="?path=modules&cat=template" class="treegrid-parent" data-parent="treegrid-template">Дизайн</a></td>
	</tr>
        <tr class="treegrid-form">
           <td><a href="?path=modules&cat=form" class="treegrid-parent" data-parent="treegrid-form">Юзабилити</a></td>
	</tr>
        <tr class="treegrid-soc">
           <td><a href="?path=modules&cat=soc" class="treegrid-parent" data-parent="treegrid-soc">Социальные сети</a></td>
	</tr>
        <tr class="treegrid-seo">
           <td><a href="?path=modules&cat=seo" class="treegrid-parent" data-parent="treegrid-seo">SEO</a></td>
	</tr>
        <tr class="treegrid-sale">
           <td><a href="?path=modules&cat=sale" class="treegrid-parent" data-parent="treegrid-sale5">Продажи</a></td>
	</tr>
        <tr class="treegrid-user">
           <td><a href="?path=modules&cat=user" class="treegrid-parent" data-parent="treegrid-user">Конверсия</a></td>
	</tr>
        <tr class="treegrid-develop">
           <td><a href="?path=modules&cat=develop" class="treegrid-parent" data-parent="treegrid-develop">Разработчикам</a></td>
	</tr>
        <tr class="treegrid-install">
           <td><a href="?path=modules&install=check" class="treegrid-parent" data-parent="treegrid-install">Установленные</a> <span id="mod-install-count" class="label ' . $label_class . '">' . $num . '</span></td>
	</tr>
    </table>
    <script>
    var modcat="' . $active_tree_menu . '";
    </script>';




    $sidebarleft[] = array('title' => 'Категории', 'content' => $tree);
    $PHPShopInterface->setSidebarLeft($sidebarleft, 2);

    $PHPShopInterface->Compile(2);
}

?>