<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.unisender.unisender_system"));

function actionBase() {
    global $_classPath;

    @set_time_limit(10000);

    $apikey = $_POST['key_new'];

    // Обход пользователей
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['send_mail']);
    $data = $PHPShopOrm->select(array('*'), array('subscribe' => "='1'"), false, array('limit' => 100));
    if (is_array($data))
        foreach ($data as $user) {

            // Новые подписчики
            $key[] = $user['id'];
            $new_emails[] = $user['mail'];
            $new_names[] = $user['name'];
            $new_phone[] = $user['tel'];
        }


    if (!empty($new_emails)) {

        // Создаём POST-запрос
        $query_array = array(
            'api_key' => $apikey,
            'field_names[0]' => 'email',
            'field_names[1]' => 'Name',
            'field_names[2]' => 'phone',
            'field_names[21]' => 'email_list_ids',
            'platform' => 'phpshop',
            'format' => 'json'
        );
        for ($i = 0; $i < count($new_emails); $i++) {
            $query_array['data[' . $i . '][0]'] = $new_emails[$i];
            $query_array['data[' . $i . '][1]'] = iconv('cp1251', 'utf-8', $new_names[$i]);
            $query_array['data[' . $i . '][2]'] = $new_phone[$i];
        }

        // Устанавливаем соединение

        $fp = fsockopen("ssl://api.unisender.com", 443, $errno, $errstr, 30);
        $get_string = http_build_query($query_array);
        
        if (!$fp) {
            $api_uri = 'https://api.unisender.com/ru/api/importContacts';
            $result = file_get_contents($api_uri . '?' . $get_string);
        } else {

            $out = "GET /ru/api/importContacts?$get_string    HTTP/1.1\r\n";
            $out .= "Host: api.unisender.com\r\n";
            $out .= "Connection: Close\r\n\r\n";

            fwrite($fp, $out);
            $res = null;
            while (!feof($fp)) {
                $res.=fgets($fp, 128);
            }
            fclose($fp);

            $response = split("\r\n\r\n", $res);
            $header = $response[0];
            $responsecontent = $response[1];
            if (!(strpos($header, "Transfer-Encoding: chunked") === false)) {
                $aux = split("\r\n", $responsecontent);
                for ($i = 0; $i < count($aux); $i++)
                    if ($i == 0 || ($i % 2 == 0))
                        $aux[$i] = "";
                $responsecontent = implode("", $aux);
            }
            $result = chop($responsecontent);
        }

        if ($result) {
            // Раскодируем ответ API-сервера
            $jsonObj = json_decode($result);

            if (null === $jsonObj) {

                // Ошибка в полученном ответе
                echo '<div class="alert alert-danger" id="rules-message"  role="alert">Invalid JSON</div>';
            } elseif (!empty($jsonObj->error)) {

                // Ошибка импорта
                echo '<div class="alert alert-danger" id="rules-message"  role="alert">An error occured: ' . $jsonObj->error . '(code: ' . $jsonObj->code . ')</div>';
            } else {

                // Выключаем пользователей из синхронизации
                $PHPShopOrm->clean();
                $PHPShopOrm->debug = false;
                $id_list = implode(',', $key);
                if (!empty($id_list))
                    $PHPShopOrm->update(array('subscribe_new' => 2), array('id' => ' IN (' . $id_list . ')'));

                // Новые подписчики успешно добавлены
                echo '<div class="alert alert-success" id="rules-message"  role="alert">Выполнено. Добавлено ' . $jsonObj->result->new_emails . ' новых e-mail адресов</div>';
            }
        } else {
            // Ошибка соединения с API-сервером
            echo '<div class="alert alert-danger" id="rules-message"  role="alert">Ошибка API</div>';
        }
    }
    else
        echo '<div class="alert alert-info" id="rules-message"  role="alert">Нет новых контактов для экспорта</div>';
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules, $TitlePage, $select_name;

    // Новык пользователи
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['send_mail']);
    $data_user = $PHPShopOrm->select(array('*'), array('subscribe' => "='1'"), false, array('limit' => 1000));
    $num_new_user = count($data_user);
    if ($num_new_user > 0)
        $new_user = '<span class=badge>' . $num_new_user . '</span>';
    else
        $new_user = false;


    if ($new_user) {
        $PHPShopGUI->action_button['Синхронизация'] = array(
            'name' => 'Выгрузить пользователей ' . $new_user,
            'action' => 'loadBase',
            'class' => 'btn  btn-info btn-sm navbar-btn',
            'type' => 'submit',
            'icon' => 'glyphicon glyphicon-open'
        );
    }

    $PHPShopGUI->setActionPanel($TitlePage, $select_name, array('Синхронизация', 'Сохранить и закрыть'));

    // Выборка
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.unisender.unisender_system"));
    $data = $PHPShopOrm->select();

    $Tab1.=$PHPShopGUI->setField('Ключ доступа к API ', $PHPShopGUI->setInput('text.required', "key_new", $data['key'], false, 300));

    $Tab2 = $PHPShopGUI->setInfo('<p>Модуль позволяет автоматически выгружать данные покупателей из интернет-магазина в сервис увеличения повторных продаж через емейл рассылку <a href="http://unisender.com" target="_blank">UniSender.com</a>.</p>
<p>    
Ключ доступа к API для использования текущего модуля можно получить в настройках аккаунта Unisender в закладке <kbd>Интеграция и API</kbd>.<br>Опция <code>Текущий статус API</code> должна быть в режиме <kbd>Включен</kbd>.</p>');

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Инструкция", $Tab2), array("О Модуле", $Tab3,));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "loadBase", "Применить", "right", 80, "", "but", "actionBase.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>