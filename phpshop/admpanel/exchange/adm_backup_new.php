<?php

$TitlePage = __('Создание резервной копии базы');

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules;

    $PHPShopGUI->action_button['Создать'] = array(
        'name' => 'Создать',
        'action' => 'saveID',
        'class' => 'btn  btn-default btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-download-alt'
    );


    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./exchange/gui/exchange.gui.js');
    $PHPShopGUI->setActionPanel(__("Создание резервной копии базы"), false, array('Создать'));

    $structure_value[] = array('Структура и данные', '0', 'selected');
    $structure_value[] = array('Только структура', '1', '');

    // Список таблиц
    foreach ($GLOBALS['SysValue']['base'] as $val) {
        if (is_array($val)) {
            foreach ($val as $mod_base)
                $baseArray[$mod_base] = $mod_base;
        }
        else
            $baseArray[$val] = $val;
    }


    foreach ($baseArray as $val) {
        $table.='<option value="' . $val . '" selected class="">' . $val . '</option>';
    }

    // Содержание закладки 1
    $PHPShopGUI->_CODE.= $PHPShopGUI->setCollapse('Настройки', $PHPShopGUI->setField('Таблицы', '
        <table >
        <tr>
        <td>
        <select id="pattern_table" style="height:300px;width:500px" name="pattern_table[]" multiple class="form-control" required>' . $table . '</select>
        </td>
        <td>&nbsp;</td>
        <td class="text-center"><a class="btn btn-default btn-sm" href="#" id="select-all" data-toggle="tooltip" data-placement="top" title="Выбрать все"><span class="glyphicon glyphicon-chevron-left"></span></a><br><br>
        <a class="btn btn-default btn-sm" id="select-none" href="#" data-toggle="tooltip" data-placement="top" title="Убрать выделение со всех"><span class="glyphicon glyphicon-chevron-right"></span></a></td>
        </tr>
   </table>
            
' . $PHPShopGUI->setHelp('Для выбора более одной записи нажмите левой кнопкой мыши на запись, удерживая клавишу CTRL')) .
            $PHPShopGUI->setField('GZIP сжатие', $PHPShopGUI->setCheckbox('export_gzip', 1, 'Включить', 1), 1, 'Сокращает размер создаваемого файла') .
            $PHPShopGUI->setField('Варианты копирования', $PHPShopGUI->setSelect('export_structure', $structure_value, 300)));

    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, false);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionCreate.exchange.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция записи
function actionCreate() {
    global $PHPShopModules;

    include_once('./dumper/dumper.php');
    
    $is_safe_mode = ini_get('safe_mode') == '1' ? 1 : 0;
    if (!$is_safe_mode) set_time_limit(600);

    ob_start();
    mysqlbackup($GLOBALS['SysValue']['connect']['dbase'], $_POST['export_structure'], $_POST['pattern_table']);
    $dump = ob_get_clean();
   

    // Обновление
    if (!empty($_REQUEST['update']))
        $file = 'upload_dump.sql';
    else
        $file = 'base_' . date("d_m_y_His") . '.sql';

    $file = "./dumper/backup/" . $file;
    PHPShopFile::write($file, $dump);

    // Gzip
    if (!empty($_REQUEST['export_gzip'])) {
        PHPShopFile::gzcompressfile($file);
    }

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    if (empty($_REQUEST['update']))
        header('Location: ?path=' . $_GET['path']);
    else
        return array('success' => true);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>