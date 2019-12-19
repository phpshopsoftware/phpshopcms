<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.filemanager.filemanager_system"));

// Выбор файла
function GetFile($dir, $name) {
    global ${$name}, $root;

    if ($dh = @opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != ".." && $file != '.tmb' && $file != '.quarantine') {
                if (is_dir($dir . "/" . $file)) {
                    ${$name}[str_replace($root, '', $dir) . "/" . $file] = $file;
                    GetFile($dir . "/" . $file, $name);
                }
                else
                    ${$name}[str_replace($root, '', $dir) . "/" . $file] = $file;
            }
        }
        closedir($dh);
    }

    return null;
}

// Создание sitemap
function setGeneration() {
    global $BASE, $root;

    @set_time_limit(10000);

    $root = $_SERVER['DOCUMENT_ROOT'];
    $dir_global = $root . "/UserFiles/Files";
    GetFile($dir_global, "BASE");

    $lang = @parse_ini_file('../modules/filemanager/server/lang.ini', 1);

    $ini = null;
    if (is_array($BASE))
        foreach ($BASE as $path => $file) {

            if (!empty($lang[$path])) {
                $ini.='[' . $path . ']
name = "' . $lang[$path]['name'] . '";
size = "' . $lang[$path]['size'] . '";
';
            } else {
                $ini.='[' . $path . ']
name = "' . $file . '";
size = "' . round(@filesize($root . $path) / (1024 * 1024), 1) . '";
';
            }
        }

    // Запись в файл
    if (fwrite(fopen('../modules/filemanager/server/lang.ini', "w+"), $ini))
        echo '<div class="alert alert-success" id="rules-message"  role="alert">Файл <strong>lang.ini</strong> успешно создан.</div>';
    else
        echo '<div class="alert alert-danger" id="rules-message"  role="alert">Ошибка сохранения файла в папке /phpshop/modules/filemanager/server/lang.ini !</div>';
}

// Функция обновления
function actionUpdate() {

    setGeneration();
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $select_name;

    $PHPShopGUI->action_button['Создать'] = array(
        'name' => 'Создать карту перевода',
        'action' => 'saveID',
        'class' => 'btn  btn-default btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-import'
    );

    $PHPShopGUI->action_button['Открыть'] = array(
        'name' => 'Файлы',
        'action' => '../modules/filemanager/server/?full=true',
        'class' => 'btn  btn-default btn-sm navbar-btn btn-action-panel-blank',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-hdd'
    );


    $PHPShopGUI->setActionPanel(__("Настройка модуля") . ' <span id="module-name">' . ucfirst($_GET['id']) . '</span>', $select_name, array('Создать', 'Открыть', 'Закрыть'));

    // Выборка
    $data = $PHPShopOrm->select();

    $Info = '<p>Для вызова файлового менеджера необходимо добавить в шаблон кнопку вызова с указанием атрибута <code>class="openFilemanagerModal"</code> и переменную <code>@filemanager@</code> для вывода модального окна файлового менеджера. Пример:
        <code>&lt;button class="openFilemanagerModal"&gt;Файловый менеджер&lt;/button&gt;</code>
</p>
        <p>Для управления именами файлов и каталогов:
<ol>
<li>Создать карту перевода через меню модуля</li>
<li>Отредактировать файл перевода <code>/phpshop/modules/filemanager/server/lang.ini</code></li>
<ol>        
</p>';

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Инструкция", $PHPShopGUI->setInfo($Info)), array("О Модуле", $Tab3));

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