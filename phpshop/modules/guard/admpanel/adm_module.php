<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.guard.guard_system"));

function setSelectValue($n) {
    $i = 1;
    while ($i <= 10) {
        if ($n == $i)
            $s = "selected";
        else
            $s = "";
        $select[] = array($i, $i, $s);
        $i++;
    }
    return $select;
}

function actionStart() {
    global $PHPShopGUI,  $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField('Автоматическая проверка', $PHPShopGUI->setCheckbox("enabled_new", 1, "Включить", $data['enabled']));
    $Tab1.=$PHPShopGUI->setField('Количество проверок в день', $PHPShopGUI->setSelect('chek_day_num_new', setSelectValue($data['chek_day_num']), 50));
    $Tab1.=$PHPShopGUI->setField('Режим', $PHPShopGUI->setCheckbox("mode_new", 1, "Расширенный режим проверки файлов", $data['mode']).$PHPShopGUI->setHelp('Требует выделения большого количества памяти. Рекомендуется запускать только на VPS-хостингах.'));
    $Tab1.=$PHPShopGUI->setField('Уведомления', $PHPShopGUI->setCheckbox("stop_new", 1, "Блокировка сайта при обнаружении вируса", $data['stop']) .
            $PHPShopGUI->setCheckbox("mail_enabled_new", 1, "Уведомление администратора по E-mail", $data['mail_enabled']));
    $Tab1.=$PHPShopGUI->setField('E-mail для отчетов', $PHPShopGUI->setInputText(null, 'mail_new', $data['mail'], 300));


    // Проверка дат событий
    if ((date('U') - $data['last_chek']) < (86400 * 3))
        $flag_chek = '<p class="text-success">'.PHPShopDate::dataV($data['last_chek']).'</p>';
    else
        $flag_chek = '<p class="text-danger">Не выполнено</p>';

    if ((date('U') - $data['last_update']) < (86400 * 5))
        $flag_update = '<p class="text-success">'.PHPShopDate::dataV($data['last_update']).'</p>';
    else
        $flag_update = '<p class="text-danger">Не выполнено</p>';

    if ((date('U') - $data['last_crc']) < (86400 * 3))
        $flag_crc = '<p class="text-success">'.PHPShopDate::dataV($data['last_crc']).'</p>';
    else
        $flag_crc = '<p class="text-danger">Не выполнено</p>';

    $Tab1.=$PHPShopGUI->setField('Проверка файлов<br>' . $flag_chek , '<a class="btn btn-sm btn-success" style="width:200px" href="../modules/guard/admin.php?do=chek" target="_blank"><span class="glyphicon glyphicon-ok"></span> Проверить файлы</a>');

    $Tab1.=$PHPShopGUI->setField('Обновление сигнатур<br>' . $flag_update , '<a class="btn btn-sm btn-success" style="width:200px" href="../modules/guard/admin.php?do=update" target="_blank"><span class="glyphicon glyphicon-refresh"></span> Обновить сигнатуры</a>');

    $Tab1.=$PHPShopGUI->setField('Файловая база<br>' . $flag_crc, '<a class="btn btn-sm btn-success" style="width:200px" href="../modules/guard/admin.php?do=create" target="_blank"><span class="glyphicon glyphicon-signal"></span> Пересчитать базу</a>');

    // Инструкция
    $Info = ' <h4>Режимы</h4>
Флаг "Автоматическая проверка файлов" включает автоматическую проверку файлов по заданному промежутку, редактирующийся в опции "<b>Количество проверок в день</b>".
    Флаг "<b>Расширенный режим проверки файлов</b>" активирует возможность проверки всех файлов, включая шаблоны и библиотеки. Если эта опция не активная, то работает упрощенный режим проверки, который проверяет только самые "популярные" файлы у вирусов. Расширенный режим требует больше ресурсов и может замедлять работу сайта. 
<h4>Уведомления</h4>
    Флаг "<b>Блокировка сайта при обнаружении вируса</b>" будет выводит заглушку вместо сайта, чтобы остальные пользователи не заразились вирусом через ваш сайт и поисковики не понизили его в рейтинге.
    Флаг "<b>Уведомление администратора по E-mail</b>" отправляет отчеты о проверки, включенной флагом "Автоматическая проверка файлов". 
<h4>Действия</h4>
    Проверка файлов - проверяет файлы на изменения, в измененных файлах проверяются сигнатуры вирусов
    Обновление сигнатур - соединение и обновление сигнатур с сервера разработчика.
    Пересчет файловой базы - обход файлов и создание новой БД контрольных сумм файлов 
<h4>Лечение</h4>
При заражении вирусом можно восстановить рабочие бекапы файлов. Все бекапы хранятся в UserFiles/Files/ и имеют вид дата-случайное_число.zip Нужно или распаковать файлы на сервере (определяется сервером) или скачать самый свежий архив через ftp себе на компьютер, распаковать и загрузить содержимое в корневую папку сервера / 

<h4>Полезные советы</h4>
<ol>
<li>Если вы производите обновление ПО, то антивирус при включенном флаге "автоматическая проверка файлов" сработает на несоответствие контрольных сумм файлов и сообщит вам об этом. В этом случаи необходимо выполнить действие по обновлению сигнатур.<br><br>
<li>Если вы получили уведомление о заражении вирусом, то в письме администратору (необходимо, чтобы флаг "Уведомление администратора по E-mail" был включен) будет предложен файл архивной копии (backup) с чистыми проверенными файлами. Архив нужно восстановить в ручном режиме и заменить файлы на фтп через любой фтп-менеджер. </ol>
';
    $Tab2 = $PHPShopGUI->setInfo($Info);

    // Содержание закладки 2
    $Tab3 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1,true), array("Инструкция", $Tab2), array("О Модуле", $Tab3));


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", 1) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    $PHPShopOrm->debug = true;
    if (empty($_POST['mode_new']))
        $_POST['mode_new'] = 0;
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    if (empty($_POST['stop_new']))
        $_POST['stop_new'] = 0;
    if (empty($_POST['mail_enabled_new']))
        $_POST['mail_enabled_new'] = 0;

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    header('Location: ?path=modules&install=check');
    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>