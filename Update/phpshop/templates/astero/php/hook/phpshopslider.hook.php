<?php

function template_slider_hook($obj, $row, $rout) {
    static $i;
    if ($rout == 'END') {

        // Активный слайдер
        if (empty($i)) {
            $obj->set('slideActive', 'active');
            $obj->set('slideIndicator', '<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>', true);
        } else {
            $obj->set('slideActive', '');
            $obj->set('slideIndicator', '<li data-target="#carousel-example-generic" data-slide-to="' . $i . '"></li>', true);
        }

        $i++;

    }
}

$addHandler = array
    (
    'index' => 'template_slider_hook',
);
?>
