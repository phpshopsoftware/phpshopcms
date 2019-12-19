<?php

// Функция удаления
function actionDelete() {
    global $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    if (PHPShopSecurity::true_skin($_POST['file']))
        $action = @unlink("./dumper/backup/" . $_POST['file']);
    else
        $action = false;


    return array("success" => $action);
}

// Обработка событий
$PHPShopGUI->getAction();

?>