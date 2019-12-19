<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.filemanager.filemanager_system"));

// ����� �����
function GetFile($dir, $name) {
    global ${$name}, $root;

    if ($dh = @opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != ".." && $file != '.tmb' && $file != '.quarantine') {
                if (is_dir($dir . "/" . $file)) {
                    ${$name}[str_replace($root, '', $dir) . "/" . $file] = $file;
                    GetFile($dir . "/" . $file, $name);
                }
                else
                    ${$name}[str_replace($root, '', $dir) . "/" . $file] = $file;
            }
        }
        closedir($dh);
    }

    return null;
}

// �������� sitemap
function setGeneration() {
    global $BASE, $root;

    @set_time_limit(10000);

    $root = $_SERVER['DOCUMENT_ROOT'];
    $dir_global = $root . "/UserFiles/Files";
    GetFile($dir_global, "BASE");

    $lang = @parse_ini_file('../modules/filemanager/server/lang.ini', 1);

    $ini = null;
    if (is_array($BASE))
        foreach ($BASE as $path => $file) {

            if (!empty($lang[$path])) {
                $ini.='[' . $path . ']
name = "' . $lang[$path]['name'] . '";
size = "' . $lang[$path]['size'] . '";
';
            } else {
                $ini.='[' . $path . ']
name = "' . $file . '";
size = "' . round(@filesize($root . $path) / (1024 * 1024), 1) . '";
';
            }
        }

    // ������ � ����
    if (fwrite(fopen('../modules/filemanager/server/lang.ini', "w+"), $ini))
        echo '<div class="alert alert-success" id="rules-message"  role="alert">���� <strong>lang.ini</strong> ������� ������.</div>';
    else
        echo '<div class="alert alert-danger" id="rules-message"  role="alert">������ ���������� ����� � ����� /phpshop/modules/filemanager/server/lang.ini !</div>';
}

// ������� ����������
function actionUpdate() {

    setGeneration();
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $select_name;

    $PHPShopGUI->action_button['�������'] = array(
        'name' => '������� ����� ��������',
        'action' => 'saveID',
        'class' => 'btn  btn-default btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-import'
    );

    $PHPShopGUI->action_button['�������'] = array(
        'name' => '�����',
        'action' => '../modules/filemanager/server/?full=true',
        'class' => 'btn  btn-default btn-sm navbar-btn btn-action-panel-blank',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-hdd'
    );


    $PHPShopGUI->setActionPanel(__("��������� ������") . ' <span id="module-name">' . ucfirst($_GET['id']) . '</span>', $select_name, array('�������', '�������', '�������'));

    // �������
    $data = $PHPShopOrm->select();

    $Info = '<p>��� ������ ��������� ��������� ���������� �������� � ������ ������ ������ � ��������� �������� <code>class="openFilemanagerModal"</code> � ���������� <code>@filemanager@</code> ��� ������ ���������� ���� ��������� ���������. ������:
        <code>&lt;button class="openFilemanagerModal"&gt;�������� ��������&lt;/button&gt;</code>
</p>
        <p>��� ���������� ������� ������ � ���������:
<ol>
<li>������� ����� �������� ����� ���� ������</li>
<li>��������������� ���� �������� <code>/phpshop/modules/filemanager/server/lang.ini</code></li>
<ol>        
</p>';

    // ����� �����������
    $Tab3 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("����������", $PHPShopGUI->setInfo($Info)), array("� ������", $Tab3));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>