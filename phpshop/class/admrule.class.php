<?php

/**
 * Библиотека проверки прав администрирования
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 * @param string $Status права пользователя
 */
class PHPShopAdminRule {

    protected $UserStatus;

    /**
     * Конструктор
     */
    function __construct() {

        // Проверка авторизации
        $this->UserStatus = $this->ChekBase();

        $this->fixRules = array(
            'banner' => 'baner',
            'order' => 'visitor',
            'payment' => 'order',
            'catalog' => 'cat_prod',
            'slider' => 'baner',
            'report' => 'stats1',
            'menu' => 'page_menu',
            'page' => 'page_menu',
            'photo' => 'page',
            'rss' => 'rsschanels',
            'modules' => 'module',
            'system' => 'visitor',
            'exchange'=>'cat_prod',
            'sort' => 'catalog',
            'catpage' => 'page',
            'intro' => 'system',
            'upload'=>'update',
            'currency'=>'valuta',
            'tpleditor'=>'system',
            'metrica'=>'system'
            
        );
    }

    /**
     * Проверка авторизации
     * @return mixed
     */
    function ChekBase() {

        // Проверка сессии
        $session_id = session_id();
        if (!$session_id)
            session_start();

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['users']);
        $data = $PHPShopOrm->select(array('*'), array('enabled' => "='1'", 'id' => "='" . intval($_SESSION['idPHPSHOP']) . "'"), false, array('limit' => 1));

        if (is_array($data)) {
            $hasher = new PasswordHash(8, false);
            if ($_SESSION['logPHPSHOP'] == $data['login']) {
                if ($hasher->CheckPassword($_SESSION['pasPHPSHOP'], $data['password'])) {
                    return unserialize($data['status']);
                }
            }
        }

        if (!empty($_SERVER['QUERY_STRING']))
            $_SESSION['return'] = $_SERVER['QUERY_STRING'];

        header("Location: /phpshop/admpanel/");
        exit("No access");
    }

    /**
     * Проверка прав
     * @param string $path раздел администрирования [news|gbook]
     * @param string $do действие [view|edit|remove]
     * @return boolean 
     */
    function CheckedRules($path, $do = 'view') {
        
        // Заглушка !!!!
        if(!is_array($this->UserStatus))
        return true;

        $rules_array = array(
            'view' => 0,
            'edit' => 1,
            'create' => 2,
            'remove' => 3,
            'all' => 4,
            'rule' => 5
        );


        if (empty($this->UserStatus[$path]) and !empty($this->fixRules[$path]))
            $path = $this->fixRules[$path];

        $array = explode("-", $this->UserStatus[$path]);

        if (!empty($array[$rules_array[$do]]))
            return true;
    }

    /**
     * Собщение об отсутствии права
     */
    function BadUserFormaWindow() {
        echo'
          <div class="alert alert-danger" id="rules-message" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> <strong>Внимание!</strong> Недостаточно прав для выполнения. <a href="#" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-arrow-left"></span> Вернуться</a> или поменять <a href="?path=users&id=' . $_SESSION['idPHPSHOP'] . '&tab=1" class="btn btn-xs btn-primary">Права <span class="glyphicon glyphicon-arrow-right"></span></a> Администратора.</div>
';
        return true;
    }

}

?>
