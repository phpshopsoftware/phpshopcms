<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.chat.chat_system"));

// ���������� ������ ������
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));
    
    return $action;
}

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    // ������������ ���������� / � ����� ����������
    /*
      if(substr($_POST['upload_dir_new'], -1) != '/')
      $_POST['upload_dir_new'].='/';

      // ������� ���������� ����� 775 �� ����� ��� ������
      @chmod($_SERVER['DOCUMENT_ROOT'] .$GLOBALS['SysValue']['dir']['dir'].'/UserFiles/Image/'.$_POST['upload_dir_new'],$_POST['chmod_new']); */

    $_SESSION['chat_skin'] = $_POST['skin_new'];

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

// ����� �������
function GetSkinList($skin) {
    global $PHPShopGUI;
    $dir = "../modules/chat/templates/skin/";

    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                $file = str_replace(array('.css', 'bootstrap-theme-'), '', $file);

                if ($skin == $file)
                    $sel = "selected";
                else
                    $sel = "";

                if ($file != "." and $file != ".." and !strpos($file, '.'))
                    $value[] = array($file, $file, $sel);
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('skin_new', $value, 200);
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // �������
    $data = $PHPShopOrm->select();

    // �����
    $e_value[] = array('�� ��������', 0, $data['enabled']);
    $e_value[] = array('�����', 1, $data['enabled']);
    $e_value[] = array('������', 2, $data['enabled']);

    // ��� ������
    $w_value[] = array('�����', 0, $data['windows']);
    $w_value[] = array('����������� ����', 1, $data['windows']);


    $Tab1 = $PHPShopGUI->setField('���������', $PHPShopGUI->setInputText(false, 'title_new', $data['title']));
    //$Tab1.=$PHPShopGUI->setField('CHMOD',$PHPShopGUI->setInputText(false, 'chmod_new', $chmod,100,'* 0775'),'left');
    $Tab1.=$PHPShopGUI->setLine() . $PHPShopGUI->setField('������������� ���������', $PHPShopGUI->setTextarea('title_start_new', $data['title_start']));
    $Tab1.=$PHPShopGUI->setField('C�������� ������������ ������', $PHPShopGUI->setTextarea('title_end_new', $data['title_end']));
    $Tab1.=$PHPShopGUI->setField('����� ������', $PHPShopGUI->setSelect('enabled_new', $e_value, 200));
    $Tab1.=$PHPShopGUI->setField('������', GetSkinList($data['skin']));
    //$Tab1.=$PHPShopGUI->setField('����� �������������',$PHPShopGUI->setInputText('/UserFiles/Image/','upload_dir_new', $upload_dir,100),'left');
    $Tab1.=$PHPShopGUI->setField('��� ������', $PHPShopGUI->setSelect('windows_new', $w_value, 200));

    $info = '��� ������������ ������� �������� ������� ������� �������� ������ "�� ��������" � � ������ ������ �������� ����������
        <kbd>@chat@</kbd> � ���� ������.
<p>
��� ������ �� ������� ������������� � ���� ������� ���������� ����� ������ <a class="btn btn-xs btn-default" href="http://www.phpshop.ru/loads/files/setup.exe" target="_blank"><span class="glyphicon glyphicon-save"></span> EasyControl</a> � ��������� ������� ��� ��������� 
"��� � ������������". ��� �������� � ����.
</p>
';

    $Tab2 = $PHPShopGUI->setInfo($info, 200, '96%');

    // ����� �����������
    $Tab3 = $PHPShopGUI->setPay(false, false, $data['version'], true);


    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1), array("����������", $Tab2), array("� ������", $Tab3));

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
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>