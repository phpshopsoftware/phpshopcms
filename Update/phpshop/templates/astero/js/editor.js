
$(document).ready(function() {

    switch ($('.admin-modal-content').attr('data-path')) {
        case 'shop.UID':
            var pathEdit = 'product';
            var idEdit = $('.admin-modal-content').attr('data-id');
            break;

        case 'shop.CID':
            var pathEdit = 'catalog';
            var idEdit = $('.admin-modal-content').attr('data-id');
            break;
    }

    // Скрыть кнопку
    if (pathEdit === undefined && idEdit === undefined)
        $('.openAdminModal').hide();

    $(".openAdminModal").on('click', function() {
        var frame = $('.admin-modal-content').attr('src');
        
        if (frame === undefined)
            $('.admin-modal-content').attr('src', '/phpshop/admpanel/admin.php?path=' + pathEdit + '&id=' + idEdit + '&frame=true');
        
        $('#adminModal').modal('toggle');

    });

    // Editor в отдельное окно
    $('#editorwindow').on('click', function() {
        var w = '1240';
        var h = '550';
        var url = $('.admin-modal-content').attr('src');
        filemanager = window.open(url.split('&frame').join(''));
        filemanager.focus();
        $('#adminModal').modal('hide');
    });

});

