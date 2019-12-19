<?

/**
 * ������� ������� �� �������
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopBrandsElement extends PHPShopElements {

    /**
     * @var int  ���-�� �������
     */
    var $limitOnLine = 5;
    var $firstClassName = 'span-first-child';

    /**
     * �����������
     */
    function __construct() {
        $this->debug = false;
        parent::__construct();
    }

    /**
     * ����� ��������� ��������
     * @return string
     */
    function index() {
        global $SysValue;
        // ������ ���� �������������
        $PHPShopOrm = new PHPShopOrm($SysValue['base']['table_name20']);
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->mysql_error = false;
        $result = $PHPShopOrm->query("select * from " . $SysValue['base']['table_name20'] . " where (brand='1' and goodoption!='1') order by num");
        while (@$row = mysqli_fetch_assoc($result)) {
            $arrayVendor[$row['id']] = $row;
        }
        if (is_array($arrayVendor))
            foreach ($arrayVendor as $k => $v) {
                if (is_numeric($k))
                    $sortValue.=' category=' . $k . ' OR';
            }
        $sortValue = substr($sortValue, 0, strlen($sortValue) - 2);

        if (!empty($sortValue)) {
            // ������ �������� 
            $i = 0;
            $result = $PHPShopOrm->query("select * from " . $SysValue['base']['table_name21'] . " where $sortValue order by num");
            while (@$row = mysqli_fetch_array($result)) {
                @$arrayVendorValue[$row['category']]['name'].= ", " . $row['name'];
                if ($arrayVendor[$row['category']]['brand']) {
                    if ($i % $this->limitOnLine == 0) {
                        $this->set('brandFirstClass', $this->firstClassName);
                    } else {
                        $this->set('brandFirstClass', '');
                    }
                    $i++;

                    $this->set('brandIcon', $row['icon']);
                    $this->set('brandName', $row['name']);
                    $desc = '';
                    if ($row['page']) {
                        $PHPShopOrm->clean();
                        $res = $PHPShopOrm->query("select content from " . $SysValue['base']['page'] . " where link = '$row[page]' LIMIT 1");
                        $page = mysqli_fetch_array($res);
                        $desc = $page['content'];
                    }

                    $this->set('brandPageLink', '/selection/?v[' . $row['category'] . ']=' . $row['id']);
                    $this->set('brandDescr', $desc);

                    $this->set('brandsList', ParseTemplateReturn('brands/top_brands_one.tpl'), true);
                }
            }
        }
        if ($this->get('brandsList'))
            return ParseTemplateReturn('brands/top_brands_main.tpl');
    }

}

class PHPShopNtCatalogElement extends PHPShopShopCatalogElement {

    /**
     * ������� ��������� � ��������
     * @return string
     */
    function leftCatalTableNt() {


        $dis = null;
        $podcatalog = null;

        $this->cell = $this->PHPShopSystem->getParam('num_row_adm');

        $table = null;
        $j = 1;
        $item = 1;

        // �������� ������
        $hook = $this->setHook(__CLASS__, __FUNCTION__, null, 'START');
        if ($hook)
            return $hook;

        if (is_array($this->data))
            foreach ($this->data as $row) {
                $dis = null;
                $podcatalog = null;
                $this->set('catalogId', $row['id']);
                $this->set('catalogTemplates', $this->getValue('dir.templates') . chr(47) . $_SESSION['skin'] . chr(47));
                $this->set('catalogTitle', $row['name']);
                $this->set('catalogName', $row['name']);

                // �������� �� ������� ������ � �������� ���������
                if (stristr($row['content'], 'img') and strlen($row['content']) < 150)
                    $this->set('catalogContent', $row['content']);
                else
                    $this->set('catalogContent', null);

                // ����� ������� ��������� �� ����, ������ ������������
                if (is_array($GLOBALS['Cache'][$this->objBase]))
                    foreach ($GLOBALS['Cache'][$this->objBase] as $val) {
                        if ($val['parent_to'] == $row['id'])
                            $podcatalog.=$this->template_cat_table($val);
                    }

                $this->set('catalogPodcatalog', $podcatalog);

                // �������� ������
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

                // ���������� ������
                $dis.= ParseTemplateReturn("catalog/catalog_table_forma.tpl");

                // ������ � ���������� (1-5)
                if ($j < $this->cell) {
                    $cell_name = 'd' . $j;
                    $$cell_name = $dis;
                    $j++;
                    if ($item == count($this->data)) {
                        $table.=$this->setCell($d1, @$d2, @$d3, @$d4, @$d5);
                    }
                } else {
                    $cell_name = 'd' . $j;
                    $$cell_name = $dis;
                    $table.=$this->setCell($d1, @$d2, @$d3, @$d4, @$d5);
                    $d1 = $d2 = $d3 = $d4 = $d5 = null;
                    $j = 1;
                }
                $item++;
            }

        $this->product_grid = $table;
        return $this->compile();
    }

    /**
     * ����� ��������� ���������
     * @param array $replace ������ ������ ������
     * @param array $where ������ ���������� �������, ������������ ��� ������ ������������� ��������
     * PHPShopShopCatalogElement::leftCatal(false,$where['id']=1);
     * @return string
     */
    function leftCatalNt($replace = null, $where = null) {
        $dis = null;
        $i = 0;

        // �������� ������
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $where, 'START');
        if ($hook)
            return $hook;

        // �������� �������
        if (empty($where))
            $where['parent_to'] = '=0';

        // �� �������� ������� ��������
        $where['skin_enabled '] = "!='1'";

        // ����������
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
        }

        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->cache_format = $this->cache_format;
        $PHPShopOrm->cache = $this->cache;
        $PHPShopOrm->debug = $this->debug;

//        print_r($GLOBALS['SysValue']['other']['thisCat']);
        $this->data = $PHPShopOrm->select(array('*'), $where, array('order' => 'num, name'), array("limit" => 100), __CLASS__, __FUNCTION__);
        if (is_array($this->data)) {
            foreach ($this->data as $row) {

                // ���������� ����������
                $this->set('catalogId', $row['id']);
                $this->set('catalogI', $i);
                $this->set('catalogTemplates', $this->getValue('dir.templates') . chr(47) . $this->PHPShopSystem->getValue('skin') . chr(47));
                $this->set('catalogPodcatalog', $this->subcatalogNt($row['id']));
                $this->set('catalogTitle', $row['title']);
                $this->set('catalogName', $row['name']);

                // �������� ������
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');


                // ���� ���� �����������
                if ($this->chek($row['id'])) {
                    $dis.=$this->parseTemplate('catalog/catalog_forma_3_nt.tpl');
                }
                // ���� ��� ������������
                else {
                    if ($row['vid'] == 1) {
                        $dis.=$this->parseTemplate('catalog/catalog_forma_2_nt.tpl');
                    } else {
                        $dis.=$this->parseTemplate('catalog/catalog_forma_nt.tpl');
                    }
                }
                $i++;
            }
        }

        // ������ ������
        if (is_array($replace)) {
            foreach ($replace as $key => $val)
                $dis = str_replace($key, $val, $dis);
        }

        return $dis;
    }

    /**
     * ����� ������������
     * @param int $n �� ��������
     * @param boolean $flag �������� ����������� ����. ������ ��� ���. TRUE - �� ���������, ��������.
     * @return string
     */
    function subcatalogNt($n, $flag = true) {

        $dis = null;

        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->cache_format = $this->cache_format;
        $PHPShopOrm->cache = $this->cache;
        $PHPShopOrm->debug = $this->debug;

        $where['parent_to'] = '=' . $n;

        // �� �������� ������� ��������
        $where['skin_enabled'] = "!='1'";

        // ����������
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
        }

        $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'num, name'), array('limit' => 100), __CLASS__, __FUNCTION__);


        if (is_array($data))
            foreach ($data as $row) {

                //������� ����������� 3 ������.
                if ($flag) {
                    $this->set('catalogPodcatalog3level', '');
                    $this->set('catalogPodcatalog3level', $this->subcatalogNt($row['id'], false));
                }

                // ���������� ����������
                $this->set('catalogName', $row['name']);
                $this->set('catalogUid', $row['id']);

                $PHPShopCategory = new PHPShopCategory($n);
                $this->set('catalogTitle', $PHPShopCategory->getName());

                // �������� ������
                $this->setHook(__CLASS__, __FUNCTION__, $row);

                // ���������� ������
                if ($flag AND $this->get('catalogPodcatalog3level'))
                    $dis.=ParseTemplateReturn('catalog/podcatalog_forma_nt_2.tpl');
                elseif ($flag)
                    $dis.=ParseTemplateReturn('catalog/podcatalog_forma_nt.tpl');
                else
                    $dis.=ParseTemplateReturn('catalog/podcatalog_forma_3level_nt.tpl');
            }
        return $dis;
    }

}

// ���� ��������� (3 ������)
$PHPShopNtCatalogElement = new PHPShopNtCatalogElement();
$PHPShopNtCatalogElement->init('leftCatalNt');
$PHPShopNtCatalogElement->init('leftCatalTableNt');

// ���� �������
$PHPShopBrandsElement = new PHPShopBrandsElement();
$PHPShopBrandsElement->init('topBrands');
?>