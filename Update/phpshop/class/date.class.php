<?php

/**
 * Библиотека работы с датами
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 * @subpackage Helper
 */
class PHPShopDate {

    /**
     * Преобразование даты из Unix к строковый вид. 
     * Синоним PHPShopDate::dataV()
     * @return string 
     */
    static function get($nowtime = false, $full = false, $revers = false, $delim = '-', $months_enabled = false) {
        return PHPShopDate::dataV($nowtime, $full, $revers, $delim, $months_enabled);
    }

    /**
     * Преобразование даты из Unix к строковый вид.
     * @param int $nowtime формат даты в Unix
     * @param bool $full вывод часов и минут
     * @param bool $revers обратная строка даты
     * @return string
     */
    static function dataV($nowtime = false, $full = true, $revers = false, $delim = '-', $months_enabled = false) {

        if (empty($nowtime))
            $nowtime = date("U");

        $Months = array("01" => "января", "02" => "февраля", "03" => "марта",
            "04" => "апреля", "05" => "мая", "06" => "июня", "07" => "июля",
            "08" => "августа", "09" => "сентября", "10" => "октября",
            "11" => "ноября", "12" => "декабря");
        $d_array = array(
            'y' => date("Y", $nowtime),
            'm' => date("m", $nowtime),
            'd' => date("d", $nowtime),
            'h' => date("h:i", $nowtime)
        );

        if ($months_enabled)
            $d_array['m'] = $Months[$d_array['m']];

        if (!empty($revers))
            $time = $d_array['y'] . $delim . $d_array['m'] . $delim . $d_array['d'];
        else
            $time = $d_array['d'] . $delim . $d_array['m'] . $delim . $d_array['y'];

        if (!empty($full))
            $time.=" " . $d_array['h'];

        return $time;
    }

    /**
     * Преобразование даты из строкового вида в Unix
     * @param string $data дата в формате строки
     * @param string $delim разделитель даты [-] или [.]
     * @return <type>
     */
    static function GetUnixTime($data, $delim = '-') {
        $array = explode($delim, $data);
        return @mktime(12, 0, 0, $array[1], $array[0], $array[2]);
    }

}

?>