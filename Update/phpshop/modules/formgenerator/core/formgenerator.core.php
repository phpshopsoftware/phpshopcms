<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

class PHPShopFormgenerator extends PHPShopCore {

    /**
     * �����������
     */
    function __construct() {

        // ��� ��
        $this->objBase = $GLOBALS['SysValue']['base']['formgenerator']['formgenerator_forms'];
        $this->debug = false;

        // ������ �������
        $this->action = array('nav' => 'index', 'post' => 'forma_send');

        parent::__construct();
    }

    /**
     * ����� �� ���������
     */
    function index() {

        $forma_path = $GLOBALS['SysValue']['nav']['nav'];

        if (!empty($forma_path))
            $this->forma($forma_path);
        else {
            $data = $this->PHPShopOrm->select(array('path'), false, false, array('limit' => 1));
            $this->forma($data['path']);
        }
    }

    function fixtags($str) {
        return str_replace(array('<//'), array('</'), $str);
    }

    /**
     * ����� �������� �� �����
     */
    function forma_send() {
        $content = null;
        $error = false;
        $i = 1;

        // �������� ������
        if (!empty($_SESSION['mod_formgenerator_captcha'])) {
            if ($_SESSION['mod_formgenerator_captcha'] != $_POST['key'])
                $error = true;
        }

        if (PHPShopSecurity::true_num($_POST['forma_id'])) {

            $mail = PHPShopSecurity::TotalClean($_POST['forma_mail'], 3);

            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $data = $PHPShopOrm->select(array('*'), array('id' => "='" . $_POST['forma_id'] . "'", 'enabled' => "='1'"), false, array('limit' => 1));

            // �������������� ���� formgenerator_
            foreach ($_POST as $k => $v)
                if (strstr($k, 'formgenerator')) {

                    // �������� ������������ �����
                    if (strstr($k, '*') and empty($v)){
                        $error = true;
                    }

                    // ���������� ���� ��� ������ ������������� ����������
                    $formamemory['formamemory' . $i] = $v;
                    $i++;

                    $content.='
' . str_replace('formgenerator_', '', $k) . ': ' . $v;
                }


            // ���� ��� ���� ���������
            if (empty($error)) {

                PHPShopObj::loadClass("mail");
                $zag = $data['name'] . " - " . $this->PHPShopSystem->getValue("name");
                $content = '������� �������,

' . $data['name'] . '
----------------
E-mail: ' . $mail . '
' . $content . '
��������: ' . $_SERVER['HTTP_REFERER'] . '

---
';

                // ��������� ������������
                if (!empty($data['user_mail_copy']) and PHPShopSecurity::true_email($mail))
                    $PHPShopMail = new PHPShopMail($mail, $this->PHPShopSystem->getValue("adminmail2"), $zag, $content);


                // ���� ������ ����� �����������
                if (empty($mail))
                    $mail = $this->PHPShopSystem->getValue("adminmail2");

                // ���������� ����� �������� �����
                if (!class_exists('PHPShopMailFile'))
                    include_once($GLOBALS['SysValue']['class']['formgeneratormail']);

                // ��������� ��������������
                if (!empty($_FILES['forma_file']['tmp_name']))
                    $PHPShopMailFile = new PHPShopMailFile($data['mail'], $mail, $zag, $content, $_FILES['forma_file']['name'], $_FILES['forma_file']['tmp_name']);
                else
                    $PHPShopMail = new PHPShopMail($data['mail'], $mail, $zag, $content);

                // ����
                $this->title = "��������� ���������� - " . $this->PHPShopSystem->getValue("name");

                // ���������� ����������
                $this->set('pageContent', $data['success_message']);
                $this->set('pageTitle', $data['name']);

                // ���������� ������
                $this->parseTemplate($this->getValue('templates.page_page_list'));
            } else {

                if (is_array($formamemory))
                    foreach ($formamemory as $pole => $value)
                        $this->set($pole, $value);

                // �� ��������� ������������ ����
                $this->set('formamail', $mail);

                if (!empty($data['dir']))
                    header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=error_message");
                else
                    $this->forma($data['path'], $data['error_message']);
            }
        }
        else {
            $this->title = "������ - " . $this->PHPShopSystem->getValue("name");
            $this->set('pageContent', '������ ���������� �����.');
            $this->set('pageTitle', '������');

            // ���������� ������
            $this->parseTemplate($this->getValue('templates.page_page_list'));
        }
    }

    /**
     *  ����� �����
     */
    function forma($path, $error = false) {
        $i = 1;

        $path = PHPShopSecurity::TotalClean($path, 4);
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $data = $PHPShopOrm->select(array('*'), array('path' => "='" . $path . "'", 'enabled' => "='1'"), false, array('limit' => 1));

        if (is_array($data)) {

            // ������� ������ �����
            if (empty($error))
                while ($i < 10) {
                    $this->set('formamemory' . $i, '');
                    $i++;
                }


            if (!empty($_GET['error']))
                $error = $data['error_message'];

            $forma_content = '
                <p><b>' . $error . '</b></p>
<form method="post" enctype="multipart/form-data" name="formgenerator" id="formgenerator" action="/formgenerator/' . $path . '/">
            ' . Parser($this->fixtags($data['content'])) . '
                <p id="formgenerator_buttons">
            <input type="hidden" name="forma_id" value="' . $data['id'] . '">
            <input class="user" type="reset" value="��������">
            <input class="user" type="submit" name="forma_send" value="���������">
             </p>
</form>';

            // ���������� ����������




            $this->set('pageContent', $forma_content);
            $this->set('pageTitle', $data['name']);

            // ����
            $this->title = $data['name'] . " - " . $this->PHPShopSystem->getValue("name");

            // ���������� ������
            $this->parseTemplate($this->getValue('templates.page_page_list'));
        }
        else
            $this->setError404();
    }

}

?>