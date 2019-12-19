<?php

PHPShopObj::loadClass("page");
PHPShopObj::loadClass("string");

$TitlePage = __('Новая страница');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page']);

// Построение дерева категорий
function treegenerator($array, $i, $curent) {
    global $tree_array;
    $del = '¦&nbsp;&nbsp;&nbsp;&nbsp;';
    $tree_select = $check = false;

    $del = str_repeat($del, $i);
    if (is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {

            $check = treegenerator($tree_array[$k], $i + 1, $curent);

            if ($k == $curent)
                $selected = 'selected';
            else
                $selected = null;

            if (empty($check['select'])) {
                $tree_select.='<option value="' . $k . '" ' . $selected . '>' . $del . $v . '</option>';
                $i = 1;
            } else {
                $tree_select.='<option value="' . $k . '" ' . $selected . '>' . $del . $v . '</option>';
                //$i++;
            }

            $tree_select.=$check['select'];
        }
    }
    return array('select' => $tree_select);
}

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopModules, $TitlePage;

    // Начальные данные
    $data = array();
    $data['num'] = 1;

    $PHPShopGUI->action_select['Урок'] = array(
        'name' => 'Обучение',
        'action' => 'presentation',
        'icon' => 'glyphicon glyphicon-education'
    );

    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->setActionPanel($TitlePage, array('Урок'), array('Создать и редактировать', 'Сохранить и закрыть'));
    $PHPShopGUI->addJSFiles('./js/bootstrap-tour.min.js', './page/gui/tour.gui.js', './page/gui/page.gui.js');


    $PHPShopCategoryArray = new PHPShopPageCategoryArray();
    $CategoryArray = $PHPShopCategoryArray->getArray();

    $CategoryArray[0]['name'] = '- Корневой уровень -';
    $tree_array = array();

    $PHPShopCategoryArrayKey = $PHPShopCategoryArray->getKey('parent_to.id', true);
    if (is_array($PHPShopCategoryArrayKey))
        foreach ($PHPShopCategoryArrayKey as $k => $v) {
            foreach ($v as $cat) {
                $tree_array[$k]['sub'][$cat] = $CategoryArray[$cat]['name'];
            }
            $tree_array[$k]['name'] = $CategoryArray[$k]['name'];
            $tree_array[$k]['id'] = $k;
        }


    $GLOBALS['tree_array'] = &$tree_array;

    $tree_select = '<select class="selectpicker show-menu-arrow hidden-edit" data-container=""  data-style="btn btn-default btn-sm" name="category_new" data-width="100%">';

    $tree_array[0]['sub'][1000] = 'Главное меню сайта';
    $tree_array[0]['sub'][2000] = 'Начальная страница';

    $tree_select.='<option value="0" ' . $data['category'] . ' data-subtext="<span class=\'glyphicon glyphicon-cog\'></span> Настройка">Внутренняя страница</option>';
    if (is_array($tree_array[0]['sub']))
        foreach ($tree_array[0]['sub'] as $k => $v) {
            $check = treegenerator($tree_array[$k], 1, $data['category']);

            if ($k == $data['category'])
                $selected = 'selected';
            else
                $selected = null;

            if (in_array($k, array(1000, 2000)))
                $subtext = 'data-subtext="<span class=\'glyphicon glyphicon-cog\'></span> Настройка"';
            else
                $subtext = null;

            $tree_select.='<option value="' . $k . '" ' . $selected . ' ' . $subtext . '>' . $v . '</option>';

            $tree_select.=$check['select'];
        }
    $tree_select.='</select>';


    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '550';
    $oFCKeditor->Value = $data['content'];

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setCollapse(__('Информация'), $PHPShopGUI->setField(__("Размещение:"), $tree_select) .
            $PHPShopGUI->setField("Заголовок:", $PHPShopGUI->setInput("text.requared", "name_new", $data['name'])) .
            $PHPShopGUI->setField("Сортировка:", $PHPShopGUI->setInputText("№", "num_new", $data['num'], 150)) .
            $PHPShopGUI->setField("URL Ссылка:", $PHPShopGUI->setInputText('/page/', "link_new", $data['link'], 300, '.html')));

    $SelectValue[] = array('Вывод в каталоге', 1, $data['enabled']);
    $SelectValue[] = array('Заблокировать', 0, $data['enabled']);
    //$SelectValue[] = array('Внутренняя страница', 2, $data['enabled']);
    $Tab1.= $PHPShopGUI->setField("Опции вывода:", $PHPShopGUI->setSelect("enabled_new", $SelectValue, 300));

    // Содержание закладки 3
    $Tab3 = $PHPShopGUI->setField("Title: ", $PHPShopGUI->setTextarea("title_new", $data['title']));
    $Tab3.=$PHPShopGUI->setField("Description: ", $PHPShopGUI->setTextarea("description_new", $data['description']));
    $Tab3.=$PHPShopGUI->setField("Keywords: ", $PHPShopGUI->setTextarea("keywords_new", $data['keywords']));


    $Tab1.=$PHPShopGUI->setCollapse(__('SEO / Мета-данные'), $Tab3);
    
        // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Содержание", $oFCKeditor->AddGUI()));



    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.page.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * Экшен записи
 * @return bool 
 */
function actionInsert() {
    global $PHPShopModules, $PHPShopOrm;

    // Корректировка пустых значений
    $PHPShopOrm->updateZeroVars('enabled_new', 'secure_new');

    if (empty($_POST['link_new']))
        $_POST['link_new'] = PHPShopString::toLatin($_POST['name_new']);

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);

    if ($_POST['saveID'] == 'Создать и редактировать')
        header('Location: ?path=page&return=page.catalog&id=' . $action);
    else
        header('Location: ?path=page.catalog&cat=' . $_POST['category_new']);

    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>
