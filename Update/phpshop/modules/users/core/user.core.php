<?php

class PHPShopUser extends PHPShopCore {

    /**
     * �����������
     */
    function __construct() {

        // ��� ��
        $this->objBase = $GLOBALS['SysValue']['base']['users']['users_base'];
        $this->debug = false;

        // ������ �������
        $this->action = array("nav" => array("register", "sendpassword"), 'post' => array("add_user", "update_user", "exit_user", "enter_user", "send_user"), 'get' => 'activation');

        parent::PHPShopCore();

        // ���������
        $this->option();

        // ��������� ������� ������
        $this->navigation(null, __('������������'));
    }

    /**
     * ���������
     */
    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['users']['users_system']);
        $this->option = $PHPShopOrm->select();
    }

    /**
     * ����� �� ���������, ������ �������
     */
    function index() {

        if (PHPShopSecurity::true_login($_SESSION['userName'])) {
            // ������� ������
            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $row = $PHPShopOrm->select(array('*'), array('login' => "='" . $_SESSION['userName'] . "'", 'enabled' => "='1'"));
            if (is_array($row)) {


                // ������ ������������
                $this->set('userName', $row['login']);
                $this->set('userMail', $row['mail']);
                $this->set('userDate', $row['date']);

                // �������������� ����
                $content = unserialize($row['content']);
                $dop = null;

                if (is_array($content))
                    foreach ($content as $k => $v) {
                        $this->set('userAddName', str_replace('dop_', '', $k));
                        $this->set('userAddValue', $v);
                        $dop.=PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['user_forma_dop_content'], true, false, true);
                    }

                // ���������� ����������
                $this->set('userContent', $dop);
                $this->set('pageContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['users_forma_enter'], true, false, true));
                $this->set('pageTitle', '������ ������� ������������');

                // ����
                $this->title = "������� ������������ - " . $this->PHPShopSystem->getValue("name");

                // ���������� ������
                $this->parseTemplate($this->getValue('templates.page_page_list'));
            }
        }
        else
            $this->enter();
    }

    /**
     * ����� ����� �����������
     */
    function enter($activation = false) {

        if (!empty($activation)) {

            // ��������� �� e-mail
            if ($this->option['mail_check'] == 1){
                $this->set('activationNotice', $GLOBALS['SysValue']['lang']['users_notice']);
                $this->set('user_check','show');
            }
            else{
                $this->set('activationNotice', $GLOBALS['SysValue']['lang']['users_notice_admin_check']);
                $this->set('user_check','show');
            }
        }

        // ���������� ����������
        $this->set('pageContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['users_forma'], true, false, true));
        $this->set('pageTitle', '�����������');

        // ����
        $this->title = "������� ������������ - ����������� - " . $this->PHPShopSystem->getValue("name");

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * ����� �������� ���������
     */
    function activation() {
        $activation = PHPShopSecurity::TotalClean($_GET['activation'], 4);
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->debug = $this->debug;
        $row = $PHPShopOrm->select(array('id,login,mail'), array('activation' => "='" . $activation . "'"), false, array('limit' => 1));
        if (!empty($row['login'])) {

            // ��������� ������������
            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->update(array('enabled_new' => '1', 'activation_new' => 'done'), array('id' => '=' . $row['id']));

            // ���������� � ��������
            $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name9'));
            $PHPShopOrm->insert(array('date_new' => date("d-m-y"), 'mail_new' => $row['mail']));

            $_SESSION['userName'] = $row['login'];
            $_SESSION['userId'] = $row['id'];
        }

        // ������� � ������ �������
        $this->index();
    }

    /**
     * ������ ����
     * @param Int $id �� ������������
     * @param string $user ��� ������������
     */
    function log($id, $user) {

        if (empty($_SESSION['userLog']) or $_SESSION['userLog'] < (time() - 60 * 15)) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['users']['users_log']);
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->insert(array('user_id_new' => $id, 'user_name_new' => $user, 'date_new' => date('U')));
        }

        $_SESSION["userLog"] = time();
    }

    /**
     * ����� �����
     */
    function enter_user() {

        if (PHPShopSecurity::true_login($_POST['login']) and PHPShopSecurity::true_passw($_POST['password'])) {

            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $PHPShopOrm->debug = $this->debug;
            $row = $PHPShopOrm->select(array('id,login,mail'), array('enabled' => "='1'", 'login' => "='" . $_POST['login'] . "'", 'password' => "='" . base64_encode($_POST['password']) . "'"), false, array('limit' => 1));

            if (!empty($row['id'])) {
                $_SESSION['userName'] = $row['login'];
                $_SESSION['userId'] = $row['id'];
                $_SESSION['userMail'] = $row['mail'];

                // ����� � ������
                $this->log($row['id'], $row['login']);
            }

            //$this->index();
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }

    /**
     * ����� ������
     */
    function exit_user() {
        unset($_SESSION['userName']);
        unset($_SESSION["userLog"]);

        // ������� ���
        if (PHPShopSecurity::true_num($_SESSION['userId'])) {

            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['users']['users_log']);
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->delete(array('user_id' => '=' . $_SESSION['userId']));
            unset($_SESSION['userId']);
        }

        $this->index();
    }

    /**
     * ���� �� ������������ � ����
     * @param string $login ��� ������������
     * @return bool
     */
    function chek($login) {
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->debug = $this->debug;
        $num = $PHPShopOrm->select(array('id'), array('login' => "='$login'"), false, array('limit' => 1));
        if (empty($num['id']))
            return true;
    }

    /**
     *  ����� ������ ������
     */
    function sendpassword() {

        // ���������� ����������
        $this->set('pageContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['users_forma_lost'], true, false, true));
        $this->set('pageTitle', '����������� ������');

        // ����
        $this->title = "������� ������������ - ����������� ������ - " . $this->PHPShopSystem->getValue("name");

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * ����� �������� ������
     */
    function send_user() {

        $mail = PHPShopSecurity::TotalClean($_POST['mail'], 3);
        $login = PHPShopSecurity::TotalClean($_POST['login'], 2);

        // ������� ������
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->Option['where'] = ' OR ';
        $row = $PHPShopOrm->select(array('*'), array('login' => "='" . $login . "'", 'mail' => "='" . $mail . "'"), false, array('limit' => 1));

        if (!empty($row['login'])) {

            PHPShopObj::loadClass("mail");
            $zag = "����������� ������ � " . $this->PHPShopSystem->getValue("name");
            $content = '������� �������, ' . $row['login'] . '
----------------

��� ������� � ����� http://' . $_SERVER['SERVER_NAME'] . '/user/ ����������� ������:
�����: ' . $row['login'] . '
������: ' . base64_decode($row['password']) . '

---
';

            // ��������� ������������
            $PHPShopMail = new PHPShopMail($row['mail'], $this->PHPShopSystem->getValue("admin_mail"), $zag, $content);
        }
    }

    /**
     *  ����� ����� �����������
     */
    function register() {

        // ������
        if ($this->option['captcha'] != 1) {
            $this->set('captchaCommentStart', '<!--');
            $this->set('captchaCommentEnd', '-->');
        }

        // ���������� ����������
        $this->set('pageContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['users_forma_register'], true, false, true));
        $this->set('pageTitle', '����������� ������������');

        // ����
        $this->title = "������� ������������ - ����������� - " . $this->PHPShopSystem->getValue("name");

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * ����� ����� ������ ������������
     */
    function update_user() {

        if (PHPShopSecurity::true_email($_POST['mail'])) {


            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $PHPShopOrm->debug = $this->debug;

            // �������������� ���� dop_
            foreach ($_POST as $v => $k)
                if (strstr($v, 'dop'))
                    $dop[$v] = $k;


            $update_var = array(
                'mail_new' => $_POST['mail'],
                'content_new' => serialize($dop)
            );

            if (!empty($_POST['password']))
                $update_var['password_new'] = base64_encode($_POST['password']);


            $PHPShopOrm->update($update_var, array('id' => '=' . $_SESSION['userId']));

            // ���������� ����������
            $this->set('userMessage', '<font color="green">������ ��������</font>');
        }
        else
            $this->set('userMessage', '<font color="red">������ ���������� �����!</font>');
        
        $this->set('user_check','show');

        // ���������� ����������
        $this->set('pageContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['users_forma_enter'], true, false, true));
        $this->set('pageTitle', '������ ������� ������������');

        

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * ����� ������ ������������
     */
    function add_user() {
        $mes = null;
        $dop = array();

        // �������� ������
        if ($this->option['captcha'] == 1 and strtolower($_POST['key']) != strtolower($_SESSION['text'])) {
            $mes = '������ ������������ ������';
        } elseif (PHPShopSecurity::true_login($_POST['login']) and PHPShopSecurity::true_passw($_POST['password']) and PHPShopSecurity::true_email($_POST['mail'])) {

            // �������� �� ������������ �����
            if ($this->chek($_POST['login'])) {
                $PHPShopOrm = new PHPShopOrm($this->objBase);
                $PHPShopOrm->debug = $this->debug;

                // �������� ���
                $check_text = md5(rand(0, 1000));

                // �������������� ���� dop_
                foreach ($_POST as $v => $k)
                    if (strstr($v, 'dop'))
                        $dop[$v] = $k;


                $PHPShopOrm->insert(array('date' => date("d-m-y"), 'mail' => $_POST['mail'], 'login' => $_POST['login'], 'password' => base64_encode($_POST['password']), 'activation' => $check_text, 'content' => serialize($dop)), $prefix = '');

                // ���������� ������ � ��������� ���������
                PHPShopObj::loadClass("mail");

                // ��������� � ����������
                if ($this->option['mail_check'] == 1)
                    $act_link = '��� ��������� ������������ ' . $_POST['login'] . ' ��������� �� ������: http://' . $_SERVER['SERVER_NAME'] . '/user/?activation=' . $check_text;
                else
                    $act_link = '��������� ������������ ���������� ��������������� �������, ��������� �������.';

                $zag = "����������� � " . $this->PHPShopSystem->getValue("name");
                $content = '������� �������
----------------

' . $act_link . '

��� ������� � ����� http://' . $_SERVER['SERVER_NAME'] . '/ ����� ��������� ����������� ������:
�����: ' . $_POST['login'] . '
������: ' . $_POST['password'] . '

---
';

                // ��������� ������������
                $PHPShopMail = new PHPShopMail($_POST['mail'], $this->PHPShopSystem->getValue("admin_mail"), $zag, $content);

                // ��������� ��������������
                if ($this->option['mail_check'] == 2) {
                    $content = '������� �������
----------------

��������� ������ ��������� ������������ ' . $_POST['login'] . '
��� ��������� ��������� �� ������: http://' . $_SERVER['SERVER_NAME'] . '/phpshop/admpanel/

---
';
                    $PHPShopMail = new PHPShopMail($this->PHPShopSystem->getValue("admin_mail"), $_POST['mail'], $zag, $content);
                }


                // ������� � ������ �������
                $this->enter(true);
            }
            else
                $mes = '������������ � ����� ������ ��� ���������������';
        }
        else
            $mes = '������ ���������� ����� �����������';

        // ��� �������
        if (!empty($mes)) {

            $this->set('usersError', $mes);
            $this->set('user_check','show');


            // ����
            $this->title = "������� ������������ - ����������� - " . $this->PHPShopSystem->getValue("name");

            // ���������� ����������
            $this->set('pageContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['users_forma_register'], true, false, true));
            $this->set('pageTitle', '����������� ������������');

            // ���������� ������
            $this->parseTemplate($this->getValue('templates.page_page_list'));
        }
    }

}

?>