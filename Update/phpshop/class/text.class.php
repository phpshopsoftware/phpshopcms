<?php

/**
 * ���������� ����������� ������
 * @version 1.4
 * @package PHPShopClass
 * @subpackage Helper
 */
class PHPShopText {

    /**
     * ������ &nbsp
     * @param int $n ���������� ��������
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
     * ������ �����
     * @param string $string �����
     * @param string $style �����
     * @return string
     */
    static function b($string, $style = false) {
        return '<b style="' . $style . '">' . $string . '</b>';
    }

    /**
     * ����������
     * @param string $string �����
     * @param string $icon ������
     * @param string $size ������ ������
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
     * ���������
     * @param string $string �����
     * @param string $icon ������
     * @param string $size ������ ������
     * @param string $color ���� ������
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
     * �����������
     * @param string $src ����������
     * @param int $hspace �������������� ������
     * @param string $align ������������
     * @param string $css �����
     * @return string
     */
    static function img($src, $hspace = 5, $align = 'left', $css=null ) {
        return '<img src="' . $src . '" hspace="' . $hspace . '" align="' . $align . '" border="0" style="'. $css.'">';
    }

    /**
     * ������� ������
     * @return string
     */
    static function br() {
        return '<br>';
    }

    /**
     * ������
     * @param string $href ������
     * @param string $text �����
     * @param string $title ��������
     * @param string $color ����
     * @param string $size ������
     * @param string $target ������
     * @param string $class �����
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
     * �����
     * @param string $name ���
     * @return string
     */
    static function slide($name) {
        return '<a name="' . $name . '"></a>';
    }

    /**
     * ��������� H1
     * @param string $string �����
     * @return string
     */
    static function h1($string) {
        return '<h1>' . $string . '</h1>';
    }

    /**
     * ��������� H2
     * @param string $string �����
     * @return string
     */
    static function h2($string) {
        return '<h2>' . $string . '</h2>';
    }

    /**
     * ��������� H3
     * @param string $string �����
     * @return string
     */
    static function h3($string) {
        return '<h3>' . $string . '</h3>';
    }

    /**
     * ������
     * @param string $string �����
     * @param string $class css
     * @return string
     */
    static function ul($string,$class='list-group') {
        return '<ul class="'.$class.'">' . $string . '</ul>';
    }

    /**
     * ������������ ������
     * @param string $string �����
     * @param string $type ���
     * @return string
     */
    static function ol($string, $type = null) {
        return '<ol type="' . $type . '">' . $string . '</ol>';
    }

    /**
     * ������� ������
     * @param string $string �����
     * @param string $href ������
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
     * ��������� ���� TR
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
     * ���������� ������
     * <code>
     * // example:
     * $value[]=array('��� ����� 1',123,'selected');
     * $value[]=array('��� ����� 2',456,false);
     * PHPShopText::select('my',$value,100);
     * </code>
     * @param string $name ���
     * @param array $value ���������� � ���� �������
     * @param int $width ������
     * @param string $float float
     * @param string $caption ����� ����� ���������
     * @param string $onchange ��� javascript ������� �� ������ onchange
     * @param int $height ������
     * @param int $size ������
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
     * ������ ������� TD
     * @param string $string �����
     * @param string $class �����
     * @param string $colspan �������
     * @param string $id ��
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
     * ��������� ������� TH
     * @param string $string �����
     * @return string
     */
    static function th($string) {
        return '<th>' . $string . '</th>';
    }

    /**
     * ���� DIV
     * @param string $string �����
     * @param string $align ������������
     * @param string $style �����
     * @param string $id ��
     * @param string $class �����
     * @return string
     */
    static function div($string, $align = "left", $style = false, $id = false, $class = false) {
        if(empty($id)) $id=time();
        if(empty($class)) $class=__CLASS__.'-'.__FUNCTION__;
        return '<div  id="' . $id . '" style="text-align:'.$align.';' . $style . '" class="' . $class . '">' . $string . '</div>';
    }

    /**
     * ����������� �����
     * @param string $string �����
     * @return string
     */
    static function strike($string) {
        
        // ���� �����
        if(strstr($string," ")){
            $string_array=explode(" ",$string);
            return '<span style="text-decoration: line-through">' . $string_array[0] . '</span><span class="rubznak">'.$string_array[1].'</span>';
        }
        else return '<span style="text-decoration: line-through">' . $string . '</span>';
    }

    /**
     * �����������
     * @param string $type [<] ��� [>]
     * @return string
     */
    static function comment($type = '<') {
        if ($type == '<')
            return '<!--';
        else
            return '-->';
    }

    /**
     * �����
     * @param string $string �����
     * @param string $style �����
     * @return string
     */
    static function p($string = '<br>', $style = false) {
        if(empty($style)) $style=__CLASS__.'-'.__FUNCTION__;
        return '<p style="' . $style . '">' . $string . '</p>';
    }

    /**
     * ������
     * @param string $value �����
     * @param string $onclick JS ������� �� �����
     * @param string $class �����
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
     * �������
     * @param string $content ����������
     * @param string $cellpadding cellpadding
     * @param string $cellspacing cellspacing
     * @param string $align ������������
     * @param string $width �����
     * @param string $bgcolor ���
     * @param string $border ������
     * @param string $id ��
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
     * �����
     * @param string $content ����������
     * @param string $name ���, ��
     * @param string $method ����� ��������
     * @param string $action ���� ��������
     * @param string $target ��� �������� [null|_blank]
     * @return string
     */
    static function form($content, $name, $method = 'post', $action = '', $target = '_self') {
        return '<form action="' . $action . '" target="' . $target . '" name="' . $name . '" id="' . $name . '" method="' . $method . '">' . $content . '</form>';
    }

    /**
     * Input
     * @param string $type ��� [text,password,button � �.�]
     * @param string $name ���
     * @param mixed $value ��������
     * @param int $float float
     * @param int $size ������
     * @param string $onclick ����� �� �����, ��� javascript �������
     * @param string $class ��� ������ �����
     * @param string $caption ����� ����� ���������
     * @param string $description ����� ����� ��������
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
     * ���� ��� ����� ������
     * @param string $caption ����� ����� ���������
     * @param string $name ���
     * @param mixed $value ��������
     * @param int $size ������
     * @param string $description ����� ����� ��������
     * @param string $float  float
     * @param string $class ��� ������ �����
     * @return string
     */
    static function setInputText($caption, $name, $value, $size = 300, $description = false, $float = "none", $class = false) {
        return PHPShopText::setInput('text', $name, $value, $float, $size, false, $class, $caption, $description);
    }

}

?>