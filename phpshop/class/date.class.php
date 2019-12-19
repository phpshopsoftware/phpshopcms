<?php

/**
 * ���������� ������ � ������
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 * @subpackage Helper
 */
class PHPShopDate {

    /**
     * �������������� ���� �� Unix � ��������� ���. 
     * ������� PHPShopDate::dataV()
     * @return string 
     */
    static function get($nowtime = false, $full = false, $revers = false, $delim = '-', $months_enabled = false) {
        return PHPShopDate::dataV($nowtime, $full, $revers, $delim, $months_enabled);
    }

    /**
     * �������������� ���� �� Unix � ��������� ���.
     * @param int $nowtime ������ ���� � Unix
     * @param bool $full ����� ����� � �����
     * @param bool $revers �������� ������ ����
     * @return string
     */
    static function dataV($nowtime = false, $full = true, $revers = false, $delim = '-', $months_enabled = false) {

        if (empty($nowtime))
            $nowtime = date("U");

        $Months = array("01" => "������", "02" => "�������", "03" => "�����",
            "04" => "������", "05" => "���", "06" => "����", "07" => "����",
            "08" => "�������", "09" => "��������", "10" => "�������",
            "11" => "������", "12" => "�������");
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
     * �������������� ���� �� ���������� ���� � Unix
     * @param string $data ���� � ������� ������
     * @param string $delim ����������� ���� [-] ��� [.]
     * @return <type>
     */
    static function GetUnixTime($data, $delim = '-') {
        $array = explode($delim, $data);
        return @mktime(12, 0, 0, $array[1], $array[0], $array[2]);
    }

}

?>