<?php

/**
 * Библиотека офорфмления текста
 * @version 1.4
 * @package PHPShopClass
 * @subpackage Helper
 */
class PHPShopText {

    /**
     * Пробел &nbsp
     * @param int $n количество пробелов
     * @return string
     */
    static function nbsp($n = 1) {
        $i = 0;
        $nbsp = null;
        while ($i < $n) {
            $nbsp.='&nbsp;';
            $i++;
        }
        return $nbsp;
    }

    /**
     * Жирный текст
     * @param string $string текст
     * @param string $style стиль
     * @return string
     */
    static function b($string, $style = false) {
        return '<b style="' . $style . '">' . $string . '</b>';
    }

    /**
     * Оповещение
     * @param string $string текст
     * @param string $icon иконка
     * @param string $size размер текста
     * @return string
     */
    static function notice($string, $icon = false, $size = false) {
        if (!empty($icon))
            $img = PHPShopText::img($icon);
        else
            $img = null;
        return $img . '<span style="color:red;font-size:' . $size . '">' . $string . '</span>';
    }

    /**
     * Сообщение
     * @param string $string текст
     * @param string $icon иконка
     * @param string $size размер текста
     * @param string $color цвет текста
     * @return string
     */
    static function message($string, $icon = false, $size = false, $color = 'green') {
        if (!empty($icon))
            $img = PHPShopText::img($icon);
        else
            $img = null;
        return $img . '<span style="color:' . $color . ';font-size:' . $size . '">' . $string . '</span>';
    }

    /**
     * Изображение
     * @param string $src изобржение
     * @param int $hspace горизонтальный отсутп
     * @param string $align выравнивание
     * @param string $css стили
     * @return string
     */
    static function img($src, $hspace = 5, $align = 'left', $css=null ) {
        return '<img src="' . $src . '" hspace="' . $hspace . '" align="' . $align . '" border="0" style="'. $css.'">';
    }

    /**
     * Перевод строки
     * @return string
     */
    static function br() {
        return '<br>';
    }

    /**
     * Ссылка
     * @param string $href ссылка
     * @param string $text текст
     * @param string $title описание
     * @param string $color цвет
     * @param string $size размер
     * @param string $target ссылка
     * @param string $class класс
     * @return string
     */
    static function a($href, $text, $title = false, $color = false, $size = false, $target = false, $class = false) {
        //$style = 'text-decoration:underline;';
        if ($size)
            $style.='font-size:' . $size . 'px;';
        if ($color)
            $style.='color:' . $color;
        if (empty($title))
            $title = $text;

		if ($title)
			$title = ' title="' . $title . '" ';
		if ($target)
			$target = ' target="' . $target . '" ';
		if ($class)
			$class = ' class="' . $class . '" ';
		if ($style)
			$style = ' style="' . $style . '" ';
        return '<a href="' . $href . '"' . $title . $target . $class . $style . '>' . $text . '</a>';
    }

    /**
     * Якорь
     * @param string $name имя
     * @return string
     */
    static function slide($name) {
        return '<a name="' . $name . '"></a>';
    }

    /**
     * Заголовок H1
     * @param string $string текст
     * @return string
     */
    static function h1($string) {
        return '<h1>' . $string . '</h1>';
    }

    /**
     * Заголовок H2
     * @param string $string текст
     * @return string
     */
    static function h2($string) {
        return '<h2>' . $string . '</h2>';
    }

    /**
     * Заголовок H3
     * @param string $string текст
     * @return string
     */
    static function h3($string) {
        return '<h3>' . $string . '</h3>';
    }

    /**
     * Список
     * @param string $string текст
     * @param string $class css
     * @return string
     */
    static function ul($string,$class='list-group') {
        return '<ul class="'.$class.'">' . $string . '</ul>';
    }

    /**
     * Нумерованный список
     * @param string $string текст
     * @param string $type тип
     * @return string
     */
    static function ol($string, $type = null) {
        return '<ol type="' . $type . '">' . $string . '</ol>';
    }

    /**
     * Элемент списка
     * @param string $string текст
     * @param string $href ссылка
     * @param string $class css
     * @return string
     */
    static function li($string, $href = null, $class="list-group-item") {
        if (!empty($href)) {
            $text = PHPShopText::a($href, $string);
            $li = '<li class="'.$class.'">' . $text . '</li>';
        }
        else
            $li = '<li class="'.$class.'">' . $string . '</li>';
        return $li;
    }

    /**
     * Генератор слоя TR
     * @return string
     */
    static function tr() {
        $Arg = func_get_args();
        $tr = '<tr class=tablerow>';
        foreach ($Arg as $val) {
            $tr.=PHPShopText::td($val, 'tablerow');
        }
        $tr.='</tr>';
        return $tr;
    }

    /**
     * Выпадающий список
     * <code>
     * // example:
     * $value[]=array('моя цифра 1',123,'selected');
     * $value[]=array('моя цифра 2',456,false);
     * PHPShopText::select('my',$value,100);
     * </code>
     * @param string $name имя
     * @param array $value значенение в виде массива
     * @param int $width ширина
     * @param string $float float
     * @param string $caption текст перед элементом
     * @param string $onchange имя javascript функции по экшену onchange
     * @param int $height высота
     * @param int $size размер
     * @return string
     */
    static function select($name, $value, $width, $float = "none", $caption = false, $onchange = "return true", $height = false, $size = 1, $id = false, $class="form-control selectpicker show-menu-arrow") {

        if (empty($id))
            $id = $name;

		if ($name)
			$name = ' name="' . $name . '"';

		if ($id)
			$id = ' id="' . $id . '"';

		if ($size)
			$size = ' size="' . $size . '"';

		if ($onchange)
			$onchange = ' onchange="' . $onchange . '"';
                
                if($class)
                    $class = ' class="'.$class.'"';
			

        $select = $caption . ' <select' . $name . $id . $size . ' style="float:' . $float . ';width:' . $width . 'px;height:' . $height . 'px"' . $onchange . $class.'>';
        if (is_array($value))
            foreach ($value as $val)
                $select.='<option value="' . $val[1] . '" ' . @$val[2] . '>' . $val[0] . '</option>';
        $select.='</select>';
        return $select;
    }

    /**
     * Ячейка таблицы TD
     * @param string $string текст
     * @param string $class класс
     * @param string $colspan колспан
     * @param string $id ид
     * @return string
     */
    static function td($string, $class = false, $colspan = false, $id = false) {
		if($class)
			$class = ' class="' . $class . '"';
		if($id)
			$id = ' id="' . $id . '"';
		if($colspan)
			$colspan = ' colspan="' . $colspan . '"';
		
        return '<td' . $class . $id . $colspan . '>' . $string . '</td>';
    }

    /**
     * Заголовок таблицы TH
     * @param string $string текст
     * @return string
     */
    static function th($string) {
        return '<th>' . $string . '</th>';
    }

    /**
     * Блок DIV
     * @param string $string текст
     * @param string $align выравнивание
     * @param string $style стиль
     * @param string $id ид
     * @param string $class класс
     * @return string
     */
    static function div($string, $align = "left", $style = false, $id = false, $class = false) {
        if(empty($id)) $id=time();
        if(empty($class)) $class=__CLASS__.'-'.__FUNCTION__;
        return '<div  id="' . $id . '" style="text-align:'.$align.';' . $style . '" class="' . $class . '">' . $string . '</div>';
    }

    /**
     * Зачеркнутый текст
     * @param string $string текст
     * @return string
     */
    static function strike($string) {
        
        // Знак рубля
        if(strstr($string," ")){
            $string_array=explode(" ",$string);
            return '<span style="text-decoration: line-through">' . $string_array[0] . '</span><span class="rubznak">'.$string_array[1].'</span>';
        }
        else return '<span style="text-decoration: line-through">' . $string . '</span>';
    }

    /**
     * Комментарий
     * @param string $type [<] или [>]
     * @return string
     */
    static function comment($type = '<') {
        if ($type == '<')
            return '<!--';
        else
            return '-->';
    }

    /**
     * Абзац
     * @param string $string текст
     * @param string $style стиль
     * @return string
     */
    static function p($string = '<br>', $style = false) {
        if(empty($style)) $style=__CLASS__.'-'.__FUNCTION__;
        return '<p style="' . $style . '">' . $string . '</p>';
    }

    /**
     * Кнопка
     * @param string $value текст
     * @param string $onclick JS функция по клику
     * @param string $class класс
     * @return string
     */
    static function button($value, $onclick, $class = 'ok', $id=false) {

		if ($value)
			$value = ' value="' . $value . '"';

		if ($id)
			$id = ' id="' . $id . '"';

		if ($class)
			$class = ' class="' . $class . '"';

		if ($onclick)
			$onclick = ' onclick="' . $onclick . '"';
			
        return '<input type="button"' . $value . $onclick . $class . $id.'>';
    }

    /**
     * Таблица
     * @param string $content содержание
     * @param string $cellpadding cellpadding
     * @param string $cellspacing cellspacing
     * @param string $align выравнивание
     * @param string $width длина
     * @param string $bgcolor фон
     * @param string $border бордюр
     * @param string $id ид
     * @return string
     */
    static function table($content, $cellpadding = 3, $cellspacing = 1, $align = 'center', $width = '98%', $bgcolor = false, $border = 0, $id = false, $class=false) {
		if($cellpadding)
			$cellpadding = ' cellpadding="' . $cellpadding . '"';
		if($cellspacing)
			$cellspacing = ' cellspacing="' . $cellspacing . '"';
		if($align)
			$align = 'text-align:' . $align . ';';
		if($width)
			$width = 'width:' . $width . ';';
		if($bgcolor)
			$bgcolor = 'background:' . $bgcolor . ';';
		if($border)
			$border = 'border:' . $border . 'px;';
		if($id)
			$id = ' id="' . $id . '"';
		if($class)
			$class = ' class="' . $class . '"';
		
		$style = ' style="' . $align . $width . $bgcolor . $border . '"';
		
        return '<table ' . $id . $style . $class . '>' . $content . '</table>';
    }

    /**
     * Форма
     * @param string $content содержание
     * @param string $name имя, ид
     * @param string $method метод передачи
     * @param string $action цель передачи
     * @param string $target тип перехода [null|_blank]
     * @return string
     */
    static function form($content, $name, $method = 'post', $action = '', $target = '_self') {
        return '<form action="' . $action . '" target="' . $target . '" name="' . $name . '" id="' . $name . '" method="' . $method . '">' . $content . '</form>';
    }

    /**
     * Input
     * @param string $type тип [text,password,button и т.д]
     * @param string $name имя
     * @param mixed $value значение
     * @param int $float float
     * @param int $size размер
     * @param string $onclick экшен по клику, имя javascript функции
     * @param string $class имя класса стиля
     * @param string $caption текст перед элементом
     * @param string $description текст после элемента
     * @return string
     */
    static function setInput($type, $name, $value, $float = "none", $size = 200, $onclick = "return true", $class = false, $caption = false, $description = false) {
        
        if(!empty($onclick))
            $onclick='onclick="'.$onclick.'"';

        $input = ' <input type="' . $type . '" value="' . $value . '" name="' . $name . '" id="' . $name . '" '.$onclick.'> ';

        if ($type != 'hidden')
            $input='<div style="float:' . $float . $padding . '">
             <label>' . $caption . $input . $description . '</label></div>';

        return $input;
    }

    /**
     * Поле для ввода текста
     * @param string $caption текст перед элементом
     * @param string $name имя
     * @param mixed $value значение
     * @param int $size размер
     * @param string $description текст после элемента
     * @param string $float  float
     * @param string $class имя класса стиля
     * @return string
     */
    static function setInputText($caption, $name, $value, $size = 300, $description = false, $float = "none", $class = false) {
        return PHPShopText::setInput('text', $name, $value, $float, $size, false, $class, $caption, $description);
    }

}

?>