<?php

// ���������
$TitlePage = __("�������� ��������");

$skin_base_path = 'http://template.phpshop.ru';

function _tpl($file) {

    // �������� ������
    $TemplateHelper = array(
        'banner' => '������',
        'baner_list_forma.tpl' => '����� �������',
        'catalog' => '�������',
        'catalog_forma.tpl' => '����� �������� �������',
        'catalog_forma_2.tpl' => '����� �������� ������� �',
        'catalog_forma_3.tpl' => '����� �������� ������� C',
        'catalog_info_forma.tpl' => '����� �������� �������� �������',
        'catalog_page_forma.tpl' => '����� �������� ������',
        'catalog_page_forma_2.tpl' => '����� � �������� ������',
        'catalog_page_info_forma.tpl' => '����� �������� �������� ������',
        'catalog_table_forma.tpl' => '����� �������� ���������',
        'cid_category.tpl' => '����� ������ ���������',
        'catalog_photo_1.tpl' => '����� �������� �����������',
        'catalog_photo_1_point.tpl' => '����� ����������� �����������',
        'podcatalog_forma.tpl' => '����� ����������� �������',
        'podcatalog_page_forma.tpl' => '����� ����������� ������',
        'main' => '��������',
        'index.tpl' => '������� ��������',
        'left_menu.tpl' => '����� ��������� ����',
        'right_menu.tpl' => '������ ��������� ����',
        'shop.tpl' => '������ ��������',
        'top_menu.tpl' => '�������������� ����',
        'valuta_forma.tpl' => '����� ������ ������',
        'news' => '�������',
        'main_news_forma.tpl' => '����� �������� ��������',
        'main_news_forma_full.tpl' => '����� ���������� ��������',
        'news_main_mini.tpl' => '����� ����-��������',
        'news_page_full.tpl' => '�������� ���������� ��������',
        'news_page_list.tpl' => '�������� ������ ��������',
        'page' => '��������',
        'page_catalog_list.tpl' => '����� �������� �������� ������',
        'page_page_list.tpl' => '����� ��������',
        'product' => '������',
        'brand_uid_description.tpl' => '�������� ������',
        'main_product_forma_1.tpl' => '����� ������ ������ � 1 ������',
        'main_product_forma_2.tpl' => '����� ������ ������ � 2 ������',
        'main_product_forma_3.tpl' => '����� ������ ������ � 3 ������',
        'main_product_forma_4.tpl' => '����� ������ ������ � 4 ������',
        'main_product_forma_full.tpl' => '����� ���������� �������� ������',
        'main_product_forma_full_productArt.tpl' => '�������',
        'main_product_odnotip_list.tpl' => '������ ���������� ���������',
        'main_spec_forma_icon.tpl' => '����� ���������������-������',
        'newtipIcon.tpl' => '������ �������',
        'product_odnotip_product_parent.tpl' => '���� ���������� �������',
        'product_odnotip_product_parent_one.tpl' => '����� ������������ ������',
        'product_option_product.tpl' => '����� ������ ����� ������',
        'product_page_full.tpl' => '�������� ���������� �������� ������',
        'product_page_list.tpl' => '�������� ������ �������',
        'product_page_spec_list.tpl' => '�������� ���������������',
        'product_pagetema_forma.tpl' => '����� ������ � ������',
        'product_pagetema_list.tpl' => '������ ������ � ������',
        'specIcon.tpl' => '������ ���������������',
        'style.css' => 'C���� ����������',
        'page_forma_list.tpl' => '����� �������� �����',
        'page_page_forma.tpl' => '����� �������� �������� �����'
    );

    if ($_GET['option'] != 'pro' && !empty($TemplateHelper[$file]))
        $result = $TemplateHelper[$file];
    else
        $result = $file;

    return substr($result, 0, 40);
}

/**
 * ����� �������
 */
function actionStart() {
    global $PHPShopGUI, $TitlePage, $PHPShopSystem, $selectModalBody;

    $PHPShopGUI->addJSFiles('./js/jquery.waypoints.min.js', './js/jquery.treegrid.js', './tpleditor/gui/tpleditor.gui.js', './tpleditor/gui/ace/ace.js', './js/bootstrap-tour.min.js', './tpleditor/gui/tour.gui.js');
    $ace = false;

    if (empty($_GET['option']) or $_GET['option'] == 'lite') {
        $lite_class = 'disabled';
        $pro_class = $option_str = null;
    } else {
        $lite_class = null;
        $pro_class = 'disabled';
        $option_str = '&option=' . $_GET['option'];
    }

    $PHPShopGUI->action_select['����� 1'] = array(
        'name' => '���������� �����',
        'url' => '?path=tpleditor&name=' . $_GET['name'] . '&option=lite',
        'class' => $lite_class
    );


    $PHPShopGUI->action_select['����� 2'] = array(
        'name' => '����������� �����',
        'url' => '?' . $_SERVER['QUERY_STRING'] . '&option=pro',
        'class' => $pro_class
    );

    $PHPShopGUI->action_select['�������'] = array(
        'name' => '������� HTML',
        'url' => 'http://www.wisdomweb.ru/HTML5/',
        'target' => '_blank'
    );

    $PHPShopGUI->action_select['����'] = array(
        'name' => '��������',
        'action' => 'presentation',
        'icon' => 'glyphicon glyphicon-education'
    );

    $PHPShopGUI->action_select['�������'] = array(
        'name' => '������� ��������',
        'url' => 'http://template.phpshopcms.ru',
        'target' => '_blank'
    );

    if (!empty($_GET['file'])) {

        $file = PHPShopSecurity::TotalClean('../templates/' . $_GET['name'] . '/' . $_GET['file']);
        $info = PHPShopSecurity::getExt($file);
        if (file_exists($file) and in_array($info, array('tpl', 'css'))) {
            $content = str_replace('textarea', 'area', @file_get_contents($file));
            $ace = true;

            $PHPShopGUI->action_button['������'] = array(
                'name' => '������',
                'class' => 'ace-full btn btn-default btn-sm navbar-btn',
                'type' => 'button',
                'icon' => 'glyphicon glyphicon-resize-small glyphicon-fullscreen'
            );

            $PHPShopGUI->action_button['���������'] = array(
                'name' => '���������',
                'action' => 'editID',
                'class' => 'ace-save btn btn-default btn-sm navbar-btn',
                'type' => 'button',
                'icon' => 'glyphicon glyphicon-floppy-saved'
            );
        } else {

            $content = null;
        }
    }


    switch ($_GET['mod']) {
        case 'html':
            $mod = 'rhtml';
            break;
        case 'css':
            $mod = 'css';
            break;
        default: $mod = 'rhtml';
    }

    if ($ace) {
        // ����
        $theme = $PHPShopSystem->getSerilizeParam('admoption.ace_theme');
        if (empty($theme))
            $theme = 'dawn';

        $wysiwyg = xml2array('./tpleditor/gui/wysiwyg.xml', "template", true);
        $var_list = $selectModalBody = null;
        if (is_array($wysiwyg))
            foreach ($wysiwyg as $template) {
                if ('/' . $template['path'] == $_GET['file']) {

                    // ���������
                    if (!empty($_GET['name']) and $_GET['option'] == 'pro')
                        $TitlePage.=': ' . $_GET['name'] . $_GET['file'];
                    else
                        $TitlePage.=': ' . $template['description'] . '';

                    if (is_array($template['var']))
                        if (empty($template['var'][1])) {
                            $var_list.='<button class="btn btn-xs btn-info editor_var" data-insert="@' . $template['var']['name'] . '@" type="button" data-toggle="tooltip" data-placement="top" title="' . $template['var']['description'] . '"><span class="glyphicon glyphicon-tag"></span> ' . $template['var']['name'] . '</button>';
                            $selectModal.='<tr><td>@' . $template['var']['name'] . '@</td><td>' . $template['var']['description'] . '</td></tr>';
                        } else {
                            foreach ($template['var'] as $var) {

                                // ����� ��������� � �����
                                if (preg_match("/@" . $var['name'] . "@/", $content)) {
                                    $class_btn = 'btn-default';
                                    $class_icon = 'glyphicon-tag';
                                } else {
                                    $class_btn = 'btn-info';
                                    $class_icon = 'glyphicon-plus';
                                }

                                $var_list.='<button class="btn btn-xs ' . $class_btn . ' editor_var" data-insert="@' . $var['name'] . '@" type="button" data-toggle="tooltip" data-placement="top" title="' . $var['description'] . '"><span class="glyphicon ' . $class_icon . '"></span> ' . $var['name'] . '</button>';

                                $selectModal.='<tr><td><kbd>@' . $var['name'] . '@</kbd></td><td>' . $var['description'] . '</td></tr>';
                            }
                        }
                }
            }

        if (!empty($var_list)) {
            $PHPShopGUI->_CODE = '<div class="panel panel-default" id="varlist">
            <div class="panel-body">' . $var_list . '<div class="text-right data-row"><a href="#" id="vartable" data-toggle="modal" data-target="#selectModal" data-title="' . $_GET['file'] . '"><span class="glyphicon glyphicon-question-sign"></span>�������� ����������</a></div></div></div>';

            // ��������� ���� ������� �������� ���������
            $selectModalBody = '<table class="table table-striped"><tr><th>����������</th><th>��������</th></tr>' . $selectModal . '</table>';
        }

        $PHPShopGUI->_CODE.= '<textarea class="hide hidden-edit" id="editor_src" name="editor_src" data-mod="' . $mod . '" data-theme="' . $theme . '">' . $content . '</textarea><pre id="editor">��������...</pre>';
    }
    else
        $PHPShopGUI->_CODE = '<p class="text-muted hidden-xs data-row">�������� ������������� ������ � ���� ��� �������������� � ����� ����.  
            ��������� ������� ��� ����������� �� ����� ������������ � �������� ��������� ����������, �������� <a href="?path=system#1"><span class="glyphicon glyphicon-share-alt"></span>��������� �������</a>. �������� ���� ��������� ���������� �������� � �������� ��������� ����������, �������� <a href="?path=system#4"><span class="glyphicon glyphicon-share-alt"></span>��������� ����������</a>.</p>';

    $PHPShopGUI->setActionPanel(PHPShopSecurity::TotalClean($TitlePage), array('����� 1', '����� 2', '�������', '�������','|', '����'), array('������', '�������', '���������'));

    $dir = "../templates/*";
    $k = 1;

    // ���� ����
    if (empty($_GET['option']) or $_GET['option'] == 'lite')
        $stop_array = array('css', 'icon', 'php', 'js', 'fonts', 'images', 'icon', 'modules', 'index.html', 'style.css', 'font', 'brands', 'breadcrumbs', 'calendar', 'clients', 'comment', 'error', 'forma', 'gbook', 'links', 'map', 'opros', 'order', 'paginator', 'price', 'print', 'search', 'slider', 'selection', 'users', 'pricemail', 'photo','editor');
    else
        $stop_array = array('css', 'icon', 'php', 'js', 'fonts', 'images', 'icon', 'modules', 'index.html', 'style.css', 'font');

    // ����� ������� ������ ��������
    $tree = '<table class="tree table table-hover">';

    if (empty($_GET['name'])) {


        $root = glob("../templates/*", GLOB_ONLYDIR);
        if (is_array($root)) {
            foreach ($root as $dir) {
                $path_parts = pathinfo($dir);
                $tree.='<tr class="treegrid-all"><td><a href="?path=' . $_GET['path'] . '&name=' . $path_parts['basename'] . $option_str . '">' . ucfirst($path_parts['basename']) . '</a></td></tr>';
            }
        }
        $title_icon = null;

        // �������������� �������
        $PHPShopGUI->_CODE.= $PHPShopGUI->loadLib('tab_base', $root);
    } else {


        // ����� ������� ������ ��������
        $tree.= '<tr class="treegrid-all">
           <td><a href="?path=' . $_GET['path'] . $option_str . '" class="btn btn-default btn-sm">��� �������</a> <span class="glyphicon glyphicon-triangle-right"></span> <span class="btn btn-info btn-sm" id="templatename">' . @ucfirst(PHPShopSecurity::TotalClean($_GET['name'], 4)) . '</span></td>
	</tr>';

        $dir = '../templates/' . $_GET['name'];
        $path_parts = pathinfo($dir);
        $root1 = glob($dir . "/*");
        if (is_array($root1)) {
            $parent1 = $k;
            foreach ($root1 as $dir1) {
                $path_parts1 = pathinfo($dir1);

                if (!in_array($path_parts1['basename'], $stop_array)) {

                    $k++;
                    $tree.='<tr class="treegrid-' . $k . '"><td><a href="#" class="treegrid-parent" data-parent="treegrid-' . $k . '">' . _tpl($path_parts1['basename']) . '</a></td></tr>';

                    $root2 = glob($dir1 . "/*.tpl");
                    if (is_array($root2)) {
                        $parent2 = $k;
                        foreach ($root2 as $dir2) {

                            $path_parts2 = pathinfo($dir2);
                            if (!in_array($path_parts2['basename'], $stop_array)) {
                                $k++;

                                $link = str_replace($dir, '', $dir2);
                                if ($link == $_GET['file']) {
                                    $active = ' treegrid-active';
                                    $active_icon = '<span class="glyphicon glyphicon-edit text-warning"></span>';
                                }
                                else
                                    $active = $active_icon = null;

                                $tree.='<tr class="treegrid-parent-' . $parent2 . $active . ' "><td class="data-row"><a href="?path=' . $_GET['path'] . '&name=' . $_GET['name'] . '&file=' . $link . '&mod=html' . $option_str . '" title="' . $path_parts2['basename'] . '">' . $active_icon . _tpl($path_parts2['basename']) . '</a></td></tr>';
                            }
                        }
                    }
                }
            }
        }

        if (!empty($parent2)) {
            $dir2 = str_replace($dir, '', $dir . '/style.css');
            $tree.='<tr class="treegrid-parent-' . $parent1 . ' data-row"><td><span class="glyphicon glyphicon-text-width"></span> <a href="?path=' . $_GET['path'] . '&name=' . $_GET['name'] . '&file=' . $dir2 . '&mod=css' . $option_str . '" title="style.css">' . _tpl('style.css') . '</a></td></tr>';
        }

        $title_icon = '<span class="glyphicon glyphicon-chevron-down" data-toggle="tooltip" data-placement="top" title="���������� ���"></span>&nbsp;<span class="glyphicon glyphicon-chevron-up" data-toggle="tooltip" data-placement="top" title="��������"></span>';
    }

    $tree.='</table>';
    
        $market='<p class="text-muted hidden-xs data-row">PHPShop.Market ���������� ����� 3000 ���������� � ����������� �������� ��� PHPShop. <a href="http://template.phpshopcms.ru/?from='.$_SERVER['SERVER_NAME'].'" target="_blank"><span class="glyphicon glyphicon-shopping-cart"></span>������� ������</a></p>';

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("submit", "editID", "���������", "right", 80, "", "but", "actionSave.system.edit");

    $PHPShopGUI->setFooter($ContentFooter);

    $sidebarleft[] = array('title' => __('������� � �������'), 'content' => $tree, 'title-icon' => $title_icon);
    
    /*
    if($PHPShopSystem->getSerilizeParam('admoption.templateshop_enabled') != 1)
    $sidebarleft[] = array('title' => __('������� ��������'), 'content' => $market);*/

    $PHPShopGUI->sidebarLeftCell = 3;
    $PHPShopGUI->setSidebarLeft($sidebarleft, 3);

    $PHPShopGUI->Compile(3);
}

// ������� ����������
function actionSave() {
    $file = PHPShopSecurity::TotalClean('../templates/' . $_GET['name'] . '/' . $_GET['file']);
    $info = PHPShopSecurity::getExt($file);
    if (file_exists($file) and in_array($info, array('tpl', 'css'))) {
        PHPShopFile::chmod($file);
        if (PHPShopFile::write($file, $content = str_replace(array('area', '&#43;'), array('textarea', '+'), $_POST['editor_src'])))
            $action = true;
        else
            $action = false;
    }
    else
        $action = false;

    return array("success" => $action);
}

// �������� �������������� ��������
function actionLoad() {
    global $skin_base_path, $_classPath;

    $success = false;
    if (PHPShopSecurity::true_skin($_POST['template_load'])) {

        // �������� 
        if (strlen($_POST['template_load']) < 20)
            $load = $skin_base_path . '/templates5/' . $_POST['template_load'] . '/' . $_POST['template_load'] . '.zip';
        else
            $load = null;

        // �������� ������
        $time = explode(' ', microtime());
        $start_time = $time[1] + $time[0];

        $Content = file_get_contents($load);
        if (!empty($Content)) {
            $zip = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . "/UserFiles/Files/" . $_POST['template_load'] . '.zip';
            $handle = fopen($zip, "w+");
            fwrite($handle, $Content);
            fclose($handle);
            if (is_file($zip)) {

                // ������� �������� ��������
                @chmod($_classPath . "templates", 0775);

                // ���������� ZIP
                include($_classPath . "lib/zip/pclzip.lib.php");
                $archive = new PclZip($zip);
                if ($archive->extract(PCLZIP_OPT_PATH, $_classPath . "templates/")) {

                    unlink($zip);

                    // ��������� ������
                    $time = explode(' ', microtime());
                    $seconds = ($time[1] + $time[0] - $start_time);
                    $seconds = substr($seconds, 0, 6);

                    $result = '������ <b>' . $_POST['template_load'] . '</b> �������� �� ' . $seconds . ' ���.';
                    $success = true;
                }
                else
                    $result = '������ ���������� ����� ' . $_POST['template_load'] . '.zip, ��� ���� ������ � ����� phpshop/templates/';
            }
            else
                $result = '������ ������ ����� ' . $_POST['template_load'] . '.zip, ��� ���� ������ � ����� /UserFiles/Files/';
        }
        else {
            $result = '������ ������ ����� ' . $_POST['template_load'] . '.zip';
        }
    }

    return array('success' => $success, 'result' => PHPShopSTring::win_utf8($result));
}

// ��������� �������
$PHPShopGUI->getAction();
?>