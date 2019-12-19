<?php

/**
 * Библиотека административных интерфейсов
 * @author PHPShop Software
 * @version 2.1
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopGUI
 * @package PHPShopGUI
 */
class PHPShopGUI {

    var $css;
    var $dir = '../';
    var $includeJava;
    var $includeCss;
    var $padding = 5;
    var $margin = 5;
    var $tab_pre = '_';
    var $form_enabled = true;
    var $sidebarLeftCell = 2;
    var $sidebarLeftRight = 2;
    var $dropdown_action_form = true;
    var $tab_key = 0;
    var $nav_style = 'nav-pills';

    /**
     * Конструктор
     */
    function __construct() {

        // Языковой файл
        PHPShopObj::loadClass("lang");
    }

    /**
     * Прорисовка сетки элементов
     */
    function setGrid() {
        $Arg = func_get_args();
        $dis = '<div class="row">';
        foreach ($Arg as $val) {
            if (is_array($val))
                $dis.='<div class="col-md-' . $val[1] . '">' . $val[0] . '</div>';
        }
        $dis.='</div>';

        return $dis;
    }

    /**
     * Прорисовка иконки
     * @param string $img иконка
     * @param string $class css
     */
    function i($img, $class = null) {
        return '<span class="glyphicon glyphicon-' . $img . ' ' . $class . '"></span> ';
    }

    /**
     * Иконка с выбором исходника
     * @param string $data путь
     * @param string $id имя поля
     * @param bool $drag_off загрузка перетаскиванием
     * @param array $option настройки
     */
    function setIcon($data, $id = "icon_new", $drag_off = false, $option = array('load' => true, 'server' => true, 'url' => true, 'multi' => false, 'view' => false), $width = false) {

        if (!empty($data)) {
            $name = '<span data-icon="' . $id . '">' . $data . '</span>';
            if (!empty($width))
                $width = 'style="max-width:' . $width . 'px"';
            $icon = '<img src="' . $data . '" data-thumbnail="' . $id . '" onerror="this.onerror = null;this.src = \'./images/no_photo.gif\'" class="img-thumbnail" ' . $width . '>';
            $icon_hide = $drag = '';
        } else {
            $icon = '<img class="img-thumbnail img-thumbnail-dashed" data-thumbnail="' . $id . '" src="images/no_photo.gif">';
            $name = '<span data-icon="' . $id . '">Выбрать файл</span>';
            $icon_hide = 'hide';
            $drag = '<input type="file" name="file"  data-target="' . $id . '">';
        }

        $add = $help = null;

        if (!empty($option['load']))
            $add.='<span class="btn btn-default btn-file">Загрузить<input type="file" name="file"  data-target="' . $id . '"></span>';
        if (!empty($option['multi']))
            $add.='<button type="button" class="btn btn-default" id="uploaderModal">Пакетно</button>';
        if (!empty($option['server']))
            $add.='<button type="button" class="btn btn-default" data-return="return=' . $id . '" data-toggle="modal" data-target="#elfinderModal" data-path="image">Сервер</button>';

        if (!empty($option['url']))
            $add.='<button type="button" class="btn btn-default" id="promtUrl" data-target="' . $id . '">URL</button>
                 <input type="hidden" name="furl" id="furl" value="0">';

        if ($drag_off) {
            $drag = null;
            $icon = str_replace('img-thumbnail-dashed', null, $icon);
        }

        $dis = '
        <div class="row">
           <div class="col-md-2 btn-file"><a href="#" class="link-thumbnail">' . $icon . '</a>' . $drag . '</div>';

        if (empty($option['view']))
            $dis.='
           <div class="col-md-10">
             <p><span class="remove glyphicon glyphicon-remove-sign ' . $icon_hide . '" data-return="' . $id . '" data-toggle="tooltip" data-placement="top" title="Удалить эту запись"></span> ' . $name . '</p><input type="hidden" name="' . $id . '" value="' . $data . '">
               <div class="btn-group btn-group-sm" role="group" aria-label="...">
                 ' . $add . '
              </div>
          </div>';
        else
            $dis.='<input type="hidden" name="' . $id . '" value="' . $data . '">';

        $dis.='</div>';

        return $dis;
    }

    /**
     * Файл с выбором исходника
     * @param string $data путь
     * @param string $id имя поля
     */
    function setFile($data = null, $id = "lfile", $option = array('load' => true, 'server' => 'file', 'url' => true, 'view' => false)) {

        if (!empty($data)) {
            $name = '<span data-icon="' . $id . '">' . $data . '</span>';
            $icon_hide = '';
        } else {
            $name = '<span data-icon="' . $id . '">Выбрать файл</span>';
            $icon_hide = 'hide';
        }

        $add = null;
        if (!empty($option['load']))
            $add.='<span class="file-input btn btn-default btn-file">Загрузить<input type="file" name="file" data-target="' . $id . '"></span>';
        if (!empty($option['server']))
            $add.='<button type="button" class="btn btn-default" id="server" data-return="return=' . $id . '" data-toggle="modal" data-target="#elfinderModal" data-path="' . $option['server'] . '">Сервер</button>';

        if (!empty($option['url']))
            $add.='<button type="button" class="btn btn-default" id="promtUrl" data-target="' . $id . '">URL</button><input type="hidden" name="furl" id="furl" value="0">';


        if (empty($option['view']))
            $dis.='
             <p><span class="remove glyphicon glyphicon-remove-sign ' . $icon_hide . '" data-return="' . $id . '" data-toggle="tooltip" data-placement="top" title="Удалить эту запись"></span> ' . $name . '</p><input type="hidden" name="' . $id . '" id="' . $id . '" value="' . $data . '" >
               <div class="btn-group btn-group-sm" role="group" aria-label="...">
                 ' . $add . '
              </div>
        ';
        else
            $dis.='<input type="hidden" name="' . $id . '" id="' . $id . '" value="' . $data . '" >';

        return $dis;
    }

    /**
     *  Сообщение
     * @param string $text текст сообщения
     * @param string $type офрмление [succes | danger]
     */
    function setAlert($text, $type = 'success') {
        return '<div class="alert alert-' . $type . ' alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  ' . $text . '</div>';
    }

    /**
     *  Анимация загрузки
     * @param string $title тест сообщения
     * @param string $width длина
     * @param string $class css класс
     */
    function setProgress($title, $class = false, $width = '100%') {
        return ' 
           <div class="progress ' . $class . '"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100" style="width: ' . $width . '">' . $title . '</div></div>   ';
    }

    /**
     *  Левый сайдбар
     */
    function setSidebarLeft($data = array(), $cell = 2, $hide_mobile = false) {
        $dis = null;
        $i = 1;


        if (is_array($data))
            foreach ($data as $key => $val)
                if (is_array($val)) {

                    $dis.='<div class="sidebar-data ' . $hide_mobile . ' ' . $val['class'] . '" id="' . $val['id'] . '">';


                    // Иконки управления
                    if (!empty($val['title-icon']))
                        $dis.='<span class="pull-right title-icon">' . $val['title-icon'] . '</span>';

                    $dis.='<h6>' . $val['title'] . '</h6>';
                    $dis.='<div class="row">';

                    if (!empty($val['icon'])) {
                        $cell_icon = 1;
                        $cell_info = 10;
                    } else {
                        $cell_icon = 0;
                        $cell_info = 12;
                        $icon_class = 'hide';
                    }

                    $dis.='<div class="col-md-' . $cell_icon . ' ' . $icon_class . ' "><span class="glyphicon glyphicon-' . $val['icon'] . '"></span></div>';
                    $dis.='<div class="col-md-' . $cell_info . '"><adress>';

                    if (is_array($val['name'])) {
                        $dis.='<strong><a href="' . $val['name']['link'] . '" class="sidebar-data-0">' . $val['name']['caption'] . '</a></strong><br>';
                    } elseif (!empty($val['name']))
                        $dis.='<strong class="sidebar-data-0">' . $val['name'] . '</strong><br>';

                    if (is_array($val['content']))
                        foreach ($val['content'] as $key => $list) {

                            if (is_array($list)) {
                                $dis.='<a href="' . $list['link'] . '" class="sidebar-data-' . ($key + 1) . '">' . $list['caption'] . '</a><br>';
                            }
                            else
                                $dis.='<span class="sidebar-data-' . ($key + 1) . '">' . $list . '</span><br>';
                        }
                    else
                        $dis.=$val['content'];

                    $dis.='</adress>
                           </div>
                        </div>
                      </div>';

                    if ($i < count($data))
                        $dis.='<hr>';

                    $i++;
                }

        if ($hide_mobile)
            $hide_mobile = 'hidden-xs';

        $this->sidebarLeft = '<div class="col-md-' . $cell . '  sidebar-left ' . $hide_mobile . '">' . $dis . '</div>';
    }

    /**
     *  Правый сайдбар
     */
    function setSidebarRight($data = array(), $cell = 2) {
        $dis = null;
        $i = 1;

        if (is_array($data))
            foreach ($data as $key => $val)
                if (is_array($val)) {

                    $dis.='<div class="sidebar-data" id="' . $val['idelement'] . '">';
                    $dis.='<h6>' . $val['title'] . '</h6>';
                    $dis.='<div class="row">';

                    if (!empty($val['icon'])) {
                        $cell_icon = 2;
                        $cell_info = 10;
                    } else {
                        $cell_icon = 0;
                        $cell_info = 12;
                        $icon_class = 'hide';
                    }

                    $dis.='<div class="col-md-' . $cell_icon . ' ' . $icon_class . '"><span class="glyphicon glyphicon-' . $val['icon'] . '"></span></div>';
                    $dis.='<div class="col-md-' . $cell_info . '"><adress>';

                    if (is_array($val['name'])) {
                        $dis.='<strong><a href="' . $val['name']['link'] . '">' . $val['name']['caption'] . '</a></strong><br>';
                    } elseif (!empty($val['name']))
                        $dis.='<strong>' . $val['name'] . '</strong><br>';

                    if (is_array($val['content']))
                        foreach ($val['content'] as $list) {

                            if (is_array($list)) {
                                $dis.='<a href="' . $list['link'] . '">' . $list['caption'] . '</a><br>';
                            }
                            else
                                $dis.=$list;
                        }
                    else
                        $dis.=$val['content'];

                    $dis.='</adress>
                           </div>
                        </div>
                      </div>';

                    if ($i < count($data))
                        $dis.='<hr>';

                    $i++;
                }

        $this->sidebarRight = '<div class="col-md-' . $cell . ' sidebar-right">' . $dis . '</div>';
    }

    /**
     * Выпадающая акшен панель 
     */
    function setActionPanel($title, $action = array(), $button = array()) {
        global $subpath;

        if (empty($GLOBALS['isFrame'])) {
            if ($subpath[0] != 'modules') {
                $xs_class = ' hidden-xs';
                $xs_btn_name = 'Сохранить и закрыть';
                $addFrameLink = null;
            } else {
                $xs_class = null;
                $xs_btn_name = 'Сохранить';
                $addFrameLink = '&frame=true';
            }

            $btnBack = 'Назад';
        } else {
            $addFrameLink = '&frame=true';
            $btnBack = 'Закрыть';
        }


        $this->action_button['Сохранить'] = array(
            'name' => 'Сохранить',
            'action' => 'editID',
            'class' => 'btn btn-default btn-sm navbar-btn',
            'type' => 'submit',
            'icon' => 'glyphicon glyphicon-floppy-saved'
        );

        $this->action_button['Создать и редактировать'] = array(
            'name' => 'Создать и редактировать',
            'action' => 'saveID',
            'class' => 'btn btn-default btn-sm navbar-btn',
            'type' => 'submit',
            'icon' => 'glyphicon glyphicon-floppy-saved'
        );

        $this->action_button['Сохранить и закрыть'] = array(
            'name' => $xs_btn_name,
            'action' => 'saveID',
            'class' => 'btn  btn-default btn-sm navbar-btn' . $xs_class . $GLOBALS['isFrame'],
            'type' => 'submit',
            'icon' => 'glyphicon glyphicon-ok'
        );

        $this->action_button['Закрыть'] = array(
            'name' => 'Закрыть',
            'action' => '',
            'class' => 'btn btn-default btn-sm navbar-btn btn-action-back',
            'type' => 'button',
            'icon' => 'glyphicon glyphicon-remove'
        );


        $this->action_button['Добавить'] = array(
            'name' => '',
            'action' => 'addNew',
            'class' => 'btn btn-default btn-sm navbar-btn',
            'type' => 'button',
            'icon' => 'glyphicon glyphicon-plus',
            'tooltip' => 'data-toggle="tooltip" data-placement="left" title="Добавить ' . $title . '"'
        );

        $this->action_select['Удалить выбранные'] = array(
            'name' => 'Удалить выбранные',
            'action' => 'select',
            'class' => 'disabled',
            'url' => '#'
        );

        $this->action_select['CSV'] = array(
            'name' => 'Экспортировать выбранные',
            'action' => 'export-select',
            'class' => 'disabled'
        );

        $this->action_select['Редактировать'] = array(
            'name' => 'Редактировать',
            'action' => 'edit',
            'url' => '#'
        );

        $this->action_select['Удалить'] = array(
            'name' => 'Удалить',
            'action' => 'deleteone',
            'url' => '#'
        );

        $this->action_select['Сбросить'] = array(
            'name' => 'Сбросить',
            'action' => 'reset',
            'url' => '#'
        );

        $this->action_select['Создать'] = array(
            'name' => 'Создать новый',
            'action' => 'new' . $GLOBALS['isFrame'],
            'url' => '#'
        );

        $this->action_select['Export'] = array(
            'name' => 'Экспорт данных',
            'action' => 'export',
            'url' => '#'
        );

        $this->action_select['|'] = array(
            'action' => 'divider' . $GLOBALS['isFrame'],
        );

        $this->action_select['Сделать копию'] = array(
            'name' => 'Сделать копию',
            'url' => '?path=' . $_GET['path'] . '&action=new&id=' . $_GET['id'] . $addFrameLink,
            'action' => $GLOBALS['isFrame']
        );


        if (!empty($_GET['return'])) {
            $back['url'] = $_GET['return'];
        }
        else
            $back['url'] = $_GET['path'];

        if (empty($_GET['id']))
            $back['class'] = 'back';
        else
            $back['class'] = null;

        /*
          if (strlen($title) > 73)
          $title = substr($title, 0, 73) . '...'; */

        $CODE = '
            <!-- Action panell -->
            <div class="navbar-header">
                        <a type="button" class="btn btn-default btn-sm navbar-btn pull-left ' . $back['class'] . ' check-frame" href="?path=' . $back['url'] . '">
                            <span class="glyphicon glyphicon-arrow-left"></span> ' . $btnBack . '</a>
                        <span class="navbar-brand hidden-xs ">' . $title . '</span>
                    </div>
                    <ul class="nav navbar-nav navbar-right pull-right">';

        // Кнопки
        if (is_array($button)) {

            foreach ($button as $val) {
                if (!empty($this->action_button[$val]['type']))
                    $CODE.='<li><button ' . $this->action_button[$val]['tooltip'] . ' type="' . $this->action_button[$val]['type'] . '" name="' . $this->action_button[$val]['action'] . '" class="' . $this->action_button[$val]['class'] . '" value="' . $this->action_button[$val]['name'] . '"><span class="' . $this->action_button[$val]['icon'] . '"></span> ' . $this->action_button[$val]['name'] . '</button>&nbsp;</li>';
            }
        }


        // Выпадающий список с выбранными
        if (is_array($action)) {

            $CODE.='<li class="hidden-xs"><button class="btn btn-default btn-sm navbar-btn" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span> <span class="caret"></span></button>
                <ul class="dropdown-menu select-action" role="menu">';

            foreach ($action as $val) {
                if (empty($this->action_select[$val]['name']))
                    $CODE.='<li class="' . $this->action_select[$val]['action'] . '"></li>';
                else {

                    if (empty($this->action_select[$val]['target']))
                        $this->action_select[$val]['target'] = '_self';

                    $CODE.='<li class="' . $this->action_select[$val]['class'] . '"><a href="' . $this->action_select[$val]['url'] . '" class="' . $this->action_select[$val]['action'] . '" data-option="' . $this->action_select[$val]['data'] . '" target="' . $this->action_select[$val]['target'] . '"><span class="' . $this->action_select[$val]['icon'] . '"></span> ' . $this->action_select[$val]['name'] . '</a></li>';
                }
            }

            $CODE.=' </ul>
                </li>';
        }
        $CODE.=' </ul>';


        $this->actionPanel = '<nav class="navbar-action">
                <div class="container" style="' . $GLOBALS['frameWidth'] . '">' . $CODE . '</div>
            </nav>
         <!-- /.Action panell -->
         <div id="fix-check" class="visible-lg"></div>
';
    }

    /**
     * Выпадающее меню
     * @param array $Arg массив аргументов
     * @param string $expand направление открытия меню
     * @return string
     */
    function setDropdown($Arg = array(), $expand = 'dropdown', $align = 'right', $passive = false) {
        global $subpath;

        $name = $Arg['caption'][$Arg['enable']];

        if (empty($passive) and empty($Arg['enable']))
            $passive = 'text-muted';
        else
            $passive = null;

        $CODE = '<div class="' . $expand . '">
            <a href="#" class="dropdown-toggle ' . $passive . '" data-toggle="' . $expand . '" style="color:' . $Arg['color'] . '"  role="button" aria-expanded="false"><span id="dropdown_status_' . $Arg['id'] . '">' . $name . '</span> <span class="caret hidden-xs"></span></a>
            <ul class="dropdown-menu dropdown-menu-' . $align . '" role="menu">';

        if (is_array($Arg['caption']))
            foreach ($Arg['caption'] as $key => $val) {
                if ($key == $Arg['enable'])
                    $class = 'class="disabled"';
                else
                    $class = null;
                $CODE.='<li ' . $class . '><a href="#"  data-id="' . $Arg['id'] . '" data-val="' . $key . '" class="status">' . $val . '</a></li>';
            }

        if (empty($this->path))
            $this->path = $_GET['path'];

        $CODE.= '</ul>
            </div>';

        if ($this->dropdown_action_form)
            $CODE.='<form method="post" action="?path=' . $this->path . '&id=' . $Arg['id'] . '" class="status_edit_' . $Arg['id'] . '">            
<input type="hidden" value="actionUpdate.' . $subpath[0] . '.edit" name="actionList[editID]">
<input type="hidden" value="1" name="ajax">
<input type="hidden" value="Обновить" name="editID">
<input type="hidden" value="0" name="enabled_new">
<input type="hidden" value="0" name="flag_new">
<input type="hidden" value="0" name="statusi_new">
<input type="hidden" value="' . $Arg['id'] . '" name="rowID">
</form>       
';
        return $CODE;
    }

    /**
     * Выпадающий список
     * @param array $Arg массив аргументов
     * @return string
     */
    function setDropdownAction($Arg = array()) {
        global $subpath;

        $this->action_title['edit'] = 'Редактировать';
        $this->action_title['delete'] = 'Удалить';
        $this->action_title['view'] = 'Предпросмотр';
        $this->action_title['copy'] = 'Сделать копию';
        $this->action_title['option'] = 'Настройки';
        $this->action_title['on'] = 'Включить';
        $this->action_title['off'] = 'Выключить';
        $this->action_title['email'] = 'Связь по E-mail';
        $this->action_title['|'] = '|';

        $CODE = '
        <div class="dropdown none hidden-sm hidden-xs" id="dropdown_action">
            <a href="#" class="dropdown-toggle btn btn-default btn-sm" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span> <span class="caret"></span></a>
            <ul class="dropdown-menu dropdown-menu-right" role="menu" >';


        foreach ($Arg as $val) {
            if ($val != $Arg['id']) {
                if (is_array($val)) {
                    $CODE.='<li><a href="' . $val['url'] . '">' . $val['name'] . '</a></li>';
                } else {
                    if ($this->action_title[$val] == '|')
                        $CODE.='<li class="divider"></li>';
                    else
                        $CODE.='<li><a href="#" data-id="' . $Arg['id'] . '" class="' . $val . '">' . $this->action_title[$val] . '</a></li>';
                }
            }
        }

        if (empty($this->path))
            $this->path = $_GET['path'];


        $CODE.= '</ul>
            </div>';

        if ($this->dropdown_action_form)
            $CODE.= ' 
<form method="post" action="?path=' . $this->path . '&id=' . $Arg['id'] . '" class="list_edit_' . $Arg['id'] . '">      
<input type="hidden" value="actionDelete.' . $subpath[0] . '.edit" name="actionList[delID]"> 
<input type="hidden" value="Удалить" name="delID">
<input type="hidden" value="1" name="ajax">
<input type="hidden" value="' . $Arg['id'] . '" name="rowID">
</form>
        ';

        return $CODE;
    }

    /**
     * Загрузка внешнего файла интерфейса
     * @param string $class_name имя класса, согласно config.ini
     * @param array $data массив данных
     * @param string $path путь до папки gui
     * @param mixed $option дополнительные параметры 
     */
    function loadLib($file, $data, $path = false, $option = false) {

        if (empty($path))
            $path = $_GET['path'] . '/';

        $class_path = $path . 'gui/' . $file . '.gui.php';
        if (file_exists($class_path)) {
            require_once($class_path);
            return $file($data, $option);
        }
        else
            echo "Нет файла " . $class_path;
    }

    /**
     * Прорисовка элемента Form
     * @param string $value содержание
     * @param string $action action
     * @param string $name имя
     * @param string $style CSS стилизация
     * @param string $target параметр target
     * @return string
     */
    function setForm($value, $action = false, $name = "product_edit", $style = false, $target = false, $class = 'form-horizontal') {
        $CODE.='<form method="get" target="' . $target . '" enctype="multipart/form-data" action="' . $action . '" name="' . $name . '" id="' . $name . '" style="' . $style . '"  class="' . $class . '">
            ' . $value . '</form>';
        return $CODE;
    }

    /**
     * Компиляция результата
     */
    function Compile($form = true) {

        $cell = 12;
        if (!empty($this->sidebarLeft))
            $cell = $cell - $this->sidebarLeftCell;

        if (!empty($this->sidebarRight))
            $cell = $cell - $this->sidebarLeftRight;

        if (!empty($_GET['return']))
            $path = $_GET['return'];
        else
            $path = $_GET['path'];


        if ($form)
            echo '<form method="post" enctype="multipart/form-data" name="product_edit" id="product_edit" class="form-horizontal" role="form" data-toggle="validator">';
        echo $this->actionPanel . '
                <div class="container row sidebarcontainer" style="' . $GLOBALS['frameWidth'] . '">
                    ' . $this->sidebarLeft . '
                    <div class="col-md-' . $cell . ' main">
                     ' . $this->_CODE . '
                    </div>
                    ' . $this->sidebarRight . '
                </div>
                <input type="hidden" value="' . $path . '" name="path" id="path">';

        if ($form)
            echo '</form> ';
        echo $this->includeJava . $this->includeCss;
    }

    /**
     * Прорисовка визуального редактора
     * @param string $editor
     */
    function setEditor($editor = false, $mod_enabled = false) {

        // Временный запрет подключения редакторов
        if (!in_array($editor, array('ace', 'none', 'default', 'tinymce')))
            $editor = 'default';

        // Временное перенаправление выбора редактора
        if (empty($editor))
            $editor = 'default';

        if ($mod_enabled)
            $editor_path = $this->dir . "editors/" . $editor . "/editor.php";
        else
            $editor_path = "./editors/" . $editor . "/editor.php";

        if (is_file($editor_path))
            include($editor_path);
        else {
            $this->setEditor($editor);
        }
    }

    /**
     * Перевод строки
     * @param string $value текст
     * @return string
     */
    function setLine($value = false, $padding_top = false) {
        $CODE = '
	 <div style="clear:both;padding-top:' . $this->chekSize($padding_top) . '">' . $value . '</div>';
        return $CODE;
    }

    /**
     * Прорисовка элемента Fieldset с легендой
     * @param mixed $title заголовок легенды
     * @param mixed $content содержание
     * @param mixed $size размер сетки описаняи поля 1-12
     * @param string $help подсказка
     * @param string $class класс стиля
     * @return string
     */
    function setField($title, $content, $size = 1, $help = null, $class = null, $label = 'control-label') {

        // Подсказка
        if (!empty($help))
            $help = $this->setHelpIcon($help);

        // Несколько блоков
        if (is_array($title) and is_array($content)) {

            $CODE = '
                <div class="form-group form-group-sm ' . $class . '">';

            foreach ($content as $k => $content_value) {

                if (!strpos($title[$k], ':') and !empty($title[$k]))
                    $title[$k].=':';

                $CODE.='<label class="col-sm-' . intval($size[$k][0]) . ' ' . $label . '">' . $title[$k] . $help[$k] . '</label><div class="col-sm-' . intval($size[$k][1]) . '">' . $content_value . '</div>';
            }
            $CODE.=' 
                </div>';
        }
        // Один блок
        else {

            // Поддержка старых размеров
            $old_size = array(
                'none' => 1,
                'left' => 1,
                'right' => 1
            );

            if (is_string($size))
                $size = $old_size[$size];

            // Общая настройка
            if (!empty($this->field_col))
                $size = $this->field_col;

            if (!strpos($title, ':') and !empty($title))
                $title.=':';

            $CODE = '
     <div class="form-group form-group-sm ' . $class . '">
        <label class="col-sm-' . intval($size) . ' ' . $label . '">' . $title . $help . '</label><div class="col-sm-' . (12 - intval($size)) . '">' . $content . '</div>
     </div>';
        }

        return $CODE;
    }

    /**
     * Прорисовка элемента выбора цвета
     * @param string $name имя поля
     * @param string $value значение
     * @param string $size размер
     * @return string
     */
    function setInputColor($name, $value, $size = 200, $id = false, $opt = false) {
        $add_option = null;

        if (!is_array($opt) and !empty($opt))
            $option['option'] = $opt;
        else
            $option = $opt;

        if (is_array($option))
            foreach ($option as $k => $v)
                $add_option.=' data-' . $k . '="' . $v . '" ';

        $CODE = '<div class="input-group color" style="width:' . $this->chekSize($size) . '">
    <input type="text" id="' . $id . '" name="' . $name . '" value="' . $value . '" class="form-control input-sm color-value" ' . $add_option . ' placeholder="#ffffff">
    <span class="input-group-addon input-sm" title="Выбрать цвет"><i></i></span></div>';
        return $CODE;
    }

    /**
     * Прорисовка элемента Input
     * @param string $type тип [text,password,button и т.д]
     * @param string $name имя
     * @param mixed $value значение
     * @param int $float float
     * @param int $size размер
     * @param string $onclick экшен по клику, имя javascript функции
     * @param string $class имя класса стиля
     * @param string $action привязка к экшену, имя php функции
     * @param string $caption текст перед элементом
     * @param string $description текст после элемента
     * @param string $placeholder placeholder
     * @return string
     */
    function setInput($type, $name, $value, $float = null, $size = false, $onclick = false, $class = false, $action = false, $caption = false, $description = false, $title = false) {
        static $passN;

        $class_array = array(
            'text' => 'form-control input-sm',
            'password' => 'form-control input-sm',
            'email' => 'form-control input-sm',
            'submit' => 'btn btn-primary',
            'button' => 'btn btn-default',
            'hidden' => 'hidden-edit',
            'reset' => 'btn btn-warning',
        );
        $style = null;



        if ($size)
            $style.= 'width:' . $this->chekSize($size) . ';';

        if ($float)
            $style.= 'float:' . $float . ';';

        if ($onclick)
            $onclick = 'onclick="' . $onclick . '"';

        if (strpos($type, '.')) {
            $type_array = explode(".", $type);
            $type = $type_array[0];
            $required = ' required ';

            if (!empty($type_array[2]))
                $required.=' data-minlength="' . intval($type_array[2]) . '" ';
        }

        // Проверка пароля
        if ($type == 'password') {

            if ($passN > 0)
                $data['match'] = ' data-match="#inputPassword" ';

            $id = 'inputPassword' . $passN;

            $passN++;
        }

        if (!empty($description) or !empty($caption)) {

            $CODE = ' <div class="input-group" style="' . $style . '">';

            if (!empty($caption))
                $CODE.= ' <div class="input-group-addon input-sm">' . $caption . '</div>';

            $CODE.= '<input class="' . $class_array[$type] . ' ' . $class . '" type="' . $type . '" value="' . $value . '"  name="' . $name . '" id="' . $name . '" placeholder="' . $title . '">';

            if (!empty($description))
                $CODE.='<div class="input-group-addon input-sm">' . $description . '</div>';

            $CODE.='</div>';
        }
        else
            $CODE = '<input class="' . $class_array[$type] . ' ' . $class . '" type="' . $type . '" value="' . $value . '"  name="' . $name . '" id="' . $id . '" style="' . $style . '" ' . $onclick . $data['match'] . ' placeholder="' . $title . '" ' . $required . '>';

        // Слушатель действия
        if ($action == true) {
            $this->action[$name] = $action;
        }

        return $CODE;
    }

    /**
     * Прорисовка элемента InputText
     * @param string $caption текст перед элементом
     * @param string $name имя
     * @param mixed $value значение
     * @param int $size размер
     * @param string $description текст после элемента
     * @param string $float  float
     * @param string $class имя класса стиля
     * @param string $placeholder имя класса стиля
     * @return string
     */
    function setInputText($caption, $name, $value, $size = false, $description = false, $float = false, $class = false, $placeholder = false) {

        // + fix
        $value = str_replace('&#43;', '+', $value);

        return $this->setInput('text', $name, htmlentities($value, ENT_COMPAT, 'cp1251'), $float, $size, false, $class, false, $caption, $description, $placeholder);
    }

    /**
     * Прорисовка элемента Panel
     * @return string
     */
    function setPanel($header, $content, $class = 'panel-default', $body = true) {
        $result = '<div class="panel ' . $class . '">
         <div class="panel-heading">' . $header . '</div>';
        if ($body)
            $result.= '<div class="panel-body">';
        $result.=$content;
        if ($body)
            $result.= '</div>';
        $result.= '</div>';
        return $result;
    }

    /**
     * Прорисовка элемента Input через массив 
     * @param array $arg массив опций [type,name,value,caption,description,placeholder,size]
     * @return string
     */
    function setInputArg($arg = array()) {
        if (is_array($arg)) {
            if ($arg['type'] == 'textarea')
                return $this->setTextarea($arg['name'], htmlentities($arg['value'], ENT_COMPAT, 'cp1251'), $arg['float'], $arg['width'], $arg['height'], $arg['description'], $arg['placeholder']);
            elseif ($arg['type'] == 'checkbox')
                return $this->setRadio($arg['name'], 1, $arg['caption'], $arg['value']) . $this->setRadio($arg['name'], 0, 'Выкл.', 1);
            else
                return $this->setInput($arg['type'], $arg['name'], htmlentities($arg['value'], ENT_COMPAT, 'cp1251'), $arg['float'], $arg['size'], false, $arg['class'], false, $arg['caption'], $arg['description'], $arg['placeholder']);
        }
    }

    /**
     * Прорисовка элемента выбора даты
     * @param string $name имя
     * @param mixed $value значение
     * @return string
     */
    function setInputDate($name, $value, $style = null, $class = 'col-md-2', $tooltip = false) {

        if ($tooltip)
            $tooltip = 'data-toggle="tooltip" data-placement="top" title="' . $tooltip . '"';

        return '
        <div class="input-group date ' . $class . '" style="' . $style . '">
        <input class="form-control input-sm" type="text" name="' . $name . '" value="' . $value . '" ' . $tooltip . '>
        <span class="input-group-addon input-sm"><span class="glyphicon glyphicon-remove"></span></span>
	<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></span>
        </div> ';
    }

    /**
     * Добавление закладки для модулей
     * <code>
     * // example:
     * $PHPShopGUI->setTab(array("Заголовок1","Содержание1","Высота"),array("Заголовок2","Содержание2","Высота"));
     * $PHPShopGUI->addTab(array("Заголовок3","Содержание3","Высота"));
     * </code>
     */
    function addTab() {

        $Arg = func_get_args();
        foreach ($Arg as $key => $val) {

            if (!empty($val[2]))
                $val[1] = '<hr>' . $val[1];


            if (!empty($val[3]))
                $this->tab_key_uid = $val[3];
            else
                $this->tab_key_uid = $this->tab_key;

            // Номер закладки в ссылке tab
            if ($this->tab_key_uid === $_GET['tab'])
                $active = 'active in';
            else
                $active = null;


            $this->addTabName.='<li role="presentation" class="' . $active . '"><a href="#tabs-' . $this->tab_key_uid . '" aria-controls="tabs-' . $this->tab_key_uid . '" role="tab" data-toggle="tab" data-id="' . $val[0] . '">' . $val[0] . '</a></li>';
            $this->addTabContent.='<div role="tabpanel" class="tab-pane ' . $active . ' fade" id="tabs-' . $this->tab_key_uid . '">' . $val[1] . '</div>';

            $this->tab_key++;
        }
    }

    /**
     * Прорисовка закладок Tab
     * <code>
     * // example:
     * $PHPShopGUI->setTab(array("Заголовок1","Содержание1"),array("Заголовок2","Содержание2"));
     * </code>
     */
    function setTab() {

        $Arg = func_get_args();


        $name = $content = null;


        foreach ($Arg as $key => $val) {

            if ($key == $_GET['tab']) {
                $active = 'active in';
            }
            else
                $active = null;

            // Переход на страницу
            if (!empty($val[2]) and !is_numeric($val[2]) and !is_bool($val[2])) {
                $toggle = null;
                $href = $val[2];
            } else {
                $toggle = 'data-toggle="tab"';
                $href = '#tabs-' . $this->tab_key;
            }

            if (is_bool($val[2])) {
                $hr = '<hr>';
            } else {
                $hr = null;
            }

            $name.='<li role="presentation" class="' . $active . '"><a href="' . $href . '" aria-controls="tabs-' . $this->tab_key . '" role="tab" ' . $toggle . ' data-id="' . $val[0] . '">' . $val[0] . '</a></li>';
            $content.='<div role="tabpanel" class="tab-pane ' . $active . ' fade" id="tabs-' . $this->tab_key . '">' . $hr . $val[1] . '</div>';

            $this->tab_key++;
        }

        $CODE = '
            <div role="tabpanel">
               <ul class="nav ' . $this->nav_style . '" role="tablist">' . $name . $this->addTabName . '</ul>
               <div class="tab-content"><p>' . $content . $this->addTabContent . '</p></div>
            </div>';
        $this->_CODE = $CODE;
        return $CODE;
    }

    /**
     * Проверка размера
     * @param mixed $size
     * @return string
     */
    function chekSize($size) {
        if (!strpos($size, '%') and !strpos($size, 'px'))
            $size.='px';
        return $size;
    }

    /**
     * Добавление JS файлов
     */
    function addJSFiles() {
        $Arg = func_get_args();
        foreach ($Arg as $val) {
            $this->includeJava.='<script src="' . $val . '"></script>';
        }
    }

    /**
     * Добавление CSS файлов
     */
    function addCSSFiles() {
        $Arg = func_get_args();
        foreach ($Arg as $val) {
            $this->includeCss.='<link href="' . $val . '" rel="stylesheet">';
        }
    }

    /**
     * Прорисовка элемента Div
     * @param string $align align
     * @param string $code содержание
     * @param string $style имя стиля css
     * @nane string $name имя блока
     * @return string
     */
    function setDiv($align, $code, $style = false, $name = 'div1') {
        $CODE = '
	 <div align="' . $align . '" style="' . $style . '" name="' . $name . '" id="' . $name . '">
	 ' . $code . '
	 </div>
	 ';
        return $CODE;
    }

    /**
     * Прорисовка подвала
     * @param string $code содержание
     */
    function setFooter($code) {
        $this->_CODE.=$this->setDiv("right", $code, false, 'footer');

        // Слушатель
        if (is_array($this->action))
            foreach ($this->action as $name => $function)
                $this->_CODE.=$this->setInput("hidden", "actionList[$name]", $function);
    }

    /**
     * Прорисовка справки
     * @param string $text справка
     * @param string $icon иконка
     */
    function setHelp($text, $icon = 'glyphicon-question-sign') {
        return '<span class="help-block"><span class="glyphicon ' . $icon . '"></span> ' . $text . '</span>';
    }

    /**
     * Прорисовка иконки справки
     * @param type $text
     */
    function setHelpIcon($text) {
        return '&nbsp;<span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top" title="' . $text . '" style="cursor:pointer;"></span> ';
    }

    /**
     * Прорисовка элемета Textarea
     * @param string $name имя
     * @param mixed $value значение
     * @param string $float float
     * @param mixed $width длина элемента
     * @param mixed $height ширина элемента
     * @param string $description help
     * @param string $placeholder placeholder
     * @return string
     */
    function setTextarea($name, $value, $float = "none", $width = false, $height = false, $description = false, $placeholder = false) {

        if (strpos($name, '.')) {
            $type_array = explode(".", $name);
            $name = $type_array[0];
            $required = ' required ';

            if (!empty($type_array[2]))
                $required.=' data-minlength="' . intval($type_array[2]) . '" ';
        }
        else
            $required = null;

        if (!empty($description))
            $description = '<span class="help-block"><span class="glyphicon glyphicon-question-sign"></span> ' . $description . '</span>';
        $style = null;
        if (empty($width))
            $style.='width:100%;';
        else
            $style.='width:' . $this->chekSize($width) . ';';
        if (empty($height))
            $style.='height:50px;';
        else
            $style.='height:' . $this->chekSize($height) . ';';

        $CODE = '<textarea style="' . $style . '" name="' . $name . '" id="' . $name . '" class="form-control" placeholder="' . $placeholder . '" ' . $required . '>' . $value . '</textarea>' . $description;
        return $CODE;
    }

    /**
     * Прорисовка сворачиваемого списка
     * @param string $title заголовок
     * @param string $content содержание
     * @param string $collapse свернут или развернут при загрузки
     * @param bool $line показывать разделительную линию
     * @param bool $icons показывать иконку
     * @param array $opt массим дополнительных параметров [data-x]
     * @return string
     */
    function setCollapse($title, $content, $collapse = 'in', $line = true, $icons = true, $opt = false) {
        static $collapseID;

        $datatoggle = 'data-toggle="collapse"';

        // Параметры
        $add_option = null;
        if (!is_array($opt) and !empty($opt))
            $option['option'] = $opt;
        else
            $option = $opt;
        if (is_array($option))
            foreach ($option as $k => $v)
                $add_option.=' data-' . $k . '="' . $v . '" ';

        if ($collapse == 'in')
            $icon = 'bottom';
        elseif ($collapse == 'none') {
            $collapse = 'in';
            $datatoggle = null;
        }
        else
            $icon = 'right';

        if ($line) {
            $CODE = '<hr>';
        }

        if ($icons)
            $icons = '<span class="glyphicon glyphicon-triangle-' . $icon . '"></span>';

        $CODE.= '<a name="set' . $collapseID . '"></a><div class="collapse-block"><h4 ' . $datatoggle . ' data-target="#collapseExample' . $collapseID . '" aria-expanded="true" aria-controls="collapseExample">' . $title . ' ' . $icons . '</h4>
            <div class="collapse ' . $collapse . '" id="collapseExample' . $collapseID . '" '.$add_option.'>' . $content . '</div></div>';
        $collapseID++;
        return $CODE;
    }

    /**
     * Прорисовка блока инструкции с прокруткой
     * @param string $value содержание text
     * @return string
     */
    function setInfo($value) {
        return '<p><div class="panel panel-default"><div class="panel-body">' . $value . '</div></div></p>';
    }

    /**
     * Прорисовка элемента Select
     * <code>
     * // example:
     * $value[]=array('моя цифра 1',123,'selected');
     * $value[]=array('моя цифра 2 ',567, false);
     * $PHPShopGUI->setSelect('my',$value,100);
     * 
     * // example optgroup:
     * $opt_value[]=array('моя цифра 1',123,'selected');
     * $opt_value[]=array('моя цифра 2 ',567, false);
     * $value[]=array('моя группа 1',$opt_value);
     * $PHPShopGUI->setSelect('my',$value,100);
     * </code>
     * @param string $name имя
     * @param array $value значенение в виде массива
     * @param int $width ширина
     * @param string $float float
     * @param string $caption текст перед элементом
     * @param string $search режим поиска
     * @param int $height высота
     * @param int $size размер
     * @param bool $multiple мультивыбор
     * @param string $id id
     * @param string $class class [selectpicker]
     * @param string $onchange JS функция изменения выбора
     * @param string $style class [btn btn-default btn-sm]
     * @return string
     */
    function setSelect($name, $value, $width = '', $float = "none", $caption = false, $search = false, $height = false, $size = 1, $multiple = false, $id = false, $class = 'selectpicker', $onchange = null, $style = 'btn btn-default btn-sm') {

        if ($search)
            $search = 'data-live-search="true" data-placeholder="123"';

        if ($multiple)
            $multiple = 'multiple';

        if (empty($id))
            $id = $name;

        $CODE = $caption . '<select class="' . $class . ' show-menu-arrow hidden-edit" ' . $search . ' data-container=""  data-style="' . $style . '" data-width="' . $width . '"  name="' . $name . '" id="' . $id . '" size="' . $size . '" onchange="' . $onchange . '"   ' . $multiple . '>';
        if (is_array($value))
            foreach ($value as $val) {

                // Автовыделение 
                if ($val[2] == $val[1])
                    $val[2] = "selected";
                elseif ($val[2] != "selected")
                    $val[2] = null;


                if (is_array($val[1])) {
                    $CODE.='<optgroup label="' . $val[0] . '">';
                    foreach ($val[1] as $group_val) {

                        // Автовыделение в группе
                        if ($group_val[2] == $group_val[1])
                            $group_val[2] = "selected";

                        $CODE.='<option value="' . $group_val[1] . '" ' . $group_val[2] . '>' . $group_val[0] . '</option>';
                    }
                    $CODE.='</optgroup>';
                }
                else
                    $CODE.='<option value="' . $val[1] . '" ' . $val[2] . ' ' . $val[3] . '>' . $val[0] . '</option>';
            }
        $CODE.='</select>
	 ';
        return $CODE;
    }

    /**
     * Заполнение элемента Select
     * @param int $n
     * @return array
     */
    function setSelectValue($n, $max = 10) {
        $i = 1;
        while ($i <= $max) {
            if ($n == $i)
                $s = "selected";
            else
                $s = "";
            $select[] = array($i, $i, $s);
            $i++;
        }
        return $select;
    }

    /**
     * Прорисовка элемента
     * @param string $name имя
     * @param string $value значение
     * @param string $caption описание
     * @param string $checked checked
     * @return string
     */
    function setCheckbox($name, $value, $caption, $checked = 1, $disabled = null) {

        if ($checked == 1)
            $checked = "checked";
        elseif ($checked == 0)
            $checked = null;

        if (!empty($caption))
            $CODE = '<div class="checkbox-inline"><label><input type="checkbox" value="' . $value . '" name="' . $name . '" id="' . $name . '" ' . $checked . ' ' . $disabled . '> ' . $caption . '</label></div> ';
        else
            $CODE.='<input type="checkbox" value="' . $value . '" name="' . $name . '" id="' . $name . '" ' . $checked . ' ' . $disabled . '>';

        return $CODE;
    }

    /**
     * Прорисовка элемента Radio
     * @param string $name имя
     * @param string $value значение
     * @param string $caption описание
     * @param mixed $checked checked
     * @param string $onchange имя javascript функции по экшену onchange
     * @param string $class имя класса css
     * @param array $opt массив дополнительных параметров [data-x]
     * @return string
     */
    function setRadio($name, $value, $caption, $checked = "checked", $onchange = false, $class = false, $opt = false) {

        // Автовыделение 
        if ($value == $checked)
            $checked = "checked";

        // Параметры
        $add_option = null;
        if (!is_array($opt) and !empty($opt))
            $option['option'] = $opt;
        else
            $option = $opt;
        if (is_array($option))
            foreach ($option as $k => $v)
                $add_option.=' data-' . $k . '="' . $v . '" ';

        if (!empty($onchange))
            $onchange = 'onchange="' . $onchange . '"';

        $CODE = '
	 <div class="radio-inline ' . $class . '"><label><input type="radio" value="' . $value . '" name="' . $name . '" id="' . $name . '" ' . $checked . ' ' . $onchange . ' ' . $add_option . '>' . $caption . '<i class="fa fa-circle-o small"></i></label></div>
	 ';
        return $CODE;
    }

    /**
     * Прорисовка текста
     * @param string $value текст
     * @param string $float float
     * @param string $style имя стиля css
     * @return string
     */
    function setText($value, $float = "left", $style = false) {
        $CODE = '<div style="float:' . $float . ';padding:' . $this->padding . 'px;' . $style . '">' . $value . '</div>';
        return $CODE;
    }

    /**
     * Прорисовка элемента image
     * @param string $src адрес изображения
     * @param int $width ширина
     * @param int $height высота
     * @param string $align align
     * @param Int $hspace hspace
     * @param string $style имя стиля css
     * @return string
     */
    function setImage($src, $width, $height, $align = 'absmiddle', $hspace = "5", $style = false, $onclick = false, $alt = false, $class = false) {
        if (!empty($width))
            $width = 'width="' . $width . '"';
        if (!empty($height))
            $height = 'height="' . $height . '"';
        $CODE = '<img src="' . $src . '" ' . $width . ' ' . $height . ' alt="' . $alt . '" title="' . $alt . '" border="0" align="' . $align . '" hspace="' . $hspace . '" style="' . $style . '" onclick="' . $onclick . '" class="' . $class . '">';
        return $CODE;
    }

    /**
     * Прорисовка ссылки
     * @param string $href адрес ссылки
     * @param string $caption текст ссылки
     * @param string $target target
     * @param string $style имя стиля css
     * @return string
     */
    function setLink($href, $caption, $target = '_blank', $style = false, $title = false, $class = false) {
        if (empty($title))
            $title = $caption;
        $CODE = '<a href="' . $href . '" target="' . $target . '" title="' . $title . '" style="' . $style . '" class="' . $class . '">' . $caption . '</a>';
        return $CODE;
    }

    /**
     * Сообщение об ошибке
     * @param string $name имя ошибки
     * @param string $action описание ошибки
     */
    function setError($name, $action) {
        $this->_CODE.='<p><span style="color:red">Ошибка обработчика события: </span> <strong>' . $name . '()</strong>
	 <br><em>' . $action . '</em></p>';
    }

    /**
     * Прорисовка элемента Iframe
     * @param string $name имя
     * @param string $src адрес
     * @param int $width width
     * @param int $height height
     * @param string $float float
     * @return string
     */
    function setFrame($name, $src, $width, $height, $float = 'none', $border = 1, $scrolling = 'yes') {
        $CODE = '<iframe src="' . $src . '" height="' . $this->chekSize($height) . '" width="' . $this->chekSize($width) . '" scrolling="' . $scrolling . '" frameborder="' . $border . '" name="' . $name . '" id="' . $name . '" style="margin:' . $this->margin . 'px;background-color:#ffffff;float:' . $float . '"></iframe>';
        return $CODE;
    }

    /**
     * Назначение экшена
     * @param string $name переменная для анализа
     * @param string $function имя функции php обработчика
     */
    function setAction($name, $function) {

        // Раскодирование для AJAX запроса с русскими символами
        if (!empty($_POST)) {

            foreach ($_POST as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $kk => $vv) {
                        if (is_array($vv)) {
                            $v = str_replace('+', '&#43;', $vv); // + fix
                            $_POST[$kk] = @array_map("urldecode", $vv);
                        } else {
                            $v = str_replace('+', '&#43;', $v); // + fix
                            $_POST[$k] = @array_map("urldecode", $v);
                        }
                    }
                } else {
                    $v = str_replace('+', '&#43;', $v); // + fix
                    $_POST[$k] = urldecode($v);
                }
            }
        }

        if (!empty($name)) {
            if (function_exists($function)) {
                $action = call_user_func($function);
                if (!$action)
                    $this->setError($function, $action);
                else {

                    // Результат в виде JSON
                    if (is_array($action)) {
                        exit(json_encode($action));
                        return true;
                    }
                    else
                        $this->Compile();
                }
            }
            else
                $this->setError($function, "function do not exists");
        }
    }

    /**
     * Назначение загрузчика
     * @param string $name переменная для анализа (выполнятетя, если переменная не определена)
     * @param string $function имя функции php обработчика
     */
    function setLoader($name, $function) {
        if (empty($name))
            if (function_exists($function)) {
                $action = call_user_func($function);
                if (!$action)
                    $this->setError($function, $action);
                else {
                    $this->Compile();
                }
            }
            else
                $this->setError($function, "function do not exists");
    }

    /**
     * Прорисовка элемента Table
     * @return string
     */
    function setTable() {
        $td = '';
        $Arg = func_get_args();
        foreach ($Arg as $val) {
            $td.='<td valign="top">' . $val . '</td>';
        }
        $CODE = '<table id="gui-' . strtolower(__FUNCTION__) . '"><tr>' . $td . '</tr></table>';
        return $CODE;
    }

    /**
     * Проверка на экшен
     */
    function getAction() {
        global $PHPShopBase;
        if (!empty($_REQUEST['actionList']) and is_array($_REQUEST['actionList'])) {

            foreach ($_REQUEST['actionList'] as $action => $function)
                if (isset($_REQUEST[$action])) {

                    // Проверка прав пользователя
                    if (strpos($function, '.')) {
                        $function_array = explode(".", $function);
                        $function_name = $function_array[0];

                        // Права на редактирование
                        $rule_path = $function_array[1];
                        $rule_do = $function_array[2];

                        if ($PHPShopBase->Rule->CheckedRules($rule_path, $rule_do))
                            $this->setAction($action, $function_name);
                        else {
                            // JSON
                            if (isset($_REQUEST['ajax'])) {
                                exit(json_encode(array("success" => false)));
                            }
                            else // ALERT
                                return $PHPShopBase->Rule->BadUserFormaWindow();
                        }
                    }
                    else
                        $this->setAction($action, $function);
                }
        }
    }

    /**
     * Прорисовка элемента Button
     * @param string $value значение
     * @param string $img иконка
     * @param string $class класс
     * @param string $option допоолнительнеы данные для атрибута data-option
     * @return string
     */
    function setButton($value, $img, $class = null, $option = false) {
        $CODE = '
	 <button class="btn btn-default btn-sm ' . $class . '" data-option="' . $option . '" type="button">
     <span class="glyphicon glyphicon-' . $img . '"></span> 
     ' . $value . '
     </button>
	 ';
        return $CODE;
    }

    /**
     * Прорисовка пробельных отступов
     * @param Int $count количество пробелов
     * @return type
     */
    function set_($count = 1) {
        $i = 0;
        $disp = null;
        while ($i < $count) {
            $disp.='&nbsp;';
            $i++;
        }
        return '<span style="float:left;">' . $disp . '</span>';
    }

    /*
     * Прорисовка заголовка
     * Deprecated
     */

    function setHeader() {
        return false;
    }

    /**
     * Прорисовка формы истории изменений
     * @return string 
     */
    function setHistory() {
        /*
          $PHPShopInterface = new PHPShopInterface();
          $PHPShopInterface->window = true;
          $PHPShopInterface->imgPath = "../../../admpanel/img/";
          $PHPShopInterface->setCaption(array('Дата', "20%"), array('Изменения в модуле', "80%"));

          // История изменений
          $db = readDatabase("../install/module.xml", "update");
          if (is_array($db)) {
          foreach ($db as $update) {
          $PHPShopInterface->setRow(1, $update['date'], $update['content']);
          }
          return $PHPShopInterface->Compile();
          } */
    }

    /**
     * Прорисовка формы о модуле
     * @param bool $serial сериный ключ [false]
     * @param bool $pay форма оплаты [false]
     * @param string $version номер версии модуля
     * @param bool $update проверка обновлений
     * @return string
     */
    function setPay($serial = false, $pay = false, $version = false, $update = false) {
        global $PHPShopModules;

        $mes = null;
        $path = $PHPShopModules->path;
        PHPShopObj::loadClass("date");

        /*
          if (!empty($path)) {
          $data = $PHPShopModules->checkKeyBase();
          if ($data) {
          $this->TrialOff = true;

          if (!$PHPShopModules->checkKey($serial, $path))
          $mes = '<br>Срок ознакомительного периода до <b>' . PHPShopDate::dataV($data, false) . '</b>';
          }
          } */


        $CODE = '<table class="table table-striped table-bordered">
               <tr>
                  <th>Название</th>
                  <th>Версия</th>
                  <th>Описание</th>
               </tr>';


        // Описание модуля
        $db = $PHPShopModules->getXml("../modules/" . $path . "/install/module.xml");
        if ($db['version'] > $version and !empty($update)) {
            PHPShopObj::loadClass('text');
            $version_info = $this->setAlert('Версия ядра ' . $db['version'] . ' не соответствует версии БД ' . $version, 'warning');
            $version_info.=$this->setInput("submit", "modupdate", "Обновить модуль", "center", null, "", "btn-sm pull-right", "actionBaseUpdate");
        }
        else
            $version_info = $db['version'] . $status;

        if (!empty($db['status']))
            $status = ' <span class="label label-default">' . $db['status'] . '</span>';
        else
            $status = null;

        $CODE.='<tr>
                  <td>' . $db['name'] . '</td>
                  <td>' . $version_info . '</td>
                  <td>' . $db['description'] . $mes . '</td>
               </tr>
               </table>';

        if (empty($db['reformal']))
            $db['reformal'] = $path;

        // Reformal
        $CODE.=$this->setButton(__('Есть идея развития модуля ') . $db['name'], 'question-sign', 'idea', "http://idea.phpshop.ru/proj/?ia=" . $db['reformal']);

        if (!$pay)
            return $CODE;

        $CODE.=$this->setLine('<br>');

        if ($PHPShopModules->checkKey($serial, $path)) {
            $CODE.=$this->setField('Серийный номер', $this->setInputText('EEM-', 'serial_new', $serial, $size = 110, ' * Активация выполнена', false, 'serial_done'));
            $this->TrialOff = false;
            $PHPShopModules->setKeyBase($path);
            return $CODE;
        }

        $status_serial_img = null;
        $status_serial = null;

        if (!empty($serial)) {
            $status_serial = 'serial_fail';
            $status_serial_img = $this->setImage('error.gif', 16, 16, $align = 'absmiddle', 0);
        }

        $CODE.=$this->setField('Серийный номер', $this->setInputText('EEM-', 'serial_new', $serial, 110, $status_serial_img . ' * Формат серийного  номера: 11111-22222-33333', false, $status_serial));

        if (!$PHPShopModules->checkKey($serial, $path) and !empty($db['base']))
            $CODE.=$this->setButton("Купить лицензию " . $db['price'], '../../../admpanel/icon/key.png', 200, false, $float = "none", $onclick = "window.open('http://www.phpshop.ru/order/?bay_mod=" . $db['base'] . "')");

        return $CODE;
    }

}

/**
 * Библиотека табличных административных интерфейсов
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopGUI
 */
class PHPShopInterface extends PHPShopGUI {

    var $padding = 5;
    var $margin = 5;
    var $checkbox_action = true;
    var $sort_action = true;

    /**
     * Конструктор
     */
    function __construct($tab_pre = false) {
        $this->n = 1;
        $this->numRows = 0;

        // Учет вложенных закладок
        if ($tab_pre)
            $this->tab_pre = $tab_pre;
    }

    /**
     * Прорисовка элемента Fieldset с легендой
     * @param string $title заголовок легенды
     * @param string $content содержание
     * @param integer $size размер сетки описания поля 1-12
     * @param string $help справка
     * @param string $class css class
     * @return string
     */
    function setField($title, $content, $size = 1, $help = false, $class = false, $label = 'control-label', $max_size = 12) {

        if (!strpos($title, ':') and !empty($title))
            $title.=':';

        // Поддержка старых размеров
        $old_size = array(
            'none' => 1,
            'left' => 1,
            'right' => 1
        );
        if (is_string($size))
            $size = $old_size[$size];

        // Общая настройка
        if (!empty($this->field_col))
            $size = $this->field_col;

        // Подсказка
        if (!empty($help))
            $help = $this->setHelpIcon($help);

        // Несколко 
        if (is_array($title)) {
            
        } else {

            $CODE = '
     <div class="form-group form-group-sm">
        <label class="col-sm-' . intval($size) . ' ' . $label . ' ' . $class . '">' . $title . $help . '</label><div class="col-sm-' . ($max_size - intval($size)) . '">' . $content . '</div>
     </div>';
        }

        return $CODE;
    }

    /**
     * Компиляция результата
     * @return string
     */
    function Compile($cell_left = 1, $cell_right = 2) {
        $compile = $this->actionPanel;


        $cell = 12;
        if (!empty($this->sidebarLeft))
            $cell = $cell - $cell_left;

        if (!empty($this->sidebarRight))
            $cell = $cell - $cell_right;

        if ($this->sort_action)
            $id = 'data';
        else
            $id = null;

        $compile.='
        <div class="container row sidebarcontainer">
            ' . $this->sidebarLeft . '
            <div class="col-md-' . $cell . ' main">
            <table class="table table-hover " id="' . $id . '">
		' . $this->_CODE . '
                </tbody>
            </table>
            </div>
            ' . $this->sidebarRight . '
       </div>' . $this->includeJava . $this->includeCss;

        $compile.='
                      
';

        echo $compile;
    }

    function getContent() {
        return $this->_CODE;
    }

    /**
     * Прорисовка заголовка столбцов таблицы
     */
    function setCaption() {
        $Arg = func_get_args();
        $CODE = null;
        $option['align'] = 'left';


        foreach ($Arg as $key => $val) {

            $class = null;

            // Чекбокс для пакетой обработки
            if ($key == 0 && $this->checkbox_action) {
                $id = $val[0];
                $CODE.='<th width="' . $val[1] . '" class="sorting-hide hidden-xs"><input type="checkbox" value="all" id="select_all"></th>';
            } else {

                // Дополнительные настройки
                if (is_array($val[2])) {

                    // align
                    if (!empty($val[2]['align'])) {
                        $option['align'] = $val[2]['align'];
                    }

                    // tooltip
                    if (!empty($val[2]['tooltip'])) {
                        $tooltip = 'data-toggle="tooltip" data-placement="top" title="' . $val[2]['tooltip'] . '"';
                    }
                    else
                        $tooltip = null;

                    // sort
                    if (!empty($val[2]['sort']) && $val[2]['sort'] == 'none') {
                        $class = 'sorting-hide';
                    }

                    // class
                    if (!empty($val[2]['class'])) {
                        $class.= 'sorting-hide';
                    }

                    // вывод 
                    if (isset($val[2]['view']) and $val[2]['view'] == 0) {
                        continue;
                    }
                }

                if (empty($val[0]))
                    $class = 'sorting-hide';


                $CODE.='<th width="' . $val[1] . '" class="text-' . $option['align'] . ' ' . $class . '" ' . $tooltip . '>' . $val[0] . '</th>';
            }
        }

        $this->_CODE.='<thead><tr>' . $CODE . '</tr>
            </thead>
         <tbody>
       ';
    }

    /**
     * Прорисовка ячеек таблицы
     */
    function setRow() {
        $CODE = null;
        $Arg = func_get_args();


        foreach ($Arg as $key => $val) {

            // Чекбокс для пакетой обработки
            if ($key == 0 && $this->checkbox_action) {
                $id = $val;
                $CODE.='<td class="hidden-xs"><input type="checkbox" value="' . $val . '" name="items" data-id="' . $val . '"><span class="data-row-order">' . $key . '</span><span class="hide">' . $val . '</span></td>';
            } else {
                // Дополнительные настройки
                if (is_array($val)) {

                    // вывод 
                    if (isset($val['view']) and $val['view'] == 0) {
                        continue;
                    }

                    // Ссылка
                    if (isset($val['link'])) {

                        if (!empty($val['popover'])) {
                            $popover = 'data-toggle="popover" title="' . $val['popover-title'] . '" data-content="' . $val['popover'] . '"';
                        }
                        else
                            $popover = null;

                        if (!empty($val['modal']))
                            $modal = 'data-toggle="modal" data-target="' . $val['modal'] . '"';
                        else
                            $modal = null;

                        if (empty($val['name']))
                            $val['name'] = '#' . $id;

                        if (empty($val['target']))
                            $val['target'] = '_self';

                        $row = '<a href="' . $val['link'] . '" ' . $popover . ' ' . $modal . ' class="' . $val['class'] . '" target="' . $val['target'] . '">' . $val['name'] . '</a>' . $val['addon'];
                    }
                    else
                        $row = $val['name'];

                    // id
                    if (!empty($val['id']))
                        $id = $val['id'];

                    // Статус
                    if (isset($val['status'])) {
                        $row = $this->setDropdown(array_merge(array('id' => $id), $val['status']), 'dropdown', $val['status']['align'], $val['status']['passive']);
                        $val['align'] = $val['status']['align'];
                    }

                    // Action
                    if (is_array($val['action'])) {
                        $row = $this->setDropdownAction($val['action']);
                    }

                    // Class
                    if (empty($val['class'])) {
                        $val['class'] = null;
                    }

                    // align
                    if (empty($val['align'])) {
                        $val['align'] = 'left';
                    }

                    // Color
                    if (!empty($val['color'])) {
                        $val['color'] = ';color:' . $val['color'];
                    }
                    else
                        $val['color'] = null;


                    // editable
                    if (!empty($val['editable'])) {
                        $row = '<input style="width:100%' . $val['color'] . '" data-id="' . $id . '" class="editable input-hidden form-control input-sm" data-edit="' . $val['editable'] . '" value="' . $row . '"><span class="hide">' . $row . '</span>';
                    }

                    // order
                    if (!empty($val['order']))
                        $order = ' data-order="' . $val['order'] . '" ';
                    else
                        $order = null;

                    // search
                    if (!empty($val['search']))
                        $order.= ' data-search="' . $val['search'] . '" ';

                    $CODE.='<td style="text-align:' . $val['align'] . '" class="' . $val['class'] . '" ' . $order . '>' . $row . '</td>';
                } else {
                    $CODE.='<td>' . $val . '</td>';
                }
            }
        }


        $this->_CODE.='<tr class="data-row" data-row="' . $this->numRows . '">' . $CODE . '</tr>';
        $this->numRows++;
        $this->n++;
    }

    /**
     * Назначение кнопки создания новой позиции
     * @param string $link ссылка на новое окно
     */
    function setAddItem($link) {
        $this->_CODE_ADD_BUTTON = '
<span class="pull-right">
<a class="btn btn-default " href="' . $link . '"><span class="glyphicon glyphicon-plus-sign"></span> Добавить запись</a>
</span>
	 ';
    }

    /**
     * Прорисовка иконки вкл/./выкл
     * @param bool $flag вкл
     * @return string
     */
    function icon($flag) {
        if (empty($flag))
            $imgchek = '<span class="fa fa-toggle-off"></span>';
        else
            $imgchek = '<span class="fa fa-toggle-on"></span>';
        return $imgchek;
    }

}

/**
 * Библиотека внешних интерфейсов
 * @author PHPShop Software
 * @package PHPShopGUI
 */
class PHPShopFrontInterface extends PHPShopInterface {

    var $css;
    var $js;

    /**
     * Компиляция результата
     * @return string
     */
    function Compile($id = false, $form = false, $style = false) {

        if ($this->numRows > 10 and !$this->razmer)
            $this->razmer = "height:450px;";

        if (!empty($this->css))
            $compile.='<LINK href="' . $this->css . '" type="text/css" rel="stylesheet">';
        if (!empty($this->js))
            $compile.='<SCRIPT language="JavaScript" src="' . $this->js . '"></SCRIPT>';

        $compile.='<div style="' . $this->razmer . ';overflow:auto;">
	       <table cellpadding="0" class="phpshop-gui" cellspacing="1" border="0">' . $this->_CODE . '</table></div>';

        return $compile;
    }

    /**
     * Выдача содержимого
     * @return string
     */
    function getContent() {
        return $this->_CODE;
    }

}

?>