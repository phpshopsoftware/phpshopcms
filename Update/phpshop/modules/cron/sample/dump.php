<?php
/**
 * Дампер для запуска задач через PHPShop.Cron
 * Для включения поменяйте значение enabled на true
 */

// Включение
$enabled=true;

// Авторизация
if(empty($enabled)) exit("Ошибка авторизации!");

$_classPath="../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");

/**
 * Резервная копия БД
 * @param string $dbname имя БД
 * @param bool $structure_only только структуру
 */
function mysqlbackup($dbname,$structure_only=false,$pattern_table=false) {
	global $link_db;

    $crlf = '
';

    //$con=@mysql_connect($host,$uid, $pwd) or die("Could not connect");
    //$db=@mysql_select_db($dbname,$con) or die("Could not select db");
    // mysql_query("SET NAMES 'cp1251'");
    
    
    // here we check MySQL Version
    $result = @mysqli_query($link_db,"SELECT VERSION() AS version");
    if ($result != FALSE && @mysqli_num_rows($result) > 0) {
        $row = @mysqli_fetch_array($result);
        $match = explode('.', $row['version']);
    } else {
        $result = @mysqli_query($link_db,"SHOW VARIABLES LIKE \'version\'");
        if ($result != FALSE && @mysqli_num_rows($result) > 0) {
            $row = @mysqli_fetch_row($result);
            $match = explode('.', $row[1]);
        }
    }

    if (!isset($match) || !isset($match[0])) {
        $match[0] = 3;
    }
    if (!isset($match[1])) {
        $match[1] = 21;
    }
    if (!isset($match[2])) {
        $match[2] = 0;
    }
    if (!isset($row)) {
        $row = '3.21.0';
    }


    define('MYSQL_INT_VERSION', (int) sprintf('%d%02d%02d', $match[0], $match[1], intval($match[2])));
    define('MYSQL_STR_VERSION', $row['version']);
    unset($match);

    //$sql = "# MySQL dump by phpMyDump".$crlf;
    //$sql.= "# Host: $host Database: $dbname".$crlf;
    //$sql.= "#----------------------------".$crlf;
    //$sql.= "# Server version: ".MYSQL_STR_VERSION.$crlf;
    //$sql.= $crlf.$crlf.$crlf;

    
    //$res = @mysql_list_tables($dbname);
	$res = mysqli_query($link_db,"SHOW TABLES FROM ".$dbname);
    $nt = mysqli_num_rows($res);

    for ($a = 0; $a < $nt; $a++) {
        $row = mysqli_fetch_row($res);
        $tablename = $row[0];
        
        
        if($pattern_table)
            if(!in_array($tablename,$pattern_table))
                continue;

        //$sql=$crlf."# ----------------------------------------".$crlf."# table structure for table '$tablename' ".$crlf;
        $sql = "DROP TABLE IF EXISTS $tablename;" . $crlf;
        // For MySQL < 3.23.20
        if (MYSQL_INT_VERSION >= 32321) {

            $result = mysqli_query($link_db,"SHOW CREATE TABLE $tablename");
            if ($result != FALSE && mysqli_num_rows($result) > 0) {
                $tmpres = mysqli_fetch_array($result);
                $pos = strpos($tmpres[1], ' (');
                $tmpres[1] = substr($tmpres[1], 0, 13)
                        . $tmpres[0]
                        . substr($tmpres[1], $pos);

                $sql .= $tmpres[1] . ";" . $crlf . $crlf;
            }
            mysqli_free_result($result);
        } else {
            $sql.="CREATE TABLE $tablename(" . $crlf;
            $result = mysqli_query($link_db,"show fields from $tablename", $con);

            while ($row = mysqli_fetch_array($result)) {
                $sql .= "  " . $row['Field'];
                $sql .= ' ' . $row['Type'];
                if (isset($row['Default']) && $row['Default'] != '') {
                    $sql .= ' DEFAULT \'' . $row['Default'] . '\'';
                }
                if ($row['Null'] != 'YES') {
                    $sql .= ' NOT NULL';
                }
                if ($row['Extra'] != '') {
                    $sql .= ' ' . $row['Extra'];
                }
                $sql .= "," . $crlf;
            }

            mysqli_free_result($result);
            //$sql = preg_replace('/,' . $crlf . '/$', '', $sql);

            $result = mysqli_query($link_db,"SHOW KEYS FROM $tablename");
            while ($row = mysqli_fetch_array($result)) {
                $ISkeyname = $row['Key_name'];
                $IScomment = (isset($row['Comment'])) ? $row['Comment'] : '';
                $ISsub_part = (isset($row['Sub_part'])) ? $row['Sub_part'] : '';
                if ($ISkeyname != 'PRIMARY' && $row['Non_unique'] == 0) {
                    $ISkeyname = "UNIQUE|$kname";
                }
                if ($IScomment == 'FULLTEXT') {
                    $ISkeyname = 'FULLTEXT|$kname';
                }
                if (!isset($index[$ISkeyname])) {
                    $index[$ISkeyname] = array();
                }
                if ($ISsub_part > 1) {
                    $index[$ISkeyname][] = $row['Column_name'] . '(' . $ISsub_part . ')';
                } else {
                    $index[$ISkeyname][] = $row['Column_name'];
                }
            }
            mysqli_free_result($result);

            while (list($x, $columns) = @each($index)) {
                $sql .= "," . $crlf;
                if ($x == 'PRIMARY') {
                    $sql .= '  PRIMARY KEY (';
                } else if (substr($x, 0, 6) == 'UNIQUE') {
                    $sql .= '  UNIQUE ' . substr($x, 7) . ' (';
                } else if (substr($x, 0, 8) == 'FULLTEXT') {
                    $sql .= '  FULLTEXT ' . substr($x, 9) . ' (';
                } else {
                    $sql .= '  KEY ' . $x . ' (';
                }
                $sql .= implode($columns, ', ') . ')';
            }
            $sql .= $crlf . ");" . $crlf . $crlf;
        }
        out(1, $sql);
        if ($structure_only == FALSE) {
            // here we get table content
            $result = mysqli_query($link_db,"SELECT * FROM  $tablename");
            $fields_cnt = mysqli_num_fields($result);
            while ($row = mysqli_fetch_row($result)) {
                $table_list = '(';
                for ($j = 0; $j < $fields_cnt; $j++) {
					$finfo =  mysqli_fetch_field_direct($result, $j);
                    $table_list .= $finfo->name . ', ';
                }
                $table_list = substr($table_list, 0, -2);
                $table_list .= ')';

                $sql = 'INSERT INTO ' . $tablename
                        . ' VALUES (';
                for ($j = 0; $j < $fields_cnt; $j++) {
                    if (!isset($row[$j])) {
                        $sql .= ' NULL, ';
                    } else if ($row[$j] == '0' || $row[$j] != '') {
                        $finfo =  mysqli_fetch_field_direct($result, $j);
                        $type = $finfo->type;
                        // a number
                        if ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' || $type == 'int' ||
                                $type == 'bigint' || $type == 'timestamp') {
                            $sql .= $row[$j] . ', ';
                        }
                        // a string
                        else {
                            $dummy = '';
                            $srcstr = $row[$j];
                            for ($xx = 0; $xx < strlen($srcstr); $xx++) {
                                $yy = strlen($dummy);
                                if ($srcstr[$xx] == '\\')
                                    $dummy .= '\\\\';
                                if ($srcstr[$xx] == '\'')
                                    $dummy .= '\\\'';
                                if ($srcstr[$xx] == "\x00")
                                    $dummy .= '\0';
                                if ($srcstr[$xx] == "\x0a")
                                    $dummy .= '\n';
                                if ($srcstr[$xx] == "\x0d")
                                    $dummy .= '\r';
                                if ($srcstr[$xx] == "\x1a")
                                    $dummy .= '\Z';
                                if (strlen($dummy) == $yy)
                                    $dummy .= $srcstr[$xx];
                            }
                            $sql .= "'" . $dummy . "', ";
                        }
                    } else {
                        $sql .= "'', ";
                    } // end if
                } // end for
                $sql = preg_replace('/, $/', '', $sql);
                $sql .= ");" . $crlf;

                out(1, $sql);
            }
            mysqli_free_result($result);
        }
    }


    return;
}

function define_crlf() {
    global $HTTP_USER_AGENT;
    $ucrlf = "\n";
    if (strstr($HTTP_USER_AGENT, 'Win')) {
        $ucrlf = "\r\n";
    }
    else if (strstr($HTTP_USER_AGENT, 'Mac')) {
        $ucrlf = "\r";
    }
    else {
        $ucrlf = "\n";
    }
    return $ucrlf;
} 

// Пишем GZIP файлы
function gzcompressfile($source,$level=false) {
    $dest=$source.'.gz';
    $mode='wb'.$level;
    $error=false;
    if($fp_out=gzopen($dest,$mode)) {
        if($fp_in=fopen($source,'rb')) {
            while(!feof($fp_in))
                gzwrite($fp_out,fread($fp_in,1024*512));
            fclose($fp_in);
        }
        else $error=true;
        gzclose($fp_out);
        unlink($source);
        rename($dest, $source.'.gz');
    }
    else $error=true;
    if($error) return false;
    else return $dest;
}

ob_start();
/*
echo '#SKD101|'.$dbname.'|62|2015.09.02 19:34:11|12647|1|1|14|10922|106|922|54|8|418|2|2|3|4|7|1|1|1|1|1|1|1|1|1|1|1|10|6|1|5|20|2|8|56|1|3|1|1|3|1|2|36|11|1|1|3
    
';*/

mysqlbackup($GLOBALS['SysValue']['connect']['dbase']);
$content=ob_get_clean();


$file="base_".date("d_m_y_His").".sql";
@$fp = fopen($_classPath."admpanel/dumper/backup/".$file, "w+");
if ($fp) {
    fputs($fp, $content);
    fclose($fp);
    $sorce=$_classPath."admpanel/dumper/backup/".$file;
}

gzcompressfile($sorce);
echo "Архивная копия БД создана.";

//print the result
function out($fptr,$s) {
    echo $s;
} 


?>