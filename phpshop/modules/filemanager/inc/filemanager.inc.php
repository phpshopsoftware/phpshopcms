<?php

    // Редактор БД
    $filemanager_mod_frame = ' 
        
<!-- Modal filemanager_mod -->
        <div class="modal" id="filemanagerModal" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <span class="btn btn-default btn-sm pull-left glyphicon glyphicon-fullscreen" id="filemanager-window" data-toggle="tooltip" data-placement="bottom" title="Увеличить размер" style="margin-right:10px"></span> 
                        <h4 class="modal-title">Файловый менеджер</h4>
                    </div>
                    <div class="modal-body">
                        <iframe class="filemanager-modal-content" frameborder="0" marginheight="10" marginwidth="0" scrolling="auto" width="100%" height="600"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <script>
        $(document).ready(function() {
        
        // Открытие менеджера в модальное окно
        $(".openFilemanagerModal").on("click", function() {
        $(".filemanager-modal-content").attr("height", $(window).height() - 150);
        $(".filemanager-modal-content").attr("src", "phpshop/modules/filemanager/server/");
        $("#filemanagerModal").modal("toggle");
        });
        
        // Открытие менеджера в отдельное окно
        $("#filemanager-window").on("click", function() {
        var url = $(".admin-modal-content").attr("src");
        filemanager = window.open("phpshop/modules/filemanager/server/?full=true");
        filemanager.focus();
        $("#filemanagerModal").modal("hide");
        });
        
        });
        </script>
        <!--/ Modal filemanager_mod -->';
    
    PHPShopParser::set('filemanager',$filemanager_mod_frame);
?>
