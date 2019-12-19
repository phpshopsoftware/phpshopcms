<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.nextpay.nextpay_system"));

// Обновление версии модуля
function actionBaseUpdate()
{
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));
    return $action;
}

// Функция обновления
function actionUpdate()
{
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id=nextpay');

    return $action;
}

function actionStart()
{
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->addJSFiles("../../phpshop/modules/nextpay/admpanel/ajax/ajax.js");

    // Выборка
    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField('ID Продукта для генерации ссылки', $PHPShopGUI->setInputText(false, 'merchant_key2_new',
        $data['merchant_key2'], 250));
    $Tab1 .= $PHPShopGUI->setField('Секретный ключ', $PHPShopGUI->setInputText(false, 'merchant_skey_new',
        $data['merchant_skey'], 250));

    $Tab1 .= $PHPShopGUI->setField('Заголовок оповещения об оплате ссылкой', $PHPShopGUI->setTextarea('link_top_text_new', $data['link_top_text']));
    $Tab1 .= $PHPShopGUI->setField('Оповещение об оплате ссылкой', $PHPShopGUI->setTextarea('link_text_new', $data['link_text']));

    // Вывод формы генерации ссылки на оплату
    $Tab2 = $PHPShopGUI->setField('Номер заказа', $PHPShopGUI->setInputText(false, 'f_oder', '', 250));
    $Tab2 .= $PHPShopGUI->setField('Сумма к оплате', $PHPShopGUI->setInputText(false, 'f_sum', '', 250));
    $Tab2 .= $PHPShopGUI->setField('E-mail клиента', $PHPShopGUI->setInputText(false, 'f_email', '', 250));
    $Tab2 .= '<div style="margin: 10px; color: #EEA236;" id="text_link"></div>';
    $Tab2 .= $PHPShopGUI->setInput(
        'button',
        'button_link',
        'Сгенерировать ссылку на экран',
        $float = null,
        $size = false,
        $onclick = 'ajax_link()',
        $class = "btn  btn-default btn-sm navbar-btn",
        $action = false,
        $caption = false,
        $description = false,
        $title = false
    );
    $Tab2 .= "&nbsp;&nbsp;&nbsp;";
    $Tab2 .= $PHPShopGUI->setInput(
        'button',
        'button_link',
        'Отправить на e-mail',
        $float = null,
        $size = false,
        $onclick = 'ajax_link_email()',
        $class = "btn  btn-default btn-sm navbar-btn",
        $action = false,
        $caption = false,
        $description = false,
        $title = false
    );

    $info = '
<p>
Возможна работа с юридическими лицами, с заключением договора или без договора.
При работе без заключения договора в соответствии с 54-ФЗ для магазина отсутствует необходимость установки кассы. Подробнее о данном решении в статье <a href="https://www.nextpay.ru/faq54.php?p=phpshop" target="_blank">Решение для соответствия 54-ФЗ</a>. </p>

<h4>Настройка модуля</h4>
       <ol>
       <li>Зарегистрироваться в <a href="http://nextpay.ru/?p=phpshop" target="_blank">NextPay</a>. Для работы без применения кассового оборудования при подаче заявки на регистрацию сайта выберите в поле "Правовая форма" опцию "Юридическое лицо/ИП (без заключения договора)" и заполните реквизиты вашей организации.
        <li>Для генерации и отправки ссылки на оплату на эл. почту покупателя необходимо создать продукт в кабинете продавца в системе nextpay.ru в разделе <kbd>Каталоги</kbd> - <kbd>Создать продукт</kbd></li>
<li>В настройках продукта в поле "URL успеха" указать <code>http://' . $_SERVER['SERVER_NAME'] . '/nextpaysuccess/</code></li>
<li>ID продукта указать в настройках модуля в поле ID Продукта для генерации ссылки</li>
        </ol>
        
';

    $Tab3 = $PHPShopGUI->setInfo($info);

    // Форма регистрации
    $Tab4 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Генерация ссылки на оплату", $Tab2, true), array("Инструкция", $Tab3), array("О Модуле", $Tab4));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
        $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
        $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>