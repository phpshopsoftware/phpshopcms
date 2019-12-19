

<p class="text-warning">@Error@</p>
<form role="form" method="post" name="forma_message" class="template-sm">
    <div class="form-group">
        <label>Заголовок</label>
        <input type="text" name="subject" value="@php  echo $_POST[subject]; php@" class="form-control" required="">
    </div>
    <div class="form-group">
        <label>Имя</label>
        <input type="text" name="nameP" value="@php  echo $_POST[nameP]; php@" class="form-control"  required="">
    </div>
    <div class="form-group">
        <label>E-mail</label>
        <input type="email" name="mail" value="@php  echo $_POST[mail]; php@" class="form-control">
    </div>
    <div class="form-group">
        <label>Телефон</label>
        <input type="text" name="tel" value="@php  echo $_POST[tel]; php@" class="form-control">
    </div>
    <div class="form-group">
        <label>Сообщение</label>
        <textarea name="message" class="form-control" required="">@php  echo $_POST[message]; php@</textarea>
    </div>
    <div class="form-group">
        <span class="pull-right">
            <input type="hidden" name="send_f" value="ok">
            <button type="submit" class="btn btn-primary">Отправить</button>
        </span>
        <p class="small"><label><input name="rule" value="1" required="" checked="" type="checkbox"> Я согласен(а) на обработку моих персональных данных</label></p>
        @captcha@

    </div>

</form>    