<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cleversite.cleversite_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->title = "Настройка модуля Cleversite";

    // Выборка
    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField('Логин',$PHPShopGUI->setInputText(false, 'client_new', $data['client'], '300'));
    $Tab1.= $PHPShopGUI->setField('Пароль',$PHPShopGUI->setInput('password', 'password_new', $data['password'], "", '300'));

    $Tab1.=$PHPShopGUI->setField('URL сайта:', $PHPShopGUI->setInputText('http://', 'site_new', $data['site'], '300'),1, 'Данный адрес должен совпадать с введенным Вами в личном кабинете адресом сайта, на котором требуется отобразить модуль');

    $Info = '<h4>Для вставки данного модуля следуйте инструкции:</h4>
        <ol>
        <li> Зарегистрируйтесь на сайте <a href="https://cleversite.ru/?ref=qD3jt" target="_blank"> cleversite.ru</a>
		<li> Получите на почту письмо с регистрационными данными.
		<li> Выберете в личном кабинете какие виджеты Вы хотите отобразить на своем сайте.
        <li> Скопируйте Ваш Логин и вставьте его в поле "Логин" на вкладке "Основное" текущего окна настройки модуля.
		<li> Скопируйте Ваш Пароль и вставьте его в поле "Пароль" на вкладке "Основное" текущего окна настройки модуля.
		<li> Укажите адрес сайта, который вы добавили в настройки личного кабинета на сайте <a href="https://cleversite.ru/?ref=qD3jt" target="_blank">cleversite.ru</a> 
		и вставьте его в поле "Сайт" на вкладке "Основное" текущего окна настройки модуля.
		<li> Сохраните введенные Вами данные.
		</ol>';
    $Tab2 = $PHPShopGUI->setInfo($Info, '200px', '100%');

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay();

    $About = 'Если у Вас возникли вопросы, то можите пишисать оператору на <a href="http://cleversite.ru/" target="_blank">нашем сайту</a> в онлайн-консультант или отправить сообщение на <a href="mailto:help@cleversite.ru">help@cleversite.ru</a>, принимаем Ваши обращения 24 часа в сутки. Мы поможем установить код на Ваш сайт и начать работу в системе.';
    $Tab3.=$PHPShopGUI->setInfo($About);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $PHPShopGUI->setCollapse(__('Авторизация'),$Tab1)), array("Инструкция", $Tab2), array("О Модуле", $Tab3));

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
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>