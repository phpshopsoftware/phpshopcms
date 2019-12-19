<?php

$modPath = "../modules/";


if (!empty($PHPShopBase) and $PHPShopBase->Rule->CheckedRules('modules', 'remove')) {

    PHPShopObj::loadClass('string');

    $success = false;

    $load = $_POST['url'];
    $path_parts = pathinfo($_POST['url']);
    $file_name = $path_parts['basename'];

    // �������� ������
    $time = explode(' ', microtime());
    $start_time = $time[1] + $time[0];

    if ($path_parts['extension'] == 'zip') {

        $Content = file_get_contents($load);
        if (!empty($Content)) {
            $zip = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . "/UserFiles/Files/" . $file_name;
            $handle = fopen($zip, "w+");
            fwrite($handle, $Content);
            fclose($handle);
            if (is_file($zip)) {

                // ������� �������� ��������
                @chmod($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . "/UserFiles/Files", 0775);

                // ���������� ZIP
                include($_classPath . "lib/zip/pclzip.lib.php");
                $archive = new PclZip($zip);
                if ($archive->extract(PCLZIP_OPT_PATH, $_classPath . "modules/")) {

                    @unlink($zip);

                    // ��������� ������
                    $time = explode(' ', microtime());
                    $seconds = ($time[1] + $time[0] - $start_time);
                    $seconds = substr($seconds, 0, 6);

                    $result = '������ ' . basename(basename($file_name, ".zip"), ".php") . ' ��������.';
                    $success = true;
                }
                else
                    $result = '������ ���������� ����� ' . $file_name . ', ��� ���� ������ � ����� phpshop/modules/';
            }
            else
                $result = '������ ������ ����� ' . $file_name . ', ��� ���� ������ � ����� /UserFiles/Files/';
        }
        else {
            $result = '������ ������ ����� ' . $file_name;
        }
    } else  $result = '�������� ������ ������ ������ ' . $file_name.', ��������� *.zip';

    // ���� ���������
    exit(json_encode(array("result" => PHPShopString::win_utf8(PHPShopSecurity::TotalClean($result)), 'success' => $success)));
}
?>