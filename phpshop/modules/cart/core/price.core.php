<?php


class PHPShopPrice extends PHPShopCore {

    var $search_message;
    /**
     * @var array имена полей прайса для учета в поиске
     */
    var $search_target=array("name","art");
    /**
     * @var int кол-во вывода на страницу
     */
    var $search_limit;
    var $current_page=1;

    function __construct() {
        if(!empty($_REQUEST['p'])) $this->current_page=$_REQUEST['p'];
        $this->total=count($GLOBALS['_PRODUCT']);
        $this->search_limit=$GLOBALS['LoadItems']['modules']['cart']['num'];

        parent::PHPShopCore();

        // Навигация хлебные крошки
        $this->navigation(false,'Прайс-лист');
    }

    function searchforma() {

        // Проверяем запрос на безопасность
        if(!empty($_REQUEST['search']))
            $value=PHPShopSecurity::TotalClean($_REQUEST['search'],$flag=2);
        else $value='';
        $dis='<div><form method="get" class="form-inline">
        &nbsp;<input type="text" name="search" value="'.$value.'" class="form-control" placeholder="Поиск товара"> 
	<input type="submit" value="Найти"  title="Поиск" class="btn btn-primary"></form></div>';
        return $dis;
    }


    // Перевод в нижний регистр
    function lower($str) {
        if(function_exists('mb_strtolower')) $str =  mb_strtolower($str,'WINDOWS-1251');
        else $str = strtolower($str);
        return $str;
    }


    function search() {

        if(is_array($GLOBALS['_PRODUCT']))
        foreach($GLOBALS['_PRODUCT'] as $key => $value) {
            foreach($this->search_target as $found_row_name)
                if(strstr($this->lower($value[$found_row_name]),$this->lower($_REQUEST['search']))) $this->search[$key]=$value;
        }

        if(count($this->search)>0) {
            $GLOBALS['_PRODUCT']=$this->search;
            $this->search_message='Найдено совпадений: '.count($this->search);
            $this->enabled_search=true;
        }
        else {
            $this->search_message='Ничего не найдено';
            $this->enabled_search=false;
        }

    }

    function listcat($category) {


        $dis='<div style="float:left"><form class="form-inline"><SELECT name="category" id="category" class="form-control"><OPTION value="/price/">Все позиции</OPTION>';
        if(is_array($GLOBALS['_CATALOG']))
            foreach($GLOBALS['_CATALOG'] as $val) {
                if($val['id']==$category) $sel="selected"; else $sel='';
                $dis.='<OPTION value="/price/'.$val['id'].'/" '.$sel.'>'.$val['name'].'</OPTION>';
            }

        $dis.='</SELECT> <input type="button" value="Показать"  height="10"
                title="Показать" onclick="window.location.replace(document.getElementById(\'category\').value)" class="btn btn-default"></form></div>';
        return $dis;
    }

    function _($v,$des) {
        echo $des." - ".$v."<br>";
    }

    function paginatorforma() {
        $navigat='Страницы: ';
        $t=1;
        $num=$this->total/$this->search_limit;
        while ($t<$num+1) {
            if($t != $this->current_page) $navigat.='<a href="?p='.$t.'">'.$t.'</a> | ';
            else $navigat.="<b> $t</b> | ";
            $t++;
        }

        if($this->total>$this->search_limit)
            $navigat='<p>'.substr($navigat,0,strlen($navigat)-2).'</p>';
        else $navigat='';

        return $navigat;
    }

    function paginatorcheck($i) {


        $end=$this->current_page*$this->search_limit;
        $start=$end-$this->search_limit;


        if($i>=$start and $i<$end) return true;
    }

    // Учет параметра вывода товаров толькь=о при поиске
    function is_search() {
        if(!empty($GLOBALS['LoadItems']['modules']['cart']['enabled_search'])) {
            if(empty($_REQUEST['search'])) return false;
            elseif($this->enabled_search) return true;
            else return false;
        }
        else return true;
    }


    function index() {

        // Категория текущая
        $category=$GLOBALS['_CATALOG'][$this->PHPShopNav->getNav()];

        // Учет параметра вывода товаров токьо при поиске
        if(empty($GLOBALS['LoadItems']['modules']['cart']['enabled_search']))
            $dis=$this->listcat($category['id']);
        
        $dis.=$this->searchforma();



        // Имя каталога
        if($category)
            $dis.='<H2>'.$category['name'].'</H2>';
        else $dis.=$this->paginatorforma();

        if(!empty($_REQUEST['search'])) {
            $dis.=$this->search();
            $dis.='<p>'.$this->search_message.'</p>';
        }


        if($this->is_search()) {
            if(count($GLOBALS['_PRODUCT'])>0) {
                $dis.='<p><table class="table table-striped">
<tr bgColor="#F0F0F0">
    <td><strong>Артикул</strong></td>
	<td><strong>Наименование</strong></td>
	<td><strong>Цена</strong></td>
        <td><strong>Кол-во</strong></td>
	<td width="100"></td>
</tr>';



                foreach($GLOBALS['_PRODUCT'] as $key=>$val) {

                    // Если задан каталог
                    if($category and $val['catalog']==$category['id']) {

                        $dis.='<tr>
    <td>'.$val['art'].'</td>
	<td>'.$val['name'].'</td>
	<td>'.$val['price'].' '.$GLOBALS['LoadItems']['modules']['cart']['valuta'].'</td>
	<td width="40"><form method="get" action="./">
        <input type="text" name="num" class="input-sm value="1"></td>
	<td align="center">
        <input type="hidden" name="p" value="'.intval($_GET['p']).'">
	<input type="hidden" name="item" value="'.$key.'">
	<input type="submit" value="В корзину" title="Добавить 1 шт." class="btn btn-primary btn-sm">
	</form>
	</td>
</tr>';
                    }
                    elseif(!$category and $this->paginatorcheck($i)) {
                        $dis.='<tr>
    <td>'.$val['art'].'</td>
	<td>'.$val['name'].'</td>
	<td>'.$val['price'].' '.$GLOBALS['LoadItems']['modules']['cart']['valuta'].'</td>
        <td width="40"><form method="get" action="./">
        <input type="text" name="num" class="input-sm" value="1"></td>
	<td align="center">
	<input type="hidden" name="item" value="'.$key.'">
        <input type="hidden" name="p" value="'.intval($_GET['p']).'">
        <input type="hidden" name="search" value="'.$_REQUEST['search'].'">
	<input type="submit" value="В корзину"  height="10" title="Добавить" class="btn btn-primary btn-sm">
	</form>
	</td>
</tr>';
                    }
                    $i++;
                }

                $dis.='
</table>
</p>
<p><a href=".'.$GLOBALS['_ADMIN']['dir'].$GLOBALS['LoadItems']['modules']['cart']['filedir'].'" target="_blank" class="btn btn-warning">Скачать прайс</a>
</p>';


            }else $dis.='Нет данный в прайсе, загрузите прайс *.csv в '.$GLOBALS['_ADMIN']['dir'];
        }

        // Мета
        $this->title="Прайс-лист - ".$this->PHPShopSystem->getValue("name");

        // Определяем переменые
        $this->set('pageContent',$dis);
        $this->set('pageTitle','Прайс-лист');


        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));

    }

}

?>