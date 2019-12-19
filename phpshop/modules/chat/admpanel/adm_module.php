<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.chat.chat_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));
    
    return $action;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    // Обязательное заполнение / в конце директории
    /*
      if(substr($_POST['upload_dir_new'], -1) != '/')
      $_POST['upload_dir_new'].='/';

      // Попытка проставить права 775 на папку для файлов
      @chmod($_SERVER['DOCUMENT_ROOT'] .$GLOBALS['SysValue']['dir']['dir'].'/UserFiles/Image/'.$_POST['upload_dir_new'],$_POST['chmod_new']); */

    $_SESSION['chat_skin'] = $_POST['skin_new'];

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

// Выбор шаблона
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

    // Выборка
    $data = $PHPShopOrm->select();

    // Вывод
    $e_value[] = array('не выводить', 0, $data['enabled']);
    $e_value[] = array('слева', 1, $data['enabled']);
    $e_value[] = array('справа', 2, $data['enabled']);

    // Тип вывода
    $w_value[] = array('форма', 0, $data['windows']);
    $w_value[] = array('всплывающее окно', 1, $data['windows']);


    $Tab1 = $PHPShopGUI->setField('Заголовок', $PHPShopGUI->setInputText(false, 'title_new', $data['title']));
    //$Tab1.=$PHPShopGUI->setField('CHMOD',$PHPShopGUI->setInputText(false, 'chmod_new', $chmod,100,'* 0775'),'left');
    $Tab1.=$PHPShopGUI->setLine() . $PHPShopGUI->setField('Приветственое сообщение', $PHPShopGUI->setTextarea('title_start_new', $data['title_start']));
    $Tab1.=$PHPShopGUI->setField('Cообщение выключенного режима', $PHPShopGUI->setTextarea('title_end_new', $data['title_end']));
    $Tab1.=$PHPShopGUI->setField('Место вывода', $PHPShopGUI->setSelect('enabled_new', $e_value, 200));
    $Tab1.=$PHPShopGUI->setField('Дизайн', GetSkinList($data['skin']));
    //$Tab1.=$PHPShopGUI->setField('Файлы пользователей',$PHPShopGUI->setInputText('/UserFiles/Image/','upload_dir_new', $upload_dir,100),'left');
    $Tab1.=$PHPShopGUI->setField('Тип вывода', $PHPShopGUI->setSelect('windows_new', $w_value, 200));

    $info = 'Для произвольной вставки элемента следует выбрать параметр вывода "Не выводить" и в ручном режиме вставить переменную
        <kbd>@chat@</kbd> в свой шаблон.
<p>
Для ответа на вопросы пользователей в чате следует установить пакет утилит <a class="btn btn-xs btn-default" href="http://www.phpshop.ru/loads/files/setup.exe" target="_blank"><span class="glyphicon glyphicon-save"></span> EasyControl</a> с выбранным пунктом для установки 
"Чат с посетителями". Чат появится в трее.
</p>
';

    $Tab2 = $PHPShopGUI->setInfo($info, 200, '96%');

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay(false, false, $data['version'], true);


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Инструкция", $Tab2), array("О Модуле", $Tab3));

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