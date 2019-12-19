

$(document).ready(function() {

    // Подсказки 
    $('[data-toggle="tooltip"]').tooltip({container: 'body'});

    $('[data-toggle="popover"]').popover({
        "trigger": "hover",
        "html": true
    });

});