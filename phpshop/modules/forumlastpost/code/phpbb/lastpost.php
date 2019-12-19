<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
    <head>
        <title>Форум PHPBB</title>
        <META http-equiv="Content-Type" content="text-html; charset=windows-1251">
        <style>
            td {
                font-size: 12px;
                color: #696969;
            }
            a{
                color: #35A0C4;
                padding-left: 0px;
            }

            .post{
                color: #f04a35;
                font-weight: bolder;
            }
        </style>
    </head>

    <body>

        <?

        if(!@include("../config.php")) exit('config.php не обнаружен');
        $link_db=mysqli_connect ($dbhost, $dbuser, $dbpasswd ) or die("Невозможно подсоединиться к базе");
        mysqli_select_db($link_db,$dbname) or die("Невозможно подсоединиться к базе");
        mysqli_query($link_db,"SET NAMES 'cp1251'");

        function dataV($nowtime) {
            $Months = array("01"=>"января","02"=>"февраля","03"=>"марта",
                    "04"=>"апреля","05"=>"мая","06"=>"июня", "07"=>"июля",
                    "08"=>"августа","09"=>"сентября",  "10"=>"октября",
                    "11"=>"ноября","12"=>"декабря");
            $curDateM = date("m",$nowtime);
            $t=date("d",$nowtime)."-".$curDateM."-".date("y",$nowtime)." ".date("H:s ",$nowtime);
            return $t;
        }


        if(empty($_GET['n'])) $limit=7;
        else $limit=htmlspecialchars(stripslashes($_GET['n']));


        $sql="select * from ".$table_prefix."topics order by topic_last_poster_id desc limit ".$limit;
        $result=mysqli_query($link_db,$sql);
        while($row = mysqli_fetch_array($result)) {
            $name = $row['topic_title'];
            $last_post = $row['topic_last_post_time'];
            $last_poster_name = $row['topic_last_poster_name'];
            $posts = $row['topic_replies'];
            $last_id=$row['forum_id'];
            $id = $row['topic_id'];
            @$disp.='
<TR><TD width="20" class="post">
<img src="comment.gif" alt="" border="0"> '.$posts.'</TD>
<TD>'.dataV($last_post).'  |  <img src="icon-client.gif" alt="" border="0"> <b>'.$last_poster_name.'</b><BR>
<DIV><A title="'.$name.'" href="/viewforum.php?f='.$last_id.'" target="_blank">'.$name.'</A>
</DIV></TD>
</TR>
';
        }



        echo "<table>$disp</table>";

        ?>
    </body>
</html>