<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.users.users_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;
    if(empty($_POST['captcha_new'])) $_POST['captcha_new']=0;
    if(empty($_POST['stat_flag_new'])) $_POST['stat_flag_new']=0;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    $Select[]=array("Слева",0,$data['flag']);
    $Select[]=array("Справа",1,$data['flag']);

    // Расположение статистики
    $Select2[]=array("Нет",0,$data['stat_flag']);
    $Select2[]=array("Слева",2,$data['stat_flag']);
    $Select2[]=array("Справа",1,$data['stat_flag']);

    // Активация по e-mail
    $Select3[]=array("Активация пользователя по e-mail",1,$data['mail_check']);
    $Select3[]=array("Ручная активация пользователя",2,$data['mail_check']);

    $Tab1=$PHPShopGUI->setField("Расположение блока авторизации:",
             $PHPShopGUI->setSelect("flag_new",$Select,250,1).
            $PHPShopGUI->setLine().
            $PHPShopGUI->setCheckbox("enabled_new",1,"Вывод блока на сайте",$data['enabled']));

    $Tab1.=$PHPShopGUI->setField("Расположение блока статистики:",$PHPShopGUI->setSelect("stat_flag_new",$Select2,250));
    $Tab1.=$PHPShopGUI->setLine();
    $Tab1.=$PHPShopGUI->setField("Captcha:",$PHPShopGUI->setCheckbox("captcha_new",1,'Защита от спама',$data['captcha']));
    $Tab1.=$PHPShopGUI->setField("Активация:",$PHPShopGUI->setSelect("mail_check_new",$Select3,250,1));

    $Info='Для интеграции с другими модулями в качестве проверки авторизации используйте проверку существования переменной $_SESSION[UserName]
     или конструкцию:
     <pre>$PHPShopUsersElement = new PHPShopUsersElement();
     if($PHPShopUsersElement->is_autorization()) авторизация пройдена
     </pre>
     <p>
     Для добавления полей в форму регистрации отредактируйте файл <mark>/phpshop/modules/users/templates/users_forma_register.tpl</mark>, добавьте в него
     требуемые поля с префиксом dop_, пример:</p>
          <pre>&lt;input  type="text" name="dop_Возраст" size="25"&gt;</pre>
     Для доступа к таким полям в других модулях используется конструкция:
          <pre>$PHPShopUsersElement = new PHPShopUsersElement();
$PHPShopUsersElement->getParam("Возраст");</pre>
     Для произвольного размещения формы авторизации отключите опцию вывода блока на сайте и используйте переменную <kbd>@autorizationForma@</kbd> и <kbd>@onlineForma@</kbd> для вставки в свой шаблон.
';
    $Tab2=$PHPShopGUI->setInfo($Info);


    // Содержание закладки 2
    $Tab3=$PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,true),array("Описание",$Tab2),array("О Модуле",$Tab3));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", 1) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>