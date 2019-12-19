<?php

/**
 * ���������� ��� ������ � �������
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 */
class PHPShopFile {

    /**
     * ����� �� ������ �����
     * @param string $file ��� �����
     */
    static function chmod($file, $error = false, $rul = 0775) {
        if (function_exists('chmod')) {
            if (@chmod($file, $rul))
                return true;
            elseif ($error)
                return '��� ����� ' . $file;
        }
        elseif ($error)
            return __FUNCTION__ . '() ���������';
    }

    /**
     * ������ ������ � ����
     * @param string $file ���� �� �����
     * @param string $csv ������ ��� ������
     * @param string $type �������� ������
     * @param bool $error ����� ������
     */
    static function write($file, $csv, $type = 'w+', $error = false) {
        $fp = @fopen($file, $type);
        if ($fp) {
            //stream_set_write_buffer($fp, 0);
            fputs($fp, $csv);
            fclose($fp);
            return true;
        } elseif ($error)
            echo '��� ����� ' . $file;
    }

    /**
     * ������ ������ � csv ����
     * ������������ ����������� ������� php fputcsv
     * @param string $file ���� �� �����
     * @param array $csv ������ ��� ������
     * @param bool $error ����� ������
     */
    static function writeCsv($file, $csv, $error = false) {
        $fp = @fopen($file, "w+");
        if ($fp) {
            foreach ($csv as $value) {
                fputcsv($fp, $value, ';', '"');
            }
            //stream_set_write_buffer($fp, 0);
            fclose($fp);
        } elseif ($error)
            echo '��� ����� ' . $file;
    }

    /**
     * ������ CSV �����
     * @param string $file ����� �����
     * @param string $function ��� ������� ����������� 
     * @param string $delim �����������
     */
    static function readCsv($file, $function, $delim=';') {
        $fp = @fopen($file, "r");
        while (($data = @fgetcsv($fp, 10000,  $delim)) !== FALSE) {
            call_user_func($function, $data);
        }
        fclose($fp);
    }

    /**
     * GZIP ��������� �����
     * @param string $source ���� �� �����
     * @param int $level ������� ������
     * @return bool
     */
    static function gzcompressfile($source, $level = false) {
        $dest = $source . '.gz';
        $mode = 'wb' . $level;
        $error = false;
        if ($fp_out = @gzopen($dest, $mode)) {
            if ($fp_in = @fopen($source, 'rb')) {
                while (!feof($fp_in))
                    gzwrite($fp_out, fread($fp_in, 1024 * 512));
                fclose($fp_in);
            }
            else
                $error = true;
            gzclose($fp_out);
            unlink($source);
            //rename($dest, $source . '.bz2');
        }
        else
            $error = true;
        if ($error)
            return false;
        else
            return $dest;
    }

    /**
     * ����� ������ 
     * @param string $dir �����
     * @param string $function ������� ���������
     * @param bool $return ������� ���������� ������ ��������� ����
     * @return mixed
     */
    static function searchFile($dir, $function,$return = false) {
        $user_func_result = null;
        if (is_dir($dir))
            if (@$dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if($file != '.' and $file != '..'){
                    $user_func_result.=call_user_func_array($function, array($file));
					if($return)  return $user_func_result;
					}
                }

                return $user_func_result;
                closedir($dh);
            }
    }

}

?>