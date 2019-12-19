
<!-- Модальное окно returncall-->
<div class="modal fade bs-example-modal-sm" id="returnCallModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">@leftMenuName@</h4>
            </div>
            <div class="modal-body">
                <form role="form" method="post" name="user_forma" action="@ShopDir@/returncall/">
                    <div class="form-group">
                        <label>Имя</label>
                        <input type="text" name="returncall_mod_name" class="form-control" placeholder="Имя..." required="">
                    </div>
                    <div class="form-group">
                        <label>Телефон</label>
                        <input type="text" name="returncall_mod_tel" class="form-control" placeholder="Телефон..." required="">
                    </div>
                    <div class="form-group">
                        <label>Время звонка:</label>
                        <input class="form-control" type="text" name="returncall_mod_time_start">
                    </div>
                    <div class="form-group">
                        <label>Сообщение</label>
                        <textarea class="form-control" name="returncall_mod_message"></textarea>
                    </div>
                    @returncall_captcha@
                    <div class="modal-footer">
                        <input type="hidden" name="returncall_mod_send" value="1">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Заказать звонок</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<a href="#" data-toggle="modal" data-target="#returnCallModal">Заказать звонок</a>
