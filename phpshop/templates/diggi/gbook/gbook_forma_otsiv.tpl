<div class="page-header">
    <h2>Форма отзыва</h2>
</div>


@Error@

<form role="form" method="post" name="forma_gbook">
    <div class="form-group">
        <label for="exampleInputEmail1">Имя</label>
        <input type="text" name="name_new" class="form-control" id="exampleInputEmail1" placeholder="Имя..." required="">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Email</label>
        <input type="email" name="mail_new"  class="form-control" id="exampleInputEmail1" placeholder="Email...">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Заголовок</label>
        <input type="text"  name="tema_new"  class="form-control" id="exampleInputEmail1" placeholder="Заголовок..." required="">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Отзыв</label>
        <textarea name="otsiv_new" class="form-control" maxlength="500" placeholder="Сообщение..." required=""></textarea>
    </div>

    <div class="form-group">
        <p class="small"><label><input name="rule" value="1" required="" checked="" type="checkbox"> Я согласен(а) на обработку моих персональных данных</label></p>
        @captcha@
    </div>
    <div class="form-group">
        <span class="pull-right">
            <input type="hidden" name="send_gb" value="1">
            <button type="submit" class="btn btn-primary">Отправить отзыв</button>
        </span>
    </div>
</form>
