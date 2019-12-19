<?php
/**
 * Библиотека аналитики Яндекс и Goggle
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 */
class PHPShopAnalitica {
    
    var $status = false;
    var $code;

    public function __construct() {

        // Метрика
        $this->metrica_enabled = $GLOBALS['PHPShopSystem']->getSerilizeParam('admoption.metrica_enabled');
        $this->metrica_id = intval($GLOBALS['PHPShopSystem']->getSerilizeParam('admoption.metrica_id'));
        $this->ecommerce = $GLOBALS['PHPShopSystem']->getSerilizeParam('admoption.metrica_ecommerce');

        // Аналитика
        $this->google_enabled = $GLOBALS['PHPShopSystem']->getSerilizeParam('admoption.google_enabled');
        $this->google_id = "UA-" . $GLOBALS['PHPShopSystem']->getSerilizeParam('admoption.google_id');
        $this->analitics = $GLOBALS['PHPShopSystem']->getSerilizeParam('admoption.google_analitics');
    }

    /**
     * Инициализация
     * @param string $name имя функции/метода
     * @param array $data массим данных
     */
    public function init($name, $data) {
        if (method_exists($this, $name)){
            $this->status =  $this->$name($data);
        }
    }

    /**
     * Аналитика для списка товаров
     * @param array $row данные
     */
    public function CID_Product($row) {

        if ($this->analitics or $this->ecommerce) {
            $this->code = "
        <script>
              $(window).load(function(){  
                  $('body').on('click', '.addToCartList', function(){
               var s_id = $(this).attr('data-uid');
               var s_num = $(this).attr('data-num'); 
                
               if(s_num = '') s_num = 1;
   
                $.ajax({
                type: 'POST',
                url: '/shop/UID_'+s_id+'.html?ajax=json',
                success: function(json)
                {
                    // Результат поиска
                    if (json['success'] == 1) {
                    
                       var s_price = json['price'];
                       var s_name = json['name']; 
                       var s_category = json['category']";

            if ($this->analitics)
                $this->code.="
                
                     /* Google */
                     ga('create', '" . $this->google_id . "', 'auto');  
                     ga('require', 'ec');
                     ga('ec:addProduct', {
                           'id': s_id,
                           'name': s_name,
                           'category': s_category,
                           'brand': '',
                           'variant': '',
                           'price': s_price,
                           'quantity': s_num
                            });
                     ga('ec:setAction', 'add');
                     ga('send', 'event', 'UX', 'click', 'add to cart');
                     ";

            if ($this->ecommerce)
                $this->code.="     
                
                     /* Yandex */  
                     window.dataLayer = window.dataLayer || [];
                     dataLayer.push({
                         'ecommerce': {
                             'add': {
                               'products': [{
                                    'id': s_id,
                                    'name': s_name,
                                    'price': s_price,
                                    'brand':'',
                                    'category': s_category,
                                    'quantity': s_num
                               }]
                             }
                         }
                      });
                   ";

            $this->code.="}
               }
          });
     });
  }); 
         </script>";
            return true;
        }
    }

    /**
     * Аналитика для удаления товрво из корзины
     * @param array $data данные
     */
    public function id_delete($data) {

        if ($this->analitics or $this->ecommerce) {

            $PHPShopProduct = new PHPShopProduct($data['id_delete']);

            $this->code = "<script>
    $(window).load(function(){";

            if ($this->analitics)
                $this->code.="
    
                     /* Google */
                     ga('create', '" . $this->google_id . "', 'auto');  
                     ga('require', 'ec');
                     ga('ec:addProduct', {
                           'id': '" . $data['id_delete'] . "',
                           'name': '" . $PHPShopProduct->getParam('name') . "',
                           'category': '',
                           'brand': '',
                           'variant': '" . $PHPShopProduct->getParam('uid') . "',
                           'price': '" . $PHPShopProduct->getPrice() . "',
                           'quantity': '1'
                            });
                     ga('ec:setAction', 'remove');
                     ga('send', 'event', 'UX', 'click', 'remove to cart');
                     ";

            if ($this->ecommerce)
                $this->code.="     
                  
                   /* Yandex */
                   window.dataLayer = window.dataLayer || []; 
                    dataLayer.push({
                         'ecommerce': {
                              'remove': {
                                  'products': [{
                                              'id': '" . $data['id_delete'] . "',
                                              'name': '" . $PHPShopProduct->getParam('name') . "',
                                              'category': '',
                                              'quantity': '1'
                                            }]
                                          }
                                       }
                    });
                }); 
     </script>";
         
        }
    }

    /**
     * Добавлени в корзину на остальных страницах
     */
    public function click(){
        
        if ($this->analitics or $this->ecommerce) {
            $this->code = "
         <script>  
             $(window).load(function(){                     
               $('body').on('click', '.addToCartList,.addToCartFull', function(){ 
                    
               var s_id = $(this).attr('data-uid');
               var s_num = $(this).attr('data-num'); 
   
                $.ajax({
                type: 'POST',
                url: '/shop/UID_'+s_id+'.html?ajax=json',
                success: function(json)
                {
                    // Результат поиска
                    if (json['success'] == 1) {
                    
                       var s_price = json['price'];
                       var s_name = json['name']; 
                       var s_category = json['category']";

            if ($this->analitics)
                $this->code.="
                
                     /* Google */
                     ga('create', '" . $this->google_id . "', 'auto');  
                     ga('require', 'ec');
                     ga('ec:addProduct', {
                           'id': s_id,
                           'name': s_name,
                           'category': s_category,
                           'brand': '',
                           'variant': '',
                           'price': s_price,
                           'quantity': s_num
                            });
                     ga('ec:setAction', 'add');
                     ga('send', 'event', 'UX', 'click', 'add to cart');     
                     ";

            if ($this->ecommerce)
                $this->code.="  
                
                     /* Yandex */
                     window.dataLayer = window.dataLayer || [];
                     dataLayer.push({
                         'ecommerce': {
                             'add': {
                               'products': [{
                                    'id': s_id,
                                    'name': s_name,
                                    'price': s_price,
                                    'brand':'',
                                    'category': s_category,
                                    'quantity': s_num
                               }]
                             }
                         }
                      });";

            $this->code.=" } 
                    }
                });
            }); ";

            $this->code.="   
                     });
                   </script>        
                   ";
        }
    }






    /**
     * Аналитика для подробноо описания товара
     * @param array $row данные
     */
    public function UID($row) {

        if ($this->analitics or $this->ecommerce) {
            $this->code = "
         <script>  
             $(window).load(function(){";                     
               
           
            if ($this->analitics)
                $this->code.="
                
                     /* Google */
                     ga('create', '" . $this->google_id . "', 'auto');  
                     ga('require', 'ec');
                     ga('ec:addProduct', {
                                   'id': '" . $row['id'] . "',
                                   'name': '" . $row['name'] . "',
                                   'category': '" . $row['category'] . "',
                                   'price': '" . $row['price'] . "',
                                   'brand': '',
                                   'variant': '" . $row['uid'] . "'
                                 }
                         );
                      ga('ec:setAction', 'detail');
                      ga('send', 'pageview'); ";

            if ($this->ecommerce)
                $this->code.=" 
                
                      /* Yandex */  
                      window.dataLayer = window.dataLayer || [];                    
                      dataLayer.push({
                             'ecommerce': {
                                'detail': {
                                  'products': 
                                     [{
                                        'id': '" . $row['id'] . "',
                                        'name' : '" . $row['name'] . "',
                                        'price': '" . $row['price'] . "',
                                        'brand':'',
                                        'category': '" . $row['catregory'] . "',
                                        'variant' : '" . $row['uid'] . "'
                                      },
                                      {
                                         'name': '" . $row['name'] . "',
                                         'price': '" . $row['price'] . "'
                                      }]
                                  }
                              }
                         }); ";

            $this->code.="   
                     });
                   </script>        
                   ";
            
        }
    }

    /**
     * Аналитика для создания заказа
     * @param obj $obj объект
     */
    public function send_to_order($obj) {

        if ($this->analitics or $this->ecommerce) {
            $this->code = "<script>
            $(window).load(function(){";

            // Для гугла (шапка кода)
            if ($this->analitics)
                $this->code .= "
        /* Google */
        ga('create', '" . $this->google_id . "', 'auto');
        ga('require', 'ec');";

            if ($this->ecommerce)
                $this->code .= "
        /* Yandex */      
        window.dataLayer = window.dataLayer || [];
        dataLayer.push({'ecommerce': {'purchase': {'actionField': {'id' : '" . $obj->ouid . "'},
                            'products': [";

            // Товары из корзины 
            foreach ($obj->PHPShopCart->_CART as $cart_prod) {

                // Данные для Яндекса
                if ($this->ecommerce)
                    $this->code .= "{
                    'id': '" . $cart_prod['id'] . "',
                    'name': '" . $cart_prod['name'] . "',
                    'price': '" . $cart_prod['price'] . "',
                    'quantity': '" . $cart_prod['num'] . "'
                },";

                // Данные для Гугла
                if ($this->analitics)
                    $this->code .= "ga('ec:addProduct', {
                    'id': '" . $cart_prod['id'] . "',
                    'name': '" . $cart_prod['name'] . "',
                    'price': '" . $cart_prod['price'] . "',
                    'variant': '" . $cart_prod['uid'] . "',
                    'quantity': '" . $cart_prod['num'] . "'
                    });";
            }

            // Закрываем код Яндекса
            if ($this->ecommerce)
                $this->code .= "]}}});";

            // Информация о заказе для Гугла + закрываем код
            if ($this->analitics)
                $this->code .="ga('ec:setAction', 'purchase', {
                    'id': '" . $obj->ouid . "',
                    'revenue': '" . $obj->total . "',
                    'shipping': '" . $obj->delivery . "'
                    });
                    ga('send', 'pageview');";


            $this->code .= "});
            </script>";
        }
    }

    /**
     * Вывод счетчика и аналитики
     */
    public function counter() {

        if ($this->google_enabled) {

            if($this->analitics)
            echo "
                <!-- Google Analytics -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
</script>
<!--/ Google Analytics -->";
            else echo "
                <!-- Google Analytics -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=".$this->google_id."\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '".$this->google_id."');
</script>
<!--/ Google Analytics -->
";
        }


        if ($this->metrica_enabled) {

            if ($this->ecommerce)
                $ecommerce = ',
                    ecommerce:"dataLayer"';
            else
                $ecommerce = null;

            echo '
                <!-- Yandex.Metrika counter -->
  <script>
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter' . $this->metrica_id . ' = new Ya.Metrika2({
                    id:' . $this->metrica_id . ',
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true' . $ecommerce . '
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/tag.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks2");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/' . $this->metrica_id . '" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!--/ Yandex.Metrika counter -->'; 
        }
        

        // Не сработала инициализация
        if ($this->analitics or $this->ecommerce) {
            if(!$this->status){
                $this->click();
            }
        }
        
        echo $this->code;
    }

}
?>