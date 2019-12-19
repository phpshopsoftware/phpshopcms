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
        <input class="form-control" type="text" name="returncall_mod_time_start" placeholder="10.00 - 19.00">
    </div>
    <div class="form-group">
        <label>Сообщение</label>
        <textarea class="form-control" name="returncall_mod_message" placeholder="Сообщение..."></textarea>
    </div>
    @returncall_captcha@
    <div class="pull-right">
        <input type="hidden" name="returncall_mod_send" value="1">
        <button type="submit" class="btn btn-primary">Заказать звонок</button>
    </div>
    
</form>