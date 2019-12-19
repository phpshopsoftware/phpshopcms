<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

class PHPShopFormgenerator extends PHPShopCore {

    /**
     * Конструктор
     */
    function __construct() {

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['formgenerator']['formgenerator_forms'];
        $this->debug = false;

        // Список экшенов
        $this->action = array('nav' => 'index', 'post' => 'forma_send');

        parent::__construct();
    }

    /**
     * Экшен по умолчанию
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
     * Экшен отправки по почте
     */
    function forma_send() {
        $content = null;
        $error = false;
        $i = 1;

        // Проверка каптчи
        if (!empty($_SESSION['mod_formgenerator_captcha'])) {
            if ($_SESSION['mod_formgenerator_captcha'] != $_POST['key'])
                $error = true;
        }

        if (PHPShopSecurity::true_num($_POST['forma_id'])) {

            $mail = PHPShopSecurity::TotalClean($_POST['forma_mail'], 3);

            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $data = $PHPShopOrm->select(array('*'), array('id' => "='" . $_POST['forma_id'] . "'", 'enabled' => "='1'"), false, array('limit' => 1));

            // Дополнительные поля formgenerator_
            foreach ($_POST as $k => $v)
                if (strstr($k, 'formgenerator')) {

                    // Проверка обязательных полей
                    if (strstr($k, '*') and empty($v)){
                        $error = true;
                    }

                    // Запоминаем поля для ошибки обязательного заполнения
                    $formamemory['formamemory' . $i] = $v;
                    $i++;

                    $content.='
' . str_replace('formgenerator_', '', $k) . ': ' . $v;
                }


            // Если все поля заполнены
            if (empty($error)) {

                PHPShopObj::loadClass("mail");
                $zag = $data['name'] . " - " . $this->PHPShopSystem->getValue("name");
                $content = 'Доброго времени,

' . $data['name'] . '
----------------
E-mail: ' . $mail . '
' . $content . '
Источник: ' . $_SERVER['HTTP_REFERER'] . '

---
';

                // Сообщение пользователю
                if (!empty($data['user_mail_copy']) and PHPShopSecurity::true_email($mail))
                    $PHPShopMail = new PHPShopMail($mail, $this->PHPShopSystem->getEmail(), $zag, $content);


                // Если пустая почта отправителя
                if (empty($mail))
                    $mail = $this->PHPShopSystem->getEmail();

                // Подгружаем класс отправки почты
                if (!class_exists('PHPShopMailFile'))
                    include_once($GLOBALS['SysValue']['class']['formgeneratormail']);

                // Сообщение администратору
                if (!empty($_FILES['forma_file']['tmp_name']))
                    $PHPShopMailFile = new PHPShopMailFile($data['mail'], $mail, $zag, $content, $_FILES['forma_file']['name'], $_FILES['forma_file']['tmp_name']);
                else
                    $PHPShopMail = new PHPShopMail($data['mail'], $mail, $zag, $content);

                // Мета
                $this->title = "Сообщение отправлено - " . $this->PHPShopSystem->getValue("name");

                // Определяем переменные
                $this->set('pageContent', $data['success_message']);
                $this->set('pageTitle', $data['name']);

                // Подключаем шаблон
                $this->parseTemplate($this->getValue('templates.page_page_list'));
            } else {

                if (is_array($formamemory))
                    foreach ($formamemory as $pole => $value)
                        $this->set($pole, $value);

                // Не заполнены обязательные поля
                $this->set('formamail', $mail);

                if (!empty($data['dir']))
                    header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=error_message");
                else
                    $this->forma($data['path'], $data['error_message']);
            }
        }
        else {
            $this->title = "Ошибка - " . $this->PHPShopSystem->getValue("name");
            $this->set('pageContent', 'Ошибка заполнения формы.');
            $this->set('pageTitle', 'Ошибка');

            // Подключаем шаблон
            $this->parseTemplate($this->getValue('templates.page_page_list'));
        }
    }

    /**
     *  Экшен форма
     */
    function forma($path, $error = false) {
        $i = 1;

        $path = PHPShopSecurity::TotalClean($path, 4);
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $data = $PHPShopOrm->select(array('*'), array('path' => "='" . $path . "'", 'enabled' => "='1'"), false, array('limit' => 1));

        if (is_array($data)) {

            // Очищаем память формы
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
            <input class="user" type="reset" value="Очистить">
            <input class="user" type="submit" name="forma_send" value="Отправить">
             </p>
</form>';

            // Определяем переменные




            $this->set('pageContent', $forma_content);
            $this->set('pageTitle', $data['name']);

            // Мета
            $this->title = $data['name'] . " - " . $this->PHPShopSystem->getValue("name");

            // Подключаем шаблон
            $this->parseTemplate($this->getValue('templates.page_page_list'));
        }
        else
            $this->setError404();
    }

}

?>