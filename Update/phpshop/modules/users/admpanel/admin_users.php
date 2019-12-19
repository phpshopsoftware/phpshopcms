<?

$TitlePage = "Зарегистрированные пользователи";

function actionStart() {
    global $PHPShopInterface, $_classPath;

    $PHPShopInterface->size = "530,530";
    $PHPShopInterface->link = "../modules/users/admpanel/adm_usersID.php";
    $PHPShopInterface->setCaption(array("&plusmn;", "5%"), array("Дата", "10%"), array("Пользователь", "20%"), array("Mail", "20%"), array("Дополнительно", "40%"));

    // Настройки модуля
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath . "modules/");


    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.users.users_base"));
    $PHPShopOrm->debug = false;
     $PHPShopOrm->Option['where']=' or ';

    if (!empty($_GET['search'])) {
        $where = array('login' => " LIKE '%" . $_GET['search'] . "%'",
            'mail' => " LIKE '%" . $_GET['search'] . "%'",
        );
        $limit=1000;
    } else {
        $where = false;
        $limit=100;
    }

    $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'id DESC'), array('limit' => $limit));
      
    if (is_array($data))
        foreach ($data as $row) {
            extract($row);

            // Дополнительные поля
            $content = unserialize($row['content']);
            $dop = null;

            if (is_array($content))
                foreach ($content as $k => $v) {
                    $name = str_replace('dop_', '', $k);
                    $dop.=$name . ': ' . $v . ',';
                }
            $dop = substr($dop, 0, strlen($dop) - 1);


            $PHPShopInterface->setRow($id, $PHPShopInterface->icon($enabled), $date, $login, $mail, $dop);
        }

    $PHPShopInterface->setSearch();
    $PHPShopInterface->Compile();
}

?>