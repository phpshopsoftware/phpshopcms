<?php

/**
 * ���������� �������������� ������
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 */
class PHPShopRestore extends PHPShopUpdate {

    var $_restore_path = '../../';
    var $_restore_version;

    function __construct() {
        parent::__construct();
    }

    /*
     *  �������� ������������� ������
     */

    function checkRestore($version) {
        if (file_exists($this->_backup_path . 'backups/' . intval($version) . '/files.zip')) {
            $this->_restore_version = $version;
            return true;
        }
    }

    /**
     *  �������������� ����
     */
    function restoreBD() {
        global $PHPShopGUI;

        if (file_exists($this->_backup_path . 'backups/' . $this->_restore_version . '/restore.sql', 'dumper/backup/restore.sql')) {

            if (!copy($this->_backup_path . 'backups/' . $this->_restore_version . '/restore.sql', 'dumper/backup/restore.sql')) {
                $this->log("�� ������ ����������� �������������� ���� � backup/backups/" . $this->_restore_version . '/restore.sql', 'warning', 'remove');
                return false;
            }

            $this->_log.=$PHPShopGUI->setProgress(__('�������������� ���� ������...'), 'install-restore-bd');
            $this->log("�������������� ���� ������ ���������", 'success hide install-restore-bd');
            $this->log("�� ������� ��������������� ���� ������", 'danger hide install-restore-bd-danger');
        }
    }

    /**
     *  �������������� ����� �� ������
     */
    function restoreFiles() {

        // ����� �� ��������� �����
        $this->chmod("phpshop/inc/config.ini", $this->_user_ftp_chmod);

        $this->installFiles('backups/' . $this->_restore_version . '/files.zip', $status = '��������������', $this->_restore_path);

        // ����� �� ��������� �����
        $this->chmod("phpshop/inc/config.ini", $this->_user_ftp_re_chmod);
    }

    /**
     *  �������������� �������. ��������� ������.
     */
    function restoreConfig() {
        $config['upload']['version'] = $this->_restore_version;
        $this->installConfig($config);
    }

}

/**
 * ���������� ���������� ������
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 */
class PHPShopUpdate {

    /**
     * ����� ������ � ������� ����� php. ������� ����� �� ��������������.
     * @var bool 
     */
    var $local_update = true;
    var $_endPoint;
    var $_log;
    var $_timeLimit = 600;
    var $_backup_path = '../../backup/';
    var $_test_file = 'index.php';
    var $base_update_enabled = false;
    var $_user_ftp_dir;
    var $_user_ftp_chmod;
    var $_user_ftp_re_chmod;

    function __construct() {

        include_once('../lib/zip/pclzip.lib.php');

        $this->path = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/';

        $this->_user_ftp_host = $GLOBALS['SysValue']['user_ftp']['host'];
        $this->_user_ftp_login = $GLOBALS['SysValue']['user_ftp']['login'];
        $this->_user_ftp_password = $GLOBALS['SysValue']['user_ftp']['password'];
        $this->_user_ftp_chmod = $GLOBALS['SysValue']['user_ftp']['chmod'];
        $this->_user_ftp_re_chmod = $GLOBALS['SysValue']['user_ftp']['re_chmod'];

        $this->_endPoint = $_SERVER['SERVER_NAME'];
        $this->_user_ftp_chmod = $GLOBALS['SysValue']['user_ftp']['chmod'];

        if ($this->islocal())
            $this->_user_ftp_dir = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'];
        else
            $this->_user_ftp_dir = $GLOBALS['SysValue']['user_ftp']['dir'];


        // ��������� ������ ���������� ���������
        if (function_exists('error_reporting')) {
            error_reporting('E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT');
        }

        set_time_limit($this->_timeLimit);
    }

    /**
     *  ��������� ������.
     */
    function islocal() {
        if ($this->local_update or ($_SERVER["SERVER_ADDR"] == "127.0.0.1" and getenv("COMSPEC")))
            return true;
    }

    /**
     * ��������� ���� �� �����
     */
    function chmod($path, $chmod) {

        if ($this->islocal()) {
            return true;
        } else {

            if (ftp_site($this->user_ftp_stream, "CHMOD " . $chmod . " " . $this->_user_ftp_dir . '/' . $path))
                return true;
            else
                return false;
        }
    }

    /**
     *  �������� �����
     */
    function mkdir($path) {

        if ($this->islocal()) {
            if (mkdir($this->path . $path))
                return true;
            else
                return false;
        } else {

            if (ftp_mkdir($this->user_ftp_stream, $this->_user_ftp_dir . '/' . $path)) {
                return true;
            }
            else
                return false;
        }
    }

    /**
     *  �������� �����
     */
    function delete($path = null) {

        if (!$path)
            return false;

        if ($this->islocal()) {
            if (@unlink($this->path . $path))
                return true;
            else
                return false;
        } else {

            if (ftp_delete($this->user_ftp_stream, $this->_user_ftp_dir . '/' . $path))
                return true;
            else
                return false;
        }
    }

    /**
     *  �������� ������ � zip 
     */
    function isReady() {

        if (!$this->islocal()) {

            if (!$this->user_ftp_stream = ftp_connect($this->_user_ftp_host)) {
                $this->log('�� ������ ��������� � �������� ' . $this->_user_ftp_host, 'warning', 'remove');
                return false;
            }

            if (!ftp_login($this->user_ftp_stream, $this->_user_ftp_login, $this->_user_ftp_password)) {
                $this->log("������ ����������� � �������� " . $this->_user_ftp_host, 'warning', 'remove');
                return false;
            }

            if (!ftp_pasv($this->user_ftp_stream, true)) {
                $this->log("���������� ���������� ��������� ����� � �������� " . $this->_user_ftp_host, 'warning', 'remove');
                return false;
            }

            if (!empty($this->_user_ftp_dir) and !ftp_chdir($this->user_ftp_stream, $this->_user_ftp_dir)) {
                $this->log("�� ������ ����� ��������� ����� " . $this->_user_ftp_dir, 'warning', 'remove');
                return false;
            }

            // ����� ������ �� �������� �����
            //$this->chmod(null, $this->_user_ftp_chmod);

            $archive = new PclZip($this->path . 'test_update.zip');
            $v_list = $archive->add($this->path . $this->_test_file, PCLZIP_OPT_REMOVE_PATH, $this->path);

            if ($v_list == 0) {
                $this->log('�� ������ ������� ���� ��� ������������ Zip ����������, ��� ���� �� ��������� ����� � ������. ����������� ������� <a href="http://phpshop.ru/loads/files/setup.exe" target="_blank" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-cloud-download"></span> Updater.exe</a> ��� ������ � ������������ PHPShop.', 'warning', 'remove');
                return false;
            }

            if (!$this->delete('test_update.zip')) {
                $this->log("�� ������� ������� ���� ��� ������������ Zip ����������. ���������� ����� ������ ���������� �� ����� ���� ���������!", 'danger', 'remove');
                return false;
            }
            else
                return true;
        }
        else
            return true;
    }

    /**
     *  ���������� ������
     */
    function installFiles($file = 'temp/update.zip', $status = '����������', $path = '../../') {

        $archive = new PclZip($this->_backup_path . $file);
        if ($archive->extract(PCLZIP_OPT_PATH, $path, PCLZIP_CB_PRE_EXTRACT, 'preExtractCallBack')) {
            $this->log("����� " . $status . " �����������");
            return true;
        } else {
            $this->log("�� ������ ����������� ����� " . $status, 'warning', 'remove');
            return false;
        }

        // ���������� ����� �� �������� �����
        //$this->chmod(null, $this->_user_ftp_re_chmod);
    }

    /**
     * ������� ��������� ������ /temp/
     */
    function cleanTemp() {

        $this->delete('backup/temp/config_update.txt');
        $this->delete('backup/temp/upd_conf.txt');
        $this->delete('backup/temp/update.sql');
        $this->delete('backup/temp/restore.sql');
        $this->delete('backup/temp/upload_backup.sql.gz');

        if ($this->delete('backup/temp/update.zip'))
            $this->log("��������� ����� ���������� �������");
        else
            $this->log("�� ������ ������� ��������� �����", 'warning', 'remove');
    }

    /**
     * ���������� ��
     */
    function installBD() {
        global $PHPShopGUI;

        if (file_exists("dumper/backup/update.sql")) {
            $this->_log.=$PHPShopGUI->setProgress(__('���������� ���� ������...'), 'install-update-bd');
            $this->log("���������� ���� ������ ���������", 'success hide install-update-bd');
            $this->log("�� ������� �������� ���� ������", 'danger hide install-update-bd-danger');
            @unlink("dumper/backup/update.sql");
            return false;
        }
    }

    /**
     * ���������� config.ini
     */
    function installConfig($config = false) {
        global $PHPShopBase;

        // ����� �� ��������� �����
        $this->chmod("phpshop/inc/config.ini", $this->_user_ftp_chmod);

        if (!is_array($config))
            $config = parse_ini_file_true($this->_backup_path . "temp/config_update.txt", 1);

        $SysValue = parse_ini_file_true($PHPShopBase->iniPath, 1);

        // ����� config.ini
        if (is_array($config)) {
            foreach ($config as $k => $v) {
                if (is_array($config[$k])) {
                    foreach ($config[$k] as $key => $value) {
                        $SysValue[$k][$key] = $value;
                    }
                }
            }
        }


        $s = null;

        if (is_array($SysValue))
            foreach ($SysValue as $k => $v) {

                $s .="[$k]\n";
                foreach ($v as $key => $val) {
                    if (!is_array($val))
                        $s .= "$key = \"$val\";\n";
                }

                $s .= "\n";
            }

        if (!empty($s)) {
            if ($f = fopen($this->path . "phpshop/inc/config.ini", "w")) {

                if (!empty($s) and strstr($s, 'phpshop')) {
                    fwrite($f, $s);
                    $this->log("���������������� ���� ��������");
                }

                fclose($f);
            }
            else
                $this->log("�� ������ �������� ���� ������������ phpshop/inc/config.ini. ��� ���� �� ��������� �����.", 'warning', 'remove');
        }
        else
            $this->log("�� ������ �������� ���� ������������ phpshop/inc/config.ini. ������ �������� �����.", 'warning', 'remove');


        // �������������� ����
        $this->chmod("phpshop/inc/config.ini", $this->_user_ftp_re_chmod);
    }

    /**
     *  �������� ��������� ����� ������
     */
    function backupFiles() {

        // �������� �����
        $this->mkdir('backup/backups/' . $GLOBALS['SysValue']['upload']['version']);

        // ����� �� �����
        $this->chmod($this->_user_ftp_dir . '/backup/backups/' . $GLOBALS['SysValue']['upload']['version'], $this->_user_ftp_chmod);

        if ($this->base_update_enabled and !copy($this->_backup_path . "temp/upload_backup.sql.gz", $this->_backup_path . 'backups/' . $GLOBALS['SysValue']['upload']['version'] . '/base.sql.gz')) {
            copy($this->_backup_path . "temp/restore.sql", $this->_backup_path . 'backups/' . $GLOBALS['SysValue']['upload']['version'] . '/restore.sql');
            $this->log("�� ������ ����������� ����� ���� � backup/backups/" . $GLOBALS['SysValue']['upload']['version'], 'warning', 'remove');
        }

        if ($this->base_update_enabled)
            copy($this->_backup_path . "temp/restore.sql", $this->_backup_path . 'backups/' . $GLOBALS['SysValue']['upload']['version'] . '/restore.sql');


        $archive = new PclZip($this->_backup_path . 'backups/' . $GLOBALS['SysValue']['upload']['version'] . '/files.zip');
        $map = parse_ini_file_true($this->_backup_path . "temp/upd_conf.txt", 1);
        $zip_files = null;

        if (is_array($map)) {
            foreach ($map as $k => $v) {

                if (!empty($v['files'])) {

                    if (strstr($v['files'], ';')) {
                        $files = explode(";", $v['files']);

                        if (is_array($files)) {
                            foreach ($files as $file) {
                                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/' . $k . '/' . $file))
                                    $zip_files.= $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/' . $k . '/' . $file . ',';
                            }
                        }
                    }
                    elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/' . $k . '/' . $v['files']))
                        $zip_files.= $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/' . $k . '/' . $v['files'] . ',';
                }
            }
        }

        if (!empty($zip_files)) {
            $v_list = $archive->create($zip_files, PCLZIP_OPT_REMOVE_PATH, $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/');
            if ($v_list == 0) {
                $this->log("�� ������ ������� ����� ������ ����� �����������. Error : " . $archive->errorInfo(true), 'warning', 'remove');
                return false;
            }

            $this->log("��������� ����� ������ �������");

            // ����� �� �����
            $this->chmod($this->_user_ftp_dir . '/backup/backups/' . $GLOBALS['SysValue']['upload']['version'], $this->_user_ftp_re_chmod);
        }
    }

    /**
     * ������ ����� ����������
     */
    function map() {

        // ���������� �� ������������
        if ($this->base_update_enabled) {

            /*
              if (!file_exists("dumper/backup/upload_dump.sql.gz")) {
              $this->log("�� ������� ��������� ����� ���� ������", 'warning', 'remove');
              return false;
              }

              if (!copy("dumper/backup/upload_dump.sql.gz", $this->_backup_path . "temp/upload_backup.sql.gz")) {
              $this->log("�� ������ ����������� ����� ���� upload_backup.sql.gz", 'warning', 'remove');
              return false;
              }

              if (!unlink("dumper/backup/upload_dump.sql.gz")) {
              $this->log("�� ������ ������� upload_backup.sql", 'warning', 'info');
              }
             */

            if (!copy($this->_backup_path . "temp/update.sql", "dumper/backup/update.sql")) {
                $this->log("�� ������ ����������� ���������� ���� ������ update.sql", 'warning', 'remove');
                return false;
            }
        }


        // ������ ���� ������� �������
        if (!$this->map = parse_ini_file_true($this->_backup_path . "temp/upd_conf.txt", 1)) {
            $this->log("�� ������ �������� ������ ������� ����������");
            return false;
        }
    }

    /**
     * �������� ���������� � FTP
     */
    function ftpConnect() {

        if (!$this->ftp_stream = ftp_connect($this->ftp_host)) {
            $this->log('�� ������ ��������� � ��������', 'warning', 'remove');
            return false;
        }

        if (!ftp_login($this->ftp_stream, $this->ftp_login, $this->ftp_password)) {
            $this->log("������ �����������", 'warning', 'remove');
            return false;
        }

        if (!ftp_pasv($this->ftp_stream, true)) {
            $this->log("���������� ���������� ��������� �����", 'warning', 'remove');
            return false;
        }

        if (!ftp_chdir($this->ftp_stream, $this->ftp_folder)) {
            $this->log("�� ������ ����� ��������� ������ ���������� " . intval($this->ftp_folder), 'warning', 'remove');
            return false;
        }

        if (!ftp_get($this->ftp_stream, $this->path . '/backup/temp/upd_conf.txt', 'upd_conf.txt', FTP_BINARY)) {
            $this->log("������ �������� ����� ������������ ����������", 'warning', 'remove');
            return false;
        }

        if (@ftp_get($this->ftp_stream, $this->path . '/backup/temp/update.sql', 'update.sql', FTP_BINARY)) {
            $this->log("�������� ���� ���������� ���� ������. ��������� ���������� ���� ������.");
            ftp_get($this->ftp_stream, $this->path . '/backup/temp/restore.sql', 'restore.sql', FTP_BINARY);
            $this->base_update_enabled = true;
        }


        if (ftp_get($this->ftp_stream, $this->path . '/backup/temp/config_update.txt', 'config_update.txt', FTP_BINARY)) {
            $this->log("�������� ���������������� ����");
        }

        if (ftp_get($this->ftp_stream, $this->path . '/backup/temp/update.zip', 'update.zip', FTP_BINARY)) {
            $this->log("�������� ����� ������ ��� ����������");
        }

        $this->log("�������� ����� ������������ ���������� �������� ���������");
    }

    /**
     *  �������� ������� ����������
     */
    function checkUpdate() {

        $update_enable = xml2array(UPDATE_PATH, "update", true);
        if ($update_enable) {
            $this->update_status = $update_enable['status'];
            $this->version = $update_enable['name'];
            if ($this->update_status != 'no_update') {

                $this->ftp_host = $update_enable['ftp_host'];
                $this->ftp_login = $update_enable['ftp_login'];
                $this->ftp_password = $update_enable['ftp_password'];
                $this->ftp_folder = $update_enable['os'] . "/" . $update_enable['num'];

                $this->content = $update_enable['content']['item'];

                $this->btn_class = 'btn btn-primary btn-sm navbar-btn update-start';
            } elseif ($update_enable['status'] == 'passive') {
                $this->btn_class = 'btn btn-default btn-sm navbar-btn disabled';
            } elseif ($update_enable['status'] == 'no_update') {
                $this->btn_class = 'btn btn-default btn-sm navbar-btn disabled';
            }
        }
        else
            $this->btn_class = 'hide';
    }

    /**
     * �������� ������ ��
     */
    function checkBD() {

        if (file_exists("dumper/backup/upload_dump.sql.gz")) {
            $this->log('����� ���� ������ ��������');
        }
        else
            $this->log('����� ���� ������ �� ��������', 'warning', 'remove');
    }

    function log($text, $class = 'success', $icon = 'ok') {
        $this->_log.='<div class="alert alert-' . $class . '" role="alert"><span class="glyphicon glyphicon-' . $icon . '-sign"></span> ' . $text . '</div>';
    }

    function fulllog($text) {
        $this->_fulllog.=$text . '<br>';
    }

    function getLog() {
        return $this->_log;
    }

}

/**
 * �������� ������ ����� �������.
 */
function preExtractCallBack($p_event, $p_header) {
    if ($p_header['folder'] != 1 and $_SERVER["SERVER_ADDR"] != "127.0.0.1" and !getenv("COMSPEC")) {
        unlink($p_header['filename']);
    }
    return 1;
}

?>