<?php

class PHPShopUser extends PHPShopCore {

    /**
     * Конструктор
     */
    function __construct() {

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['users']['users_base'];
        $this->debug = false;

        // Список экшенов
        $this->action = array("nav" => array("register", "sendpassword"), 'post' => array("add_user", "update_user", "exit_user", "enter_user", "send_user"), 'get' => 'activation');

        parent::PHPShopCore();

        // Настройки
        $this->option();

        // Навигация хлебные крошки
        $this->navigation(null, __('Пользователи'));
    }

    /**
     * Настройки
     */
    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['users']['users_system']);
        $this->option = $PHPShopOrm->select();
    }

    /**
     * Экшен по умолчанию, личный кабинет
     */
    function index() {

        if (PHPShopSecurity::true_login($_SESSION['userName'])) {
            // Выборка данных
            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $row = $PHPShopOrm->select(array('*'), array('login' => "='" . $_SESSION['userName'] . "'", 'enabled' => "='1'"));
            if (is_array($row)) {


                // Данные пользователя
                $this->set('userName', $row['login']);
                $this->set('userMail', $row['mail']);
                $this->set('userDate', $row['date']);

                // Дополнительные поля
                $content = unserialize($row['content']);
                $dop = null;

                if (is_array($content))
                    foreach ($content as $k => $v) {
                        $this->set('userAddName', str_replace('dop_', '', $k));
                        $this->set('userAddValue', $v);
                        $dop.=PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['user_forma_dop_content'], true, false, true);
                    }

                // Определяем переменные
                $this->set('userContent', $dop);
                $this->set('pageContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['users_forma_enter'], true, false, true));
                $this->set('pageTitle', 'Личный кабинет пользователя');

                // Мета
                $this->title = "Кабинет пользователя - " . $this->PHPShopSystem->getValue("name");

                // Подключаем шаблон
                $this->parseTemplate($this->getValue('templates.page_page_list'));
            }
        }
        else
            $this->enter();
    }

    /**
     * Экшен форма авторизации
     */
    function enter($activation = false) {

        if (!empty($activation)) {

            // Активация по e-mail
            if ($this->option['mail_check'] == 1){
                $this->set('activationNotice', $GLOBALS['SysValue']['lang']['users_notice']);
                $this->set('user_check','show');
            }
            else{
                $this->set('activationNotice', $GLOBALS['SysValue']['lang']['users_notice_admin_check']);
                $this->set('user_check','show');
            }
        }

        // Определяем переменные
        $this->set('pageContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['users_forma'], true, false, true));
        $this->set('pageTitle', 'Авторизация');

        // Мета
        $this->title = "Кабинет пользователя - Авторизация - " . $this->PHPShopSystem->getValue("name");

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Экшен проверки активации
     */
    function activation() {
        $activation = PHPShopSecurity::TotalClean($_GET['activation'], 4);
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->debug = $this->debug;
        $row = $PHPShopOrm->select(array('id,login,mail'), array('activation' => "='" . $activation . "'"), false, array('limit' => 1));
        if (!empty($row['login'])) {

            // Включение пользователя
            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->update(array('enabled_new' => '1', 'activation_new' => 'done'), array('id' => '=' . $row['id']));

            // Добавление в рассылку
            $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name9'));
            $PHPShopOrm->insert(array('date_new' => date("d-m-y"), 'mail_new' => $row['mail']));

            $_SESSION['userName'] = $row['login'];
            $_SESSION['userId'] = $row['id'];
        }

        // Переход в личный кабинет
        $this->index();
    }

    /**
     * Запись лога
     * @param Int $id ИД пользователя
     * @param string $user Имя пользователя
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
     * Экшен входа
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

                // Пишем в журнал
                $this->log($row['id'], $row['login']);
            }

            //$this->index();
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }

    /**
     * Экшен выхода
     */
    function exit_user() {
        unset($_SESSION['userName']);
        unset($_SESSION["userLog"]);

        // Удаляем лог
        if (PHPShopSecurity::true_num($_SESSION['userId'])) {

            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['users']['users_log']);
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->delete(array('user_id' => '=' . $_SESSION['userId']));
            unset($_SESSION['userId']);
        }

        $this->index();
    }

    /**
     * Есть ли пользователь в базе
     * @param string $login имя пользователя
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
     *  Экшен потеря пароля
     */
    function sendpassword() {

        // Определяем переменные
        $this->set('pageContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['users_forma_lost'], true, false, true));
        $this->set('pageTitle', 'Напоминание пароля');

        // Мета
        $this->title = "Кабинет пользователя - Напоминание пароля - " . $this->PHPShopSystem->getValue("name");

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Экшен отправка пароля
     */
    function send_user() {

        $mail = PHPShopSecurity::TotalClean($_POST['mail'], 3);
        $login = PHPShopSecurity::TotalClean($_POST['login'], 2);

        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->Option['where'] = ' OR ';
        $row = $PHPShopOrm->select(array('*'), array('login' => "='" . $login . "'", 'mail' => "='" . $mail . "'"), false, array('limit' => 1));

        if (!empty($row['login'])) {

            PHPShopObj::loadClass("mail");
            $zag = "Напоминание пароля в " . $this->PHPShopSystem->getValue("name");
            $content = 'Доброго времени, ' . $row['login'] . '
----------------

Для доступа к сайту http://' . $_SERVER['SERVER_NAME'] . '/user/ используйте данные:
Логин: ' . $row['login'] . '
Пароль: ' . base64_decode($row['password']) . '

---
';

            // Сообщение пользователю
            $PHPShopMail = new PHPShopMail($row['mail'], $this->PHPShopSystem->getValue("admin_mail"), $zag, $content);
        }
    }

    /**
     *  Экшен форма регистрации
     */
    function register() {

        // Каптча
        if ($this->option['captcha'] != 1) {
            $this->set('captchaCommentStart', '<!--');
            $this->set('captchaCommentEnd', '-->');
        }

        // Определяем переменные
        $this->set('pageContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['users_forma_register'], true, false, true));
        $this->set('pageTitle', 'Регистрация пользователя');

        // Мета
        $this->title = "Кабинет пользователя - Регистрация - " . $this->PHPShopSystem->getValue("name");

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Экшен смены данных пользователя
     */
    function update_user() {

        if (PHPShopSecurity::true_email($_POST['mail'])) {


            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $PHPShopOrm->debug = $this->debug;

            // Дополнительные поля dop_
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

            // Определяем переменные
            $this->set('userMessage', '<font color="green">Данные имзенены</font>');
        }
        else
            $this->set('userMessage', '<font color="red">Ошибка заполнения формы!</font>');
        
        $this->set('user_check','show');

        // Определяем переменные
        $this->set('pageContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['users_forma_enter'], true, false, true));
        $this->set('pageTitle', 'Личный кабинет пользователя');

        

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Экшен записи пользователя
     */
    function add_user() {
        $mes = null;
        $dop = array();

        // Проверка каптчи
        if ($this->option['captcha'] == 1 and strtolower($_POST['key']) != strtolower($_SESSION['text'])) {
            $mes = 'Ошибка проверочного текста';
        } elseif (PHPShopSecurity::true_login($_POST['login']) and PHPShopSecurity::true_passw($_POST['password']) and PHPShopSecurity::true_email($_POST['mail'])) {

            // проверка на уникальность имени
            if ($this->chek($_POST['login'])) {
                $PHPShopOrm = new PHPShopOrm($this->objBase);
                $PHPShopOrm->debug = $this->debug;

                // Защитный код
                $check_text = md5(rand(0, 1000));

                // Дополнительные поля dop_
                foreach ($_POST as $v => $k)
                    if (strstr($v, 'dop'))
                        $dop[$v] = $k;


                $PHPShopOrm->insert(array('date' => date("d-m-y"), 'mail' => $_POST['mail'], 'login' => $_POST['login'], 'password' => base64_encode($_POST['password']), 'activation' => $check_text, 'content' => serialize($dop)), $prefix = '');

                // Биьлиотека работы с почтовыми функциями
                PHPShopObj::loadClass("mail");

                // Сообщение с активацией
                if ($this->option['mail_check'] == 1)
                    $act_link = 'Для активации пользователя ' . $_POST['login'] . ' перейдите по ссылке: http://' . $_SERVER['SERVER_NAME'] . '/user/?activation=' . $check_text;
                else
                    $act_link = 'Активации пользователя проводится администратором ресурса, подождите немного.';

                $zag = "Регистрация в " . $this->PHPShopSystem->getValue("name");
                $content = 'Доброго времени
----------------

' . $act_link . '

Для доступа к сайту http://' . $_SERVER['SERVER_NAME'] . '/ после активации используйте данные:
Логин: ' . $_POST['login'] . '
Пароль: ' . $_POST['password'] . '

---
';

                // Сообщение пользователю
                $PHPShopMail = new PHPShopMail($_POST['mail'], $this->PHPShopSystem->getValue("admin_mail"), $zag, $content);

                // Сообщение администратору
                if ($this->option['mail_check'] == 2) {
                    $content = 'Доброго времени
----------------

Требуется ручная активация пользователя ' . $_POST['login'] . '
Для активации перейдите по ссылке: http://' . $_SERVER['SERVER_NAME'] . '/phpshop/admpanel/

---
';
                    $PHPShopMail = new PHPShopMail($this->PHPShopSystem->getValue("admin_mail"), $_POST['mail'], $zag, $content);
                }


                // Переход в личный кабинет
                $this->enter(true);
            }
            else
                $mes = 'Пользователь с таким именем уже зарегистрирован';
        }
        else
            $mes = 'Ошибка заполнения формы регистрации';

        // Еще попытка
        if (!empty($mes)) {

            $this->set('usersError', $mes);
            $this->set('user_check','show');


            // Мета
            $this->title = "Кабинет пользователя - Регистрация - " . $this->PHPShopSystem->getValue("name");

            // Определяем переменные
            $this->set('pageContent', PHPShopParser::file($GLOBALS['SysValue']['templates']['users']['users_forma_register'], true, false, true));
            $this->set('pageTitle', 'Регистрация пользователя');

            // Подключаем шаблон
            $this->parseTemplate($this->getValue('templates.page_page_list'));
        }
    }

}

?>