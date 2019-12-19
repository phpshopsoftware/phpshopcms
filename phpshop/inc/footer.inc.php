<?php

/*
 * Подвал
 */

// Перехват модуля
$PHPShopModules->setHookHandler('footer', 'footer');

// Аналитика
$PHPShopAnalitica->counter();

echo '
  </body>
</html>';
?>